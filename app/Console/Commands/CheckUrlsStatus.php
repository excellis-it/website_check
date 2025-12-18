<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UrlManagement;
use App\Models\UrlActivityLog;
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

        // Update URL status
        $sslStatus = parse_url($url->url, PHP_URL_SCHEME) === 'https' ? 'active' : 'inactive';

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

        return [
            'status' => $status,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
        ];
    }
}
