<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 15:44
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Lyric;
use App\Models\Song;
use Illuminate\Support\Facades\Http;
use View;

class LyricController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $row = Lyric::where('song_id', $this->request->route('id'))->first();
        if(isset($row->id)) {
            return response()->json(nl2br($row->lyrics));
        } else {
            if(env('AUTO_LYRICS')) {
                $song = Song::findOrFail($this->request->route('id'));
                return $this->getLyrics($song);
            } else {
                abort(404);
            }
        }

    }

    private function br2nl( $input ) {
        return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($input))));
    }

    public function getLyrics($song) {
        $response = Http::get("https://genius.com/api/search/multi?per_page=5&q=" . urlencode($song->title . ' ' . $song->artists->first()->name));
        $body = json_decode($response->body());

        $lyrics_page_response = Http::get($body->response->sections[1]->hits[0]->result->url);
        $lyrics_page_body = $lyrics_page_response->body();

        $document = new \DOMDocument();
        @$document->loadHTML(br2nl($lyrics_page_body));

        $divs = $document->getElementsByTagName('div');

        $lyrics_content_text = "";

        foreach ( $divs as $div )
        {
            if ( $div->hasAttribute('class') && strpos( $div->getAttribute('class'), 'Lyrics__Root-sc-1ynbvzw-1' ) !== false )
            {
                $lyrics_content_text = $div->nodeValue;
            }

            if ( $div->hasAttribute('class') && strpos( $div->getAttribute('class'), 'lyrics' ) !== false )
            {
                $lyrics_content_text = $div->nodeValue;
            }
        }

        $lyrics_content_text = str_replace('side260EmbedShare', '', $lyrics_content_text);
        $lyrics_content_text = str_replace('URLCopyEmbedCopy', '', $lyrics_content_text);
        $lyrics_content_text = preg_replace("/[\r\n]+/", "\n", trim($lyrics_content_text));

        if ($lyrics_content_text && (strlen($lyrics_content_text) > 200)){
            $lyric = new Lyric();
            $lyric->song_id = $song->id;
            $lyric->lyrics = $lyrics_content_text;
            $lyric->save();
        }

        return response()->json(nl2br($lyrics_content_text));
    }
}
