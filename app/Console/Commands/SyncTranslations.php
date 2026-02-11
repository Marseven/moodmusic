<?php

namespace App\Console\Commands;

use Common\Localizations\Localization;
use Common\Localizations\LocalizationsRepository;
use Illuminate\Console\Command;

class SyncTranslations extends Command
{
    protected $signature = 'translations:sync';
    protected $description = 'Sync new translation keys from client-translations.json into all existing localizations';

    public function handle(LocalizationsRepository $repository): int
    {
        $defaultLines = $repository->getDefaultTranslationLines();

        if (empty($defaultLines)) {
            $this->error('No default translation lines found. Run "npm run extract" first.');
            return 1;
        }

        $localizations = Localization::all();

        if ($localizations->isEmpty()) {
            $this->warn('No localizations found in the database.');
            return 0;
        }

        foreach ($localizations as $localization) {
            $existingLines = $repository->getLocalizationLines($localization);
            $newKeys = array_diff_key($defaultLines, $existingLines);

            if (empty($newKeys)) {
                $this->info("{$localization->name} ({$localization->language}): already up to date.");
                continue;
            }

            $repository->storeLocalizationLines($localization, $newKeys);
            $this->info("{$localization->name} ({$localization->language}): added " . count($newKeys) . " new keys.");
        }

        $this->info('Translation sync complete.');
        return 0;
    }
}
