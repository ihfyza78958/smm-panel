<?php

namespace App\Services\Currency;

use App\Models\ActivityLog;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SmmProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProviderCurrencySyncService
{
    public function syncToLocalCurrency(?string $targetCurrency = null): array
    {
        $localCurrency = strtoupper((string) ($targetCurrency ?: Setting::get('currency_symbol', 'NPR')));
        if (!preg_match('/^[A-Z]{3}$/', $localCurrency)) {
            $localCurrency = 'NPR';
        }

        $providers = SmmProvider::whereNotNull('currency')->get();
        if ($providers->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No providers found to update conversion rates.',
                'updated' => 0,
                'rescaled_services' => 0,
                'failed_currencies' => [],
                'target_currency' => $localCurrency,
            ];
        }

        $currencies = $providers->pluck('currency')
            ->map(fn ($c) => strtoupper((string) $c))
            ->filter(fn ($c) => preg_match('/^[A-Z]{3}$/', $c))
            ->unique()
            ->values();

        $ratesToLocal = [];
        $failedCurrencies = [];

        foreach ($currencies as $currency) {
            if ($currency === $localCurrency) {
                $ratesToLocal[$currency] = 1.0;
                continue;
            }

            $rate = $this->fetchRateToLocal($currency, $localCurrency);
            if ($rate === null || $rate <= 0) {
                $failedCurrencies[] = $currency;
                continue;
            }

            $ratesToLocal[$currency] = $rate;
        }

        $updated = 0;
        $rescaledServices = 0;

        DB::beginTransaction();
        try {
            foreach ($providers as $provider) {
                $providerCurrency = strtoupper((string) $provider->currency);
                if (!isset($ratesToLocal[$providerCurrency])) {
                    continue;
                }

                $oldConversionRate = (float) ($provider->conversion_rate ?? 1);
                if ($oldConversionRate <= 0) {
                    $oldConversionRate = 1;
                }

                $newConversionRate = (float) $ratesToLocal[$providerCurrency];
                if ($newConversionRate <= 0) {
                    continue;
                }

                $provider->update([
                    'conversion_rate' => round($newConversionRate, 6),
                ]);

                if ($oldConversionRate !== $newConversionRate) {
                    $ratio = $newConversionRate / $oldConversionRate;

                    Service::where('smm_provider_id', $provider->id)
                        ->whereNotNull('provider_rate')
                        ->chunkById(200, function ($services) use ($ratio, &$rescaledServices) {
                            foreach ($services as $service) {
                                $service->update([
                                    'provider_rate' => round(((float) $service->provider_rate) * $ratio, 6),
                                    'price' => round(((float) $service->price) * $ratio, 4),
                                ]);
                                $rescaledServices++;
                            }
                        });
                }

                $updated++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to update market rates: ' . $e->getMessage(),
                'updated' => $updated,
                'rescaled_services' => $rescaledServices,
                'failed_currencies' => array_values(array_unique($failedCurrencies)),
                'target_currency' => $localCurrency,
            ];
        }

        ActivityLog::log('provider_rates_updated', "Updated {$updated} provider conversion rates to {$localCurrency}");

        $message = "Updated {$updated} provider conversion rates using live market data ({$localCurrency} target).";
        if ($rescaledServices > 0) {
            $message .= " Recalculated {$rescaledServices} services.";
        }
        if (!empty($failedCurrencies)) {
            $message .= ' Could not fetch: ' . implode(', ', array_unique($failedCurrencies)) . '.';
        }

        return [
            'success' => true,
            'message' => $message,
            'updated' => $updated,
            'rescaled_services' => $rescaledServices,
            'failed_currencies' => array_values(array_unique($failedCurrencies)),
            'target_currency' => $localCurrency,
        ];
    }

    private function fetchRateToLocal(string $base, string $target): ?float
    {
        try {
            /** @var \Illuminate\Http\Client\Response $res */
            $res = Http::timeout(10)->get('https://open.er-api.com/v6/latest/' . $base);
            if ($res->status() >= 200 && $res->status() < 300) {
                $data = json_decode((string) $res->body(), true);
                $rate = data_get($data, 'rates.' . $target);
                if (is_numeric($rate) && (float) $rate > 0) {
                    return (float) $rate;
                }
            }
        } catch (\Throwable $e) {
        }

        try {
            /** @var \Illuminate\Http\Client\Response $res */
            $res = Http::timeout(10)->get('https://api.exchangerate.host/latest', [
                'base' => $base,
                'symbols' => $target,
            ]);
            if ($res->status() >= 200 && $res->status() < 300) {
                $data = json_decode((string) $res->body(), true);
                $rate = data_get($data, 'rates.' . $target);
                if (is_numeric($rate) && (float) $rate > 0) {
                    return (float) $rate;
                }
            }
        } catch (\Throwable $e) {
        }

        return null;
    }
}
