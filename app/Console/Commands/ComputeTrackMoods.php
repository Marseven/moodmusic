<?php

namespace App\Console\Commands;

use App\Services\Tracks\MoodDetector;
use App\Track;
use Illuminate\Console\Command;

class ComputeTrackMoods extends Command
{
    protected $signature = 'mood:compute {--force : Recalculate mood for all tracks}';

    protected $description = 'Compute mood for tracks based on their genres and metadata';

    public function handle(MoodDetector $detector): int
    {
        $query = Track::query();

        if (!$this->option('force')) {
            $query->whereNull('mood');
        }

        $total = $query->count();

        if ($total === 0) {
            $this->info('No tracks to process.');
            return 0;
        }

        $this->info("Processing $total tracks...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updated = 0;

        $query->chunkById(200, function ($tracks) use ($detector, &$updated, $bar) {
            foreach ($tracks as $track) {
                $mood = $detector->detect($track);
                if ($mood !== $track->mood) {
                    $track->update(['mood' => $mood]);
                    $updated++;
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Done. Updated $updated tracks.");

        return 0;
    }
}
