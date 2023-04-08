<?php namespace Common\Database\Seeds;

use Common\Localizations\Localization;
use Common\Localizations\LocalizationsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class LocalizationsTableSeeder extends Seeder
{
    /**
     * @var LocalizationsRepository
     */
    private $repository;

    /**
     * @param LocalizationsRepository $repository
     */
    public function __construct(LocalizationsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return void
     */
    public function run()
    {
        $localizations = Localization::all();

        if ($localizations->isNotEmpty()) {
            $this->mergeExistingTranslationLines($localizations);
        } else {
            $this->repository->create([
                'name' => 'English',
                'language' => 'en',
            ]);
        }
    }

    /**
     * Merge existing localization translation lines with default ones.
     *
     * @param Collection $localizations
     */
    private function mergeExistingTranslationLines($localizations)
    {
        $defaultLines = $this->repository->getDefaultTranslationLines();

        $localizations->each(function ($localization) use ($defaultLines) {
            $this->repository->storeLocalizationLines(
                $localization,
                array_merge(
                    $defaultLines,
                    $this->repository->getLocalizationLines($localization),
                ),
            );
        });
    }
}
