<?php

namespace App\Services\Tracks;

use App\Track;

class WaveformAnalyzer
{
    /**
     * Analyze waveform data for a track.
     *
     * @return array{energy: float, variance: float, peakRatio: float, quietIntro: bool}|null
     */
    public function analyze(Track $track): ?array
    {
        $disk = $track->getWaveStorageDisk();
        $path = "waves/{$track->id}.json";

        if (!$disk->exists($path)) {
            return null;
        }

        $raw = $disk->get($path);
        $bars = json_decode($raw, true);

        if (!is_array($bars) || count($bars) < 5) {
            return null;
        }

        // Extract heights (index 3 of each bar)
        $heights = array_map(fn($bar) => max(0, (float) ($bar[3] ?? 0)), $bars);

        $maxHeight = max($heights);
        if ($maxHeight <= 0) {
            return null;
        }

        // Normalize to 0-1 range
        $normalized = array_map(fn($h) => $h / $maxHeight, $heights);
        $count = count($normalized);

        // Energy: mean of normalized heights
        $energy = array_sum($normalized) / $count;

        // Variance: standard deviation
        $sumSquaredDiff = 0;
        foreach ($normalized as $v) {
            $sumSquaredDiff += ($v - $energy) ** 2;
        }
        $variance = sqrt($sumSquaredDiff / $count);

        // Peak ratio: fraction of bars above 70% of max
        $peakCount = count(array_filter($normalized, fn($v) => $v > 0.7));
        $peakRatio = $peakCount / $count;

        // Quiet intro: first 10 bars average < 50% of global mean
        $introCount = min(10, $count);
        $introSlice = array_slice($normalized, 0, $introCount);
        $introAvg = array_sum($introSlice) / $introCount;
        $quietIntro = $introAvg < ($energy * 0.5);

        return [
            'energy' => round($energy, 4),
            'variance' => round($variance, 4),
            'peakRatio' => round($peakRatio, 4),
            'quietIntro' => $quietIntro,
        ];
    }
}
