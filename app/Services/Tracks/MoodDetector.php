<?php

namespace App\Services\Tracks;

use App\Track;

class MoodDetector
{
    protected WaveformAnalyzer $waveformAnalyzer;

    // 25 moods: 13 universal + 12 Gabonese cultural
    //
    // Gabonese:
    //   boma (fête explosive), bangando (débrouillardise festive),
    //   niamatos (énergie brute jeunesse), reglage (piste de danse),
    //   bwiti (sacré/transe), baka (polyphonie/forêt), mvett (épique Fang),
    //   nia_ku_rondi (amour/ballade), fatigue (mélancolie/peine),
    //   mbolo (chaleureux/fraternel), coupe_coupe (convivial/bar),
    //   olo (calme/posé)
    //
    // Universal:
    //   amusement, joy, eroticism, beauty, relaxation, sadness,
    //   dreaminess, triumph, anxiety, scariness, annoyance, defiance, pumped_up

    protected array $genreMapping = [
        // === Gabonese cultural moods ===
        'boma' => [
            'afrobeat', 'dancehall', 'soca', 'kompa', 'afro', 'highlife',
        ],
        'bangando' => [
            'reggaeton', 'funk', 'cumbia', 'grime', 'drill',
        ],
        'niamatos' => [
            'afro-pop', 'afropop', 'afro-fusion', 'urban',
        ],
        'reglage' => [
            'disco', 'dance', 'house', 'garage',
        ],
        'bwiti' => [
            'world-music', 'traditional', 'ritual', 'tribal', 'ethnic',
        ],
        'baka' => [
            'folk', 'acoustic', 'nature', 'forest',
        ],
        'mvett' => [
            'epic', 'soundtrack', 'orchestral', 'anthem',
        ],
        'nia_ku_rondi' => [
            'r-n-b', 'rnb', 'r&b', 'neo-soul', 'slow', 'ballad', 'zouk',
        ],
        'fatigue' => [
            'blues', 'soul', 'emo', 'grunge',
        ],
        'mbolo' => [
            'reggae', 'easy-listening', 'bossa-nova', 'lounge',
        ],
        'coupe_coupe' => [
            'ska', 'swing', 'latin', 'salsa',
        ],
        'olo' => [
            'ambient', 'chill', 'lo-fi', 'downtempo', 'smooth-jazz',
        ],
        // === Universal moods ===
        'amusement' => [
            'comedy', 'novelty', 'parody', 'polka',
        ],
        'joy' => [
            'pop', 'k-pop', 'j-pop',
        ],
        'eroticism' => [
            'sensual', 'trip-hop',
        ],
        'beauty' => [
            'classical', 'piano', 'symphony', 'chamber-music', 'opera',
        ],
        'relaxation' => [
            'new-age', 'meditation',
        ],
        'sadness' => [
            'gothic', 'post-punk',
        ],
        'dreaminess' => [
            'dream-pop', 'shoegaze', 'post-rock', 'ethereal', 'chillwave',
            'vaporwave', 'psychedelic', 'space-rock',
        ],
        'triumph' => [
            'gospel', 'worship', 'christian', 'hymn', 'praise',
        ],
        'anxiety' => [
            'industrial', 'noise', 'experimental', 'glitch', 'breakcore',
        ],
        'scariness' => [
            'dark-ambient', 'doom-metal', 'black-metal', 'horror',
        ],
        'annoyance' => [
            'punk', 'hardcore', 'grindcore', 'thrash',
        ],
        'defiance' => [
            'rap', 'hip-hop', 'trap', 'metal', 'hard-rock', 'heavy-metal', 'rock',
        ],
        'pumped_up' => [
            'electronic', 'edm', 'techno', 'dubstep', 'drum-and-bass',
            'hardstyle', 'trance', 'breakbeat', 'minimal-techno',
        ],
    ];

