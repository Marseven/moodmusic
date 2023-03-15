<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 18:11
 */

namespace App\Http\Controllers\Backend;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Podcast;
use App\Models\Station;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use View;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Post;
use App\Models\Song;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Playlist;
use App\Models\User;

class SitemapController
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

        $view = View::make('backend.sitemap.index');

        if (file_exists(public_path('sitemap.xml'))) {
            $view->with('filemtime', Carbon::parse(filemtime(public_path('sitemap.xml')))->format('Y/m/d H:i:s'));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }


        return $view;
    }

    public function make() {

        $this->request->validate([
            'post_num' => 'required|integer|max:10000',
            'song_num' => 'required|integer|max:10000',
            'static_priority' => 'required|numeric|between:0.1,1.0',
            'song_priority' => 'required|numeric|between:0.1,1.0',
            'blog_priority' => 'required|numeric|between:0.1,1.0',
        ]);

        $create = Sitemap::create();

        //General the category
        $categories = Category::all();
        foreach ($categories as $category) {
            try {
                $create->add(Url::create($category->permalink_url)
                    ->setLastModificationDate($category->updated_at)
                    ->setPriority($this->request->input('blog_priority')));
            } catch (\Exception $e) {

            }
        }

        //General the post
        $posts = Post::orderBy('id', 'desc')->limit($this->request->input('post_num', 1000))->get();
        foreach ($posts as $post) {
            try {
                $create->add(Url::create($post->permalink_url)
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority($this->request->input('blog_priority')));
            } catch (\Exception $e) {

            }
        }

        //General the genre
        $genres = Genre::where('discover', 1)->get();
        foreach ($genres as $genre) {
            try {
                $create->add(Url::create($genre->permalink_url)
                    ->setLastModificationDate($genre->updated_at)
                    ->setPriority($this->request->input('song_priority')));
            } catch (\Exception $e) {

            }
        }

        //General the genre
        $moods = Mood::all();
        foreach ($moods as $mood) {
            try {
                $create->add(Url::create($mood->permalink_url)
                    ->setLastModificationDate($mood->updated_at)
                    ->setPriority($this->request->input('song_priority')));
            } catch (\Exception $e) {

            }
        }

        //General the playlist
        $playlists = Playlist::orderBy('id', 'desc')->limit($this->request->input('song_num', 1000))->get();
        foreach ($playlists as $playlist) {
            $user = User::find($playlist->user_id);

            if(isset($user->id)) {
                try {
                    $create->add(Url::create($playlist->permalink_url)
                        ->setLastModificationDate($playlist->updated_at)
                        ->setPriority($this->request->input('song_priority')));
                } catch (\Exception $e) {

                }
            }
        }

        //General music
        $artists = Artist::orderBy('id', 'desc')->limit($this->request->input('song_num', 1000))->get();
        foreach ($artists as $artist) {
            if(isset($user->id)) {
                try {
                    $create->add(Url::create($artist->permalink_url)
                        ->setLastModificationDate($artist->updated_at)
                        ->setPriority($this->request->input('song_priority')));
                } catch (\Exception $e) {

                }
            }
        }

        $albums = Album::orderBy('id', 'desc')->limit($this->request->input('song_num', 1000))->get();
        foreach ($albums as $album) {
            if(isset($user->id)) {
                try {
                    $create->add(Url::create($album->permalink_url)
                        ->setLastModificationDate($album->updated_at)
                        ->setPriority($this->request->input('song_priority')));
                } catch (\Exception $e) {

                }
            }
        }

        $songs = Song::orderBy('id', 'desc')->limit($this->request->input('song_num', 1000))->get();
        foreach ($songs as $song) {
            if(isset($user->id)) {
                try {
                    $create->add(Url::create($song->permalink_url)
                        ->setLastModificationDate($song->updated_at)
                        ->setPriority($this->request->input('song_priority')));
                } catch (\Exception $e) {

                }
            }
        }

        $stations = Station::orderBy('id', 'desc')->limit($this->request->input('song_num', 1000))->get();
        foreach ($stations as $station) {
            if(isset($user->id)) {
                try {
                    $create->add(Url::create($station->permalink_url)
                        ->setLastModificationDate($station->updated_at)
                        ->setPriority($this->request->input('song_priority')));
                } catch (\Exception $e) {

                }
            }
        }

        $podcasts = Podcast::orderBy('id', 'desc')->limit($this->request->input('song_num', 1000))->get();
        foreach ($podcasts as $podcast) {
            if(isset($user->id)) {
                try {
                    $create->add(Url::create($podcast->permalink_url)
                        ->setLastModificationDate($podcast->updated_at)
                        ->setPriority($this->request->input('song_priority')));
                } catch (\Exception $e) {

                }
            }
        }

        $create->writeToFile(public_path('sitemap.xml'));

        return redirect()->route('backend.sitemap')->with('status', 'success')->with('message', 'Sitemap successfully updated!');
    }
}