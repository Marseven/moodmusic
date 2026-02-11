<?php

namespace App\Http\Controllers;

use App\AdSpot;
use Common\Core\BaseController;
use Common\Files\FileEntry;
use Common\Files\Response\FileResponseFactory;
use Illuminate\Support\Collection;

class AdDeliveryController extends BaseController
{
    public function next()
    {
        $ads = AdSpot::active()->get();

        if ($ads->isEmpty()) {
            return $this->success(['ad' => null]);
        }

        $ad = $this->weightedRandom($ads);
        $ad->increment('impressions');

        return $this->success([
            'ad' => [
                'id' => $ad->id,
                'name' => $ad->name,
                'audio_url' => url("api/v1/ads/{$ad->id}/audio"),
                'image_url' => $ad->image_url,
                'click_url' => $ad->click_url,
                'duration' => $ad->duration,
            ],
        ]);
    }

    /**
     * Stream ad audio publicly (no auth required).
     */
    public function audio(AdSpot $ad)
    {
        $audioUrl = $ad->audio_url;

        // If audio_url points to a file-entries endpoint, resolve and stream the file
        if (preg_match('/file-entries\/(\d+)/', $audioUrl, $matches)) {
            $fileEntry = FileEntry::find($matches[1]);
            if (!$fileEntry) {
                abort(404);
            }
            return app(FileResponseFactory::class)->create($fileEntry);
        }

        // If it's a storage path, stream directly
        $storagePath = storage_path('app/' . ltrim($audioUrl, '/'));
        if (file_exists($storagePath)) {
            return response()->file($storagePath, [
                'Content-Type' => 'audio/mpeg',
            ]);
        }

        abort(404);
    }

    public function click(AdSpot $ad)
    {
        $ad->increment('clicks');

        return $this->success();
    }

    protected function weightedRandom(Collection $ads): AdSpot
    {
        // Ensure minimum weight of 1 for all ads
        $totalWeight = $ads->sum(fn(AdSpot $ad) => max($ad->priority, 1));
        $random = mt_rand(1, $totalWeight);

        $cumulative = 0;
        foreach ($ads as $ad) {
            $cumulative += max($ad->priority, 1);
            if ($random <= $cumulative) {
                return $ad;
            }
        }

        return $ads->last();
    }
}