    protected array $keywordMapping = [
        // === Gabonese cultural moods ===
        'boma' => ['boma', 'fête', 'ambiance', 'chauffer', 'ça chauffe', 'le feu', 'ngoma'],
        'bangando' => ['bangando', 'quartier', 'rue', 'ghetto', 'débrouille', 'hustler'],
        'niamatos' => ['niamatos', 'demantos', 'flow', 'jeunesse', 'nouveau', 'trend'],
        'reglage' => ['réglage', 'reglage', 'piste', 'danse', 'danser', 'bouger'],
        'bwiti' => ['bwiti', 'ngombi', 'mogongo', 'ancêtre', 'sacré', 'transe', 'iboga', 'rituel'],
        'baka' => ['baka', 'forêt', 'nature', 'polyphonie', 'pygmée'],
        'mvett' => ['mvett', 'épopée', 'bravoure', 'fang', 'héros', 'guerrier', 'légende'],
        'nia_ku_rondi' => ['amour', 'mon coeur', 'ma vie', 'chéri', 'chérie', 'tendresse', 'baby', 'love', 'heart'],
        'fatigue' => ['fatigué', 'fatigue', 'peine', 'lassitude', 'ivresse', 'souffrance'],
        'mbolo' => ['mbolo', 'bienvenue', 'frère', 'sœur', 'retrouvailles', 'famille', 'accueil'],
        'coupe_coupe' => ['coupé', 'grillades', 'bar', 'bière', 'quartier', 'convivial'],
        'olo' => ['olo', 'calme', 'tranquille', 'posé', 'doux', 'soft'],
        // === Universal moods ===
        'amusement' => ['funny', 'lol', 'humour', 'blague', 'drôle', 'comedy', 'fun'],
        'joy' => ['happy', 'joy', 'joie', 'bright', 'sunshine', 'summer', 'celebrate', 'bonheur'],
        'eroticism' => ['sexy', 'sensual', 'desire', 'touch', 'body', 'corps', 'nuit', 'night'],
        'beauty' => ['beautiful', 'beauty', 'beau', 'belle', 'grace', 'elegant', 'magnifique'],
        'relaxation' => ['relax', 'smooth', 'breeze', 'sunset', 'peace', 'zen'],
        'sadness' => ['sad', 'tears', 'pain', 'broken', 'lonely', 'cry', 'sorrow', 'triste', 'pleure'],
        'dreaminess' => ['dream', 'rêve', 'float', 'cloud', 'sky', 'stars', 'étoiles', 'moon', 'lune'],
        'triumph' => ['god', 'dieu', 'pray', 'prière', 'bless', 'faith', 'holy', 'amen', 'gloire', 'praise', 'victory', 'victoire', 'champion'],
        'anxiety' => ['stress', 'anxious', 'panic', 'worry', 'nervous', 'tension'],
        'scariness' => ['fear', 'peur', 'dark', 'horror', 'sombre', 'death', 'mort', 'devil'],
        'annoyance' => ['angry', 'rage', 'colère', 'hate', 'frustration', 'agacé'],
        'defiance' => ['rebel', 'fight', 'power', 'fire', 'beast', 'fury', 'struggle', 'combat', 'force'],
        'pumped_up' => ['energy', 'pump', 'bass', 'club', 'party', 'dance', 'move', 'bounce'],
    ];

    public function __construct()
    {
        $this->waveformAnalyzer = new WaveformAnalyzer();
    }

    public function detect(Track $track): string
    {
        $moods = array_keys($this->genreMapping);
        $scores = array_fill_keys($moods, 0);

        // Level 1 — Genres (weight 3)
        $this->scoreGenres($track, $scores);

        // Level 2 — Keywords (weight 1)
        $this->scoreKeywords($track, $scores);

        // Level 3 — Waveform audio analysis (weight 2)
        $this->scoreWaveform($track, $scores);

        // Level 4 — Duration bonus (weight 1)
        $this->scoreDuration($track, $scores);

        // Return top mood, fallback to "boma" if all zeros
        // (Gabonese music is predominantly festive)
        $maxScore = max($scores);
        if ($maxScore === 0) {
            return 'boma';
        }

        return $this->topMood($scores);
    }

    protected function scoreGenres(Track $track, array &$scores): void
    {
        $genreNames = $track->genres()
            ->pluck('name')
            ->map(fn($n) => strtolower($n))
            ->toArray();

        foreach ($genreNames as $genreName) {
            foreach ($this->genreMapping as $mood => $genres) {
                if (in_array($genreName, $genres, true)) {
                    $scores[$mood] += 3;
                }
            }
        }
    }

    protected function scoreKeywords(Track $track, array &$scores): void
    {
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
    }

