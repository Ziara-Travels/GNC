<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyConversionService
{
    private $apiKey;
    private $baseUrl;
    private $defaultCurrency = 'USD';

    public function __construct()
    {
        $this->apiKey = config('services.exchange_rate.api_key');
        $this->baseUrl = config('services.exchange_rate.base_url', 'https://api.exchangerate-api.com/v4');
    }

    public function detectUserCurrency($request)
    {
        // Try to detect currency from various sources
        $currency = $this->detectFromIP($request->ip()) 
                   ?? $this->detectFromHeaders($request) 
                   ?? $this->detectFromSession($request) 
                   ?? $this->defaultCurrency;

        return strtoupper($currency);
    }

    private function detectFromIP($ip)
    {
        try {
            $response = Http::get("http://ip-api.com/json/{$ip}");
            
            if ($response->successful()) {
                $data = $response->json();
                return $this->getCountryCurrency($data['countryCode'] ?? null);
            }
        } catch (\Exception $e) {
            Log::warning('IP geolocation failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    private function detectFromHeaders($request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if ($acceptLanguage) {
            // Parse language header to get country code
            $languages = explode(',', $acceptLanguage);
            $primaryLanguage = explode(';', $languages[0])[0];
            
            if (strpos($primaryLanguage, '-') !== false) {
                $parts = explode('-', $primaryLanguage);
                $countryCode = strtoupper($parts[1]);
                return $this->getCountryCurrency($countryCode);
            }
        }

        return null;
    }

    private function detectFromSession($request)
    {
        return session('user_currency');
    }

    private function getCountryCurrency($countryCode)
    {
        $currencyMap = [
            'US' => 'USD', 'UK' => 'GBP', 'GB' => 'GBP', 'CA' => 'CAD',
            'AU' => 'AUD', 'NZ' => 'NZD', 'JP' => 'JPY', 'CN' => 'CNY',
            'IN' => 'INR', 'BR' => 'BRL', 'MX' => 'MXN', 'KE' => 'KES',
            'NG' => 'NGN', 'ZA' => 'ZAR', 'EG' => 'EGP', 'MA' => 'MAD',
            'DE' => 'EUR', 'FR' => 'EUR', 'IT' => 'EUR', 'ES' => 'EUR',
            'NL' => 'EUR', 'BE' => 'EUR', 'AT' => 'EUR', 'PT' => 'EUR',
            'GR' => 'EUR', 'IE' => 'EUR', 'FI' => 'EUR', 'LU' => 'EUR',
            'CH' => 'CHF', 'SE' => 'SEK', 'NO' => 'NOK', 'DK' => 'DKK',
            'PL' => 'PLN', 'CZ' => 'CZK', 'HU' => 'HUF', 'RO' => 'RON',
            'RU' => 'RUB', 'TR' => 'TRY', 'AE' => 'AED', 'SA' => 'SAR',
            'IL' => 'ILS', 'TH' => 'THB', 'SG' => 'SGD', 'MY' => 'MYR',
            'ID' => 'IDR', 'PH' => 'PHP', 'VN' => 'VND', 'KR' => 'KRW',
            'TW' => 'TWD', 'HK' => 'HKD', 'AR' => 'ARS', 'CL' => 'CLP',
            'CO' => 'COP', 'PE' => 'PEN', 'UY' => 'UYU', 'PY' => 'PYG'
        ];

        return $currencyMap[$countryCode] ?? null;
    }

    public function getExchangeRates($baseCurrency = 'USD')
    {
        $cacheKey = "exchange_rates_{$baseCurrency}";
        
        return Cache::remember($cacheKey, 3600, function() use ($baseCurrency) {
            try {
                $response = Http::get("{$this->baseUrl}/latest/{$baseCurrency}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rates'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error('Exchange rate API failed', ['error' => $e->getMessage()]);
            }

            // Return mock rates if API fails
            return $this->getMockRates();
        });
    }

    public function convertPrice($amount, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rates = $this->getExchangeRates($fromCurrency);
        
        if (!isset($rates[$toCurrency])) {
            Log::warning("Exchange rate not found for {$fromCurrency} to {$toCurrency}");
            return $amount; // Return original amount if conversion fails
        }

        return round($amount * $rates[$toCurrency], 2);
    }

    public function convertFlightPrices($flightData, $targetCurrency)
    {
        if (!isset($flightData['price'])) {
            return $flightData;
        }

        $originalPrice = $flightData['price']['total'] ?? 0;
        $originalCurrency = $flightData['price']['currency'] ?? 'USD';

        $convertedPrice = $this->convertPrice($originalPrice, $originalCurrency, $targetCurrency);

        $flightData['price']['total'] = $convertedPrice;
        $flightData['price']['currency'] = $targetCurrency;
        $flightData['price']['original_total'] = $originalPrice;
        $flightData['price']['original_currency'] = $originalCurrency;
        $flightData['price']['exchange_rate'] = $this->getExchangeRate($originalCurrency, $targetCurrency);

        return $flightData;
    }

    public function getExchangeRate($fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        $rates = $this->getExchangeRates($fromCurrency);
        return $rates[$toCurrency] ?? 1;
    }

    public function getSupportedCurrencies()
    {
        return [
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
            'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
            'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
            'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R'],
            'AED' => ['name' => 'UAE Dirham', 'symbol' => 'AED'],
            'SAR' => ['name' => 'Saudi Riyal', 'symbol' => 'SAR'],
            'BRL' => ['name' => 'Brazilian Real', 'symbol' => 'R$'],
            'MXN' => ['name' => 'Mexican Peso', 'symbol' => '$'],
            'SGD' => ['name' => 'Singapore Dollar', 'symbol' => 'S$'],
            'HKD' => ['name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
            'NOK' => ['name' => 'Norwegian Krone', 'symbol' => 'kr'],
            'SEK' => ['name' => 'Swedish Krona', 'symbol' => 'kr'],
            'DKK' => ['name' => 'Danish Krone', 'symbol' => 'kr'],
            'PLN' => ['name' => 'Polish Zloty', 'symbol' => 'zł'],
            'CZK' => ['name' => 'Czech Koruna', 'symbol' => 'Kč'],
            'HUF' => ['name' => 'Hungarian Forint', 'symbol' => 'Ft'],
            'RUB' => ['name' => 'Russian Ruble', 'symbol' => '₽'],
            'TRY' => ['name' => 'Turkish Lira', 'symbol' => '₺'],
            'THB' => ['name' => 'Thai Baht', 'symbol' => '฿'],
            'MYR' => ['name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
            'IDR' => ['name' => 'Indonesian Rupiah', 'symbol' => 'Rp'],
            'PHP' => ['name' => 'Philippine Peso', 'symbol' => '₱'],
            'VND' => ['name' => 'Vietnamese Dong', 'symbol' => '₫'],
            'KRW' => ['name' => 'South Korean Won', 'symbol' => '₩'],
            'TWD' => ['name' => 'Taiwan Dollar', 'symbol' => 'NT$'],
        ];
    }

    public function formatCurrency($amount, $currency)
    {
        $currencies = $this->getSupportedCurrencies();
        $symbol = $currencies[$currency]['symbol'] ?? $currency;
        
        return $symbol . number_format($amount, 2);
    }

    private function getMockRates()
    {
        return [
            'EUR' => 0.85,
            'GBP' => 0.73,
            'JPY' => 110.0,
            'CAD' => 1.25,
            'AUD' => 1.35,
            'CHF' => 0.92,
            'CNY' => 6.45,
            'INR' => 74.5,
            'KES' => 108.0,
            'NGN' => 411.0,
            'ZAR' => 14.8,
            'AED' => 3.67,
            'SAR' => 3.75,
            'BRL' => 5.2,
            'MXN' => 20.1,
            'SGD' => 1.35,
            'HKD' => 7.8,
            'NOK' => 8.6,
            'SEK' => 8.9,
            'DKK' => 6.3,
            'PLN' => 3.9,
            'CZK' => 21.5,
            'HUF' => 295.0,
            'RUB' => 73.0,
            'TRY' => 8.5,
            'THB' => 33.0,
            'MYR' => 4.1,
            'IDR' => 14250.0,
            'PHP' => 50.0,
            'VND' => 23000.0,
            'KRW' => 1180.0,
            'TWD' => 28.0,
        ];
    }
}