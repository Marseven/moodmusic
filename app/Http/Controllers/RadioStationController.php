<?php

namespace App\Http\Controllers;

use App\RadioStation;
use Common\Core\BaseController;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RadioStationController extends BaseController
{
    public function index()
    {
        $stations = RadioStation::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->success(['stations' => $stations]);
    }

    public function show(RadioStation $radioStation)
    {
        return $this->success(['station' => $radioStation]);
    }

    public function stream(RadioStation $radioStation)
    {
        $url = $radioStation->stream_url;
        if (!$url) {
            abort(404);
        }

        $ctx = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header' => "User-Agent: MoodMusic/1.0\r\nAccept: */*\r\nIcy-MetaData: 0\r\n",
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $stream = @fopen($url, 'r', false, $ctx);
        if (!$stream) {
            abort(502, 'Unable to connect to radio stream');
        }

        // Detect content type from stream headers
        $meta = stream_get_meta_data($stream);
        $contentType = 'audio/mpeg';
        foreach ($meta['wrapper_data'] ?? [] as $header) {
            if (stripos($header, 'content-type:') === 0) {
                $contentType = trim(substr($header, 13));
                break;
            }
        }

        return new StreamedResponse(function () use ($stream) {
            set_time_limit(0);
            while (!feof($stream) && !connection_aborted()) {
                $chunk = fread($stream, 8192);
                if ($chunk === false) break;
                echo $chunk;
                flush();
            }
            fclose($stream);
        }, 200, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'no-cache, no-store',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
