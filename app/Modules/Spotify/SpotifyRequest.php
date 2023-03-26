<?php
/**
 * Modified from https://github.com/aerni/laravel-spotify
 * Copyright by aerni
 * MIT License
 */

namespace App\Modules\Spotify;

use App\Modules\Spotify\Exceptions\SpotifyApiException;
use App\Modules\Spotify\SpotifyAuth;
use GuzzleHttp\Exception\RequestException;
use App\Modules\Spotify\Clients\SpotifyClient;

class SpotifyRequest
{
    private const SPOTIFY_API_URL = 'https://api.spotify.com/v1';

    private $accessToken;

    public function __construct()
    {
        $spotifyAuth = new SpotifyAuth();
        $accessToken = $spotifyAuth->getAccessToken();
        $this->accessToken = $accessToken;
    }
    /**
     * Make the API request.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws SpotifyApiException
     */
    public function get(string $endpoint, array $params = []): array
    {
        try {
            $response = (new SpotifyClient)->get(self::SPOTIFY_API_URL.$endpoint.'?'.http_build_query($params), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accepts' => 'application/json',
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
            ]);
        } catch (RequestException $e) {
            $errorResponse = json_decode($e->getResponse()->getBody()->getContents());
            $status = $errorResponse->error->status;
            $message = $errorResponse->error->message;

            throw new SpotifyApiException($message, $status, $errorResponse);
        }

        return json_decode((string) $response->getBody(), true);
    }
}
