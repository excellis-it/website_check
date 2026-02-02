<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UrlManagement;
use App\Models\UrlActivityLog;
use App\Models\User;
use App\Mail\UrlStatusDownMail;
use App\Mail\UrlStatusUpMail;
use App\Notifications\UrlDownNotification;
use App\Notifications\UrlUpNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class CheckUrlsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urls:check {--url-id= : Check specific URL by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of all registered URLs and log their activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting URL status check...');

        // Get URLs to check
        if ($this->option('url-id')) {
            $urls = UrlManagement::where('id', $this->option('url-id'))->get();
        } else {
            $urls = UrlManagement::all();
        }

        if ($urls->count() === 0) {
            $this->warn('No URLs found to check.');
            return 0;
        }

        $this->info("Checking {$urls->count()} URL(s)...");
        $bar = $this->output->createProgressBar($urls->count());
        $bar->start();

        $successCount = 0;
        $failureCount = 0;

        foreach ($urls as $url) {
            try {
                $result = $this->checkUrl($url);

                if ($result['status'] === 'up') {
                    $successCount++;
                } else {
                    $failureCount++;
                }

                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nError checking {$url->name}: {$e->getMessage()}");
                $failureCount++;
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('URL check completed!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total URLs', $urls->count()],
                ['Up', $successCount],
                ['Down', $failureCount],
            ]
        );

        return 0;
    }

    /**
     * Check a single URL
     */
    private function checkUrl(UrlManagement $url)
    {
        $startTime = microtime(true);
        $status = 'down';
        $statusCode = null;
        $errorMessage = null;
        $responseTime = null;

        try {
            $ch = curl_init($url->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request

            curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000); // Convert to milliseconds

            if ($statusCode >= 200 && $statusCode < 400) {
                $status = 'up';
            } else {
                $status = 'down';
                $errorMessage = "HTTP Status Code: {$statusCode}";
            }

            if ($curlError) {
                $status = 'down';
                $errorMessage = $curlError;
            }
        } catch (\Exception $e) {
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000);
            $status = 'down';
            $errorMessage = $e->getMessage();
        }

        // Update URL status with actual SSL verification
        $sslStatus = $this->checkSslCertificate($url->url);

        // Capture previous status to detect "Back Up" transition
        $previousStatus = $url->status;

        $url->update([
            'status' => $status === 'up' ? 'active' : 'down',
            'ssl_status' => $sslStatus,
            'last_checked_at' => now(),
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
        ]);

        // Log activity
        UrlActivityLog::create([
            'url_id' => $url->id,
            'status' => $status,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
            'checked_at' => now(),
            'log_date' => today(),
        ]);

        // Send Email if status is DOWN
        if ($status === 'down') {
            try {
                // Get assigned users
                $assignedEmails = $url->assignedUsers()->pluck('email')->toArray();

                // Get Super Admins (ADMIN role)
                $adminEmails = User::role('ADMIN')->pluck('email')->toArray();

                // Merge and unique emails
                $allRecipients = array_unique(array_filter(array_merge($assignedEmails, $adminEmails)));

                if (!empty($allRecipients)) {
                    $users = User::whereIn('email', $allRecipients)->get();
                    Notification::send($users, new UrlDownNotification($url));
                    Log::info("Down notification sent to " . $users->count() . " users for URL: {$url->name}");
                }
            } catch (\Exception $e) {
                // Log email error but don't stop the process
                Log::error("Failed to send status down email for URL {$url->name}: " . $e->getMessage());
            }
        }

        // Send Email if status is BACK UP (Transition from 'down' to 'active')
        if ($status === 'up' && $previousStatus === 'down') {
            try {
                $assignedEmails = $url->assignedUsers()->pluck('email')->toArray();
                $adminEmails = User::role('ADMIN')->pluck('email')->toArray();
                $allRecipients = array_unique(array_filter(array_merge($assignedEmails, $adminEmails)));

                if (!empty($allRecipients)) {
                    $users = User::whereIn('email', $allRecipients)->get();
                    Notification::send($users, new UrlUpNotification($url));
                    Log::info("Back Up notification sent to " . $users->count() . " users for URL: {$url->name}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to send status up email for URL {$url->name}: " . $e->getMessage());
            }
        }

        return [
            'status' => $status,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
        ];
    }

    /**
     * Check SSL certificate validity for a URL
     * This method verifies if the URL has a valid SSL certificate
     *
     * @param string $url
     * @return string 'active' or 'inactive'
     */
    private function checkSslCertificate($url)
    {
        // First check if URL uses HTTPS
        $scheme = parse_url($url, PHP_URL_SCHEME);

        // If not HTTPS, SSL is inactive
        if ($scheme !== 'https') {
            return 'inactive';
        }

        // Try to verify SSL certificate
        try {
            $ch = curl_init($url);

            // Configure cURL to verify SSL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request only

            // IMPORTANT: Enable SSL verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            // Execute request
            $result = curl_exec($ch);

            // Check for SSL errors
            $curlError = curl_error($ch);
            $curlErrno = curl_errno($ch);

            curl_close($ch);

            // If there's an SSL-related error, mark as inactive
            if ($curlErrno !== 0) {
                // Common SSL error codes:
                // 51 - CURLE_PEER_FAILED_VERIFICATION (SSL certificate problem)
                // 60 - CURLE_SSL_CACERT (SSL certificate problem: unable to get local issuer certificate)
                // 77 - CURLE_SSL_CACERT_BADFILE
                if (in_array($curlErrno, [51, 60, 77])) {
                    return 'inactive';
                }
            }

            // If execution was successful and no SSL errors, SSL is active
            if ($result !== false && empty($curlError)) {
                return 'active';
            }

            // Default to inactive if any errors occurred
            return 'inactive';
        } catch (\Exception $e) {
            // If any exception occurs, mark SSL as inactive
            return 'inactive';
        }
    }
}
