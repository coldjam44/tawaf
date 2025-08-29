<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationService
{
    /**
     * Get visitor country using multiple fallback methods
     */
    public function getVisitorCountry($ip = null): array
    {
        $ip = $ip ?? $this->getVisitorIP();
        
        // Return default for local/private IPs
        if ($this->isLocalIP($ip)) {
            return [
                'ip' => $ip,
                'country' => 'Unknown',
                'country_code' => 'XX',
                'method' => 'local_ip'
            ];
        }

        // Try cached result first
        $cacheKey = "visitor_country_{$ip}";
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        // Try multiple IP geolocation services
        $result = $this->tryIpApi($ip) ?? 
                  $this->tryFreeGeoIP($ip) ?? 
                  $this->tryIpGeolocation($ip) ?? 
                  $this->getDefaultCountry($ip);

        // Cache result for 24 hours
        Cache::put($cacheKey, $result, now()->addDay());

        return $result;
    }

    /**
     * Get visitor IP address
     */
    private function getVisitorIP(): string
    {
        // Check for various headers that might contain the real IP
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return request()->ip() ?? '127.0.0.1';
    }

    /**
     * Try ip-api.com service
     */
    private function tryIpApi(string $ip): ?array
    {
        try {
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    return [
                        'ip' => $ip,
                        'country' => $data['country'] ?? 'Unknown',
                        'country_code' => $data['countryCode'] ?? 'XX',
                        'city' => $data['city'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'method' => 'ip-api'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("IP-API failed for {$ip}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Try freegeoip.app service
     */
    private function tryFreeGeoIP(string $ip): ?array
    {
        try {
            $response = Http::timeout(5)->get("https://freegeoip.app/json/{$ip}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'ip' => $ip,
                    'country' => $data['country_name'] ?? 'Unknown',
                    'country_code' => $data['country_code'] ?? 'XX',
                    'city' => $data['city'] ?? null,
                    'region' => $data['region_name'] ?? null,
                    'method' => 'freegeoip'
                ];
            }
        } catch (\Exception $e) {
            Log::warning("FreeGeoIP failed for {$ip}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Try ipgeolocation.io service (requires free API key)
     */
    private function tryIpGeolocation(string $ip): ?array
    {
        $apiKey = env('IPGEOLOCATION_API_KEY');
        
        if (!$apiKey) {
            return null;
        }

        try {
            $response = Http::timeout(5)->get("https://api.ipgeolocation.io/ipgeo", [
                'apiKey' => $apiKey,
                'ip' => $ip
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'ip' => $ip,
                    'country' => $data['country_name'] ?? 'Unknown',
                    'country_code' => $data['country_code2'] ?? 'XX',
                    'city' => $data['city'] ?? null,
                    'region' => $data['state_prov'] ?? null,
                    'method' => 'ipgeolocation'
                ];
            }
        } catch (\Exception $e) {
            Log::warning("IPGeolocation failed for {$ip}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Check if IP is local/private
     */
    private function isLocalIP(string $ip): bool
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * Return default country data
     */
    private function getDefaultCountry(string $ip): array
    {
        return [
            'ip' => $ip,
            'country' => 'Unknown',
            'country_code' => 'XX',
            'city' => null,
            'region' => null,
            'method' => 'fallback'
        ];
    }
}