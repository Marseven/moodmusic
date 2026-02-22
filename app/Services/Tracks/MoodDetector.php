<?php

namespace App\Services\Tracks;

use App\Track;

class MoodDetector
{
    protected array $genreMapping = [
        'energetic' => [
            'electronic', 'dance', 'edm', 'techno', 'house', 'dubstep',
            'drum-and-bass', 'hardstyle', 'trance', 'industrial', 'punk',
            'metal', 'hardcore', 'garage', 'breakbeat', 'hip-hop', 'rap',
            'trap', 'rock', 'hard-rock', 'heavy-metal',
        ],
        'chill' => [
            'ambient', 'chill', 'trip-hop', 'downtempo', 'lo-fi', 'new-age',
            'acoustic', 'folk', 'bossa-nova', 'easy-listening', 'jazz',
            'smooth-jazz', 'lounge',
        ],
        'romantic' => [
            'r-n-b', 'soul', 'slow', 'ballad', 'romance', 'blues',
            'neo-soul', 'rnb', 'r&b',
        ],
        'happy' => [
            'pop', 'afrobeat', 'reggae', 'soca', 'kompa', 'dancehall',
            'k-pop', 'j-pop', 'latin', 'salsa', 'cumbia', 'funk', 'disco',
            'ska', 'swing', 'afro', 'reggaeton',
        ],
        'focused' => [
            'classical', 'piano', 'study', 'instrumental', 'soundtrack',
            'opera', 'chamber-music', 'minimal-techno', 'post-rock',
            'symphony', 'orchestral',
        ],
        'melancholic' => [
            'emo', 'gothic', 'grunge', 'dark-ambient', 'post-punk',
            'doom-metal', 'sad',
        ],
        'spiritual' => [
            'gospel', 'worship', 'christian', 'church', 'religious',
            'hymn', 'gregorian', 'chant', 'praise',
        ],
    ];

    protected array $keywordMapping = [
        'energetic' => ['energy', 'power', 'fire', 'beast', 'rage', 'fury', 'bass', 'club', 'party'],
        'chill' => ['chill', 'relax', 'calm', 'smooth', 'breeze', 'sunset', 'peace'],
        'romantic' => ['love', 'heart', 'kiss', 'baby', 'tender', 'sweet', 'amour', 'mon coeur'],
        'happy' => ['happy', 'joy', 'fun', 'bright', 'sunshine', 'summer', 'celebrate', 'fête'],
        'focused' => ['study', 'focus', 'meditate', 'silence', 'contemplation'],
        'melancholic' => ['sad', 'tears', 'pain', 'broken', 'lonely', 'cry', 'sorrow', 'triste'],
        'spiritual' => ['god', 'dieu', 'pray', 'prière', 'bless', 'faith', 'holy', 'amen', 'gloire', 'praise'],
    ];

    public function detect(Track $track): ?string
    {
        $scores = array_fill_keys(array_keys($this->genreMapping), 0);

        // Score from genres
        $genreNames = $track->genres()->pluck('name')->map(fn ($n) => strtolower($n))->toArray();

        foreach ($genreNames as $genreName) {
            foreach ($this->genreMapping as $mood => $genres) {
                if (in_array($genreName, $genres, true)) {
                    $scores[$mood] += 3;
                }
            }
        }

        // If genres gave a clear winner, return it
        $maxScore = max($scores);
        if ($maxScore >= 3) {
            return $this->topMood($scores);
        }

        // Fallback: keyword matching on track name + artist name
        $trackName = strtolower($track->name ?? '');
        $artistNames = strtolower(
            $track->artists()->pluck('name')->implode(' ')
        );
        $text = "$trackName $artistNames";

        foreach ($this->keywordMapping as $mood => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($text, $kw)) {
                    $scores[$mood] += 1;
                }
            }
        }

        $maxScore = max($scores);
        if ($maxScore === 0) {
            return null;
        }

        return $this->topMood($scores);
    }

    protected function topMood(array $scores): string
    {
        arsort($scores);
        return array_key_first($scores);
    }
}
