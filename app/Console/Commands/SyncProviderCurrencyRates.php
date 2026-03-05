<?php

namespace App\Console\Commands;

use App\Services\Currency\ProviderCurrencySyncService;
use Illuminate\Console\Command;

class SyncProviderCurrencyRates extends Command
{
    protected $signature = 'providers:sync-currency-rates {--target=}';
    protected $description = 'Sync provider conversion rates to local currency using market FX rates';

    public function handle(ProviderCurrencySyncService $syncService): int
    {
        $target = $this->option('target');
        $result = $syncService->syncToLocalCurrency($target ?: null);

        if (!($result['success'] ?? false)) {
            $this->error((string) ($result['message'] ?? 'Currency sync failed.'));
            return self::FAILURE;
        }

        $this->info((string) ($result['message'] ?? 'Currency sync completed.'));
        return self::SUCCESS;
    }
}
