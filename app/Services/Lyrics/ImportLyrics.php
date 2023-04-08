<?php

namespace App\Services\Lyrics;

use App\Lyric;
use App\Track;

class ImportLyrics
{

    public function execute(int $trackId): ?Lyric {
        $track = Track::with('album.artists')->findOrFail($trackId);

        $trackName = $track->name;
        $artistName = $track->artists->first()['name'];

        // Peace Sells - 2011 Remastered => Peace Sells
        $trackName = preg_replace('/ - [0-9]{4} Remastered/', '', $trackName);

        // Zero - From the Original Motion Picture "Ralph Breaks The Internet" => Zero
        $trackName = preg_replace(
            '/- From the Original Motion Picture.*?$/',
            '',
            $trackName,
        );

        // South of the Border (feat. Camila Cabello & Cardi B) => South of the Border
        $trackName = trim(explode('(feat.', $trackName)[0]);

        $providers = [AzLyricsProvider::class, GoogleLyricsProvider::class];

        $text = null;
        foreach ($providers as $provider) {
            $text = app($provider)->getLyrics($artistName, $trackName);
            if ($text) break;
        }

        if (!$text) {
            return null;
        }

        return Lyric::create([
            'track_id' => $trackId,
            'text' => $text,
        ]);
    }
}