    protected function scoreWaveform(Track $track, array &$scores): void
    {
        $metrics = $this->waveformAnalyzer->analyze($track);
        if (!$metrics) {
            return;
        }

        $energy = $metrics['energy'];
        $variance = $metrics['variance'];
        $peakRatio = $metrics['peakRatio'];
        $quietIntro = $metrics['quietIntro'];

        // High energy + high variance → boma (explosive party) / pumped_up
        if ($energy > 0.65 && $variance > 0.15) {
            $scores['boma'] += 2;
            $scores['pumped_up'] += 2;
        }

        // High energy + moderate variance → niamatos (raw energy) / defiance
        if ($energy > 0.60 && $variance > 0.12 && $variance <= 0.18) {
            $scores['niamatos'] += 2;
            $scores['defiance'] += 1;
        }

        // Very high energy + high peaks → reglage (dance floor) / triumph
        if ($energy > 0.60 && $peakRatio > 0.40) {
            $scores['reglage'] += 2;
            $scores['triumph'] += 1;
        }

        // Moderate-high energy + moderate dynamics → bangando / joy
        if ($energy > 0.50 && $energy <= 0.70 && $variance > 0.10 && $variance <= 0.18) {
            $scores['bangando'] += 2;
            $scores['joy'] += 1;
        }

        // Low energy + low variance → olo (calm, laid-back) / relaxation
        if ($energy < 0.35 && $variance < 0.12) {
            $scores['olo'] += 2;
            $scores['relaxation'] += 2;
        }

        // Moderate energy + low variance → dreaminess / bwiti (meditative)
        if ($energy >= 0.35 && $energy <= 0.55 && $variance < 0.10) {
            $scores['dreaminess'] += 2;
            $scores['bwiti'] += 1;
        }

        // Moderate energy + low peaks → nia_ku_rondi (smooth love) / eroticism
        if ($energy >= 0.45 && $energy <= 0.65 && $peakRatio < 0.25 && $variance < 0.15) {
            $scores['nia_ku_rondi'] += 2;
            $scores['eroticism'] += 1;
        }

        // Moderate energy + moderate peaks → beauty / baka (nature beauty)
        if ($energy >= 0.40 && $energy <= 0.60 && $variance >= 0.08 && $variance <= 0.15) {
            $scores['beauty'] += 2;
            $scores['baka'] += 1;
        }

        // High variance + low energy → fatigue (melancholy) / sadness
        if ($variance > 0.20 && $energy < 0.45) {
            $scores['fatigue'] += 2;
            $scores['sadness'] += 2;
        }

        // High variance + moderate energy → anxiety
        if ($variance > 0.22 && $energy >= 0.45 && $energy < 0.60) {
            $scores['anxiety'] += 2;
        }

        // Very high variance + low energy → scariness
        if ($variance > 0.25 && $energy < 0.50) {
            $scores['scariness'] += 2;
        }

        // High energy + high variance + high peaks → annoyance (aggressive)
        if ($energy > 0.55 && $variance > 0.18 && $peakRatio > 0.30) {
            $scores['annoyance'] += 2;
        }

        // Moderate energy + moderate dynamics → mbolo (warm, welcoming) / coupe_coupe / amusement
        if ($energy >= 0.40 && $energy <= 0.60 && $variance >= 0.10 && $variance <= 0.16) {
            $scores['mbolo'] += 1;
            $scores['coupe_coupe'] += 1;
            $scores['amusement'] += 1;
        }

        // Quiet intro bonuses
        if ($quietIntro) {
            if ($energy < 0.50) {
                $scores['dreaminess'] += 1;
                $scores['bwiti'] += 1;
            }
            if ($energy < 0.40) {
                $scores['fatigue'] += 1;
            }
        }

        // Epic/narrative feel: high peaks with moderate energy
        if ($peakRatio > 0.35 && $energy >= 0.50 && $energy <= 0.65) {
            $scores['mvett'] += 2;
        }
    }

    protected function scoreDuration(Track $track, array &$scores): void
    {
        $durationMs = $track->duration ?? 0;
        $durationSec = $durationMs / 1000;

        // Long tracks → olo / relaxation / bwiti (ritual)
        if ($durationSec > 360) {
            $scores['olo'] += 1;
            $scores['relaxation'] += 1;
            $scores['bwiti'] += 1;
        }

        // Short tracks → pumped_up / niamatos
        if ($durationSec > 0 && $durationSec < 150) {
            $scores['pumped_up'] += 1;
            $scores['niamatos'] += 1;
        }
    }

    protected function topMood(array $scores): string
    {
        arsort($scores);
        return array_key_first($scores);
    }
}
