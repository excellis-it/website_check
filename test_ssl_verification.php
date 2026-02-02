#!/usr/bin/env php
<?php

/**
 * SSL Verification Test Script
 *
 * This script tests the SSL verification logic to ensure it correctly identifies
 * URLs with and without valid SSL certificates.
 *
 * Usage: php test_ssl_verification.php
 */

class SslVerifier
{
    /**
     * Check SSL certificate validity for a URL
     *
     * @param string $url
     * @return array ['ssl_status' => 'active'|'inactive', 'details' => string]
     */
    public function checkSslCertificate($url)
    {
        // First check if URL uses HTTPS
        $scheme = parse_url($url, PHP_URL_SCHEME);

        // If not HTTPS, SSL is inactive
        if ($scheme !== 'https') {
            return [
                'ssl_status' => 'inactive',
                'details' => 'URL does not use HTTPS protocol'
            ];
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
                    return [
                        'ssl_status' => 'inactive',
                        'details' => "SSL Error (Code $curlErrno): $curlError"
                    ];
                }
            }

            // If execution was successful and no SSL errors, SSL is active
            if ($result !== false && empty($curlError)) {
                return [
                    'ssl_status' => 'active',
                    'details' => 'Valid SSL certificate detected'
                ];
            }

            // Default to inactive if any errors occurred
            return [
                'ssl_status' => 'inactive',
                'details' => $curlError ?: 'Unknown SSL verification error'
            ];
        } catch (\Exception $e) {
            // If any exception occurs, mark SSL as inactive
            return [
                'ssl_status' => 'inactive',
                'details' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}

// Test URLs
$testUrls = [
    'https://exceweb.excellisit.net/' => 'Expected: INACTIVE (No valid SSL)',
    'https://google.com/' => 'Expected: ACTIVE (Valid SSL)',
    'https://www.github.com/' => 'Expected: ACTIVE (Valid SSL)',
    'http://example.com/' => 'Expected: INACTIVE (No HTTPS)',
];

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
echo "║                    SSL VERIFICATION TEST SCRIPT                       ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$verifier = new SslVerifier();

foreach ($testUrls as $url => $expected) {
    echo "Testing: $url\n";
    echo "  $expected\n";

    $result = $verifier->checkSslCertificate($url);

    $statusColor = $result['ssl_status'] === 'active' ? '✅' : '❌';
    echo "  Result: SSL Status = " . strtoupper($result['ssl_status']) . " $statusColor\n";
    echo "  Details: {$result['details']}\n";
    echo "\n";
}

echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
echo "║                         TEST COMPLETED                                ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════╝\n";
echo "\n";
