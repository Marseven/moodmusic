<?php

namespace App\Modules\YoutubeDownloader\Resources;

use App\Modules\YoutubeDownloader\Utils\Utils;

class GetVideoInfo extends HttpResponse
{
    public function getJson()
    {
        return Utils::parseQueryString($this->getResponseBody());
    }

    public function isError()
    {
        return Utils::arrayGet($this->getJson(), 'errorcode') !== null;
    }

    public function getPlayerResponse()
    {
        $playerResponse = Utils::arrayGet($this->getJson(), 'player_response');
        return json_decode($playerResponse, true);
    }
}
