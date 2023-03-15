<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 15:13
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Activity;
use App\Models\CountryLanguage;
use App\Models\Episode;
use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use App\Models\Upload;
use App\Models\Withdraw;
use Carbon\Language;
use Illuminate\Http\Request;
use DB;
use PHPUnit\Exception;
use View;
use App\Models\Artist;
use App\Models\Song;
use App\Models\Album;
use App\Models\User;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Event;
use Auth;
use Carbon\Carbon;
use Image;
use App\Models\Role;
use App\Models\Country;

class ArtistManagementController extends Controller
{
    private $request;
    private $artist;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $this->artist->setRelation('albums', $this->artist->albums()->withoutGlobalScopes()->paginate(20));
        $this->artist->setRelation('songs', $this->artist->songs()->with('tags')->orderBy('plays', 'desc')->paginate(10));
        $this->artist->follower_count = $this->artist->followers()->count();

        $counts = DB::table('popular')
            ->select(DB::raw('sum(plays) AS playSong'), DB::raw('sum(favorites) AS favoriteSong'),  DB::raw('sum(collections) AS collectSong'))
            ->where('artist_id', $this->artist->id)
            ->first();

        $songs_revenue = DB::table('stream_stats')
            ->select(DB::raw('sum(revenue) AS total, count(*) AS count'))
            ->where('streamable_type', (new Song)->getMorphClass())
            ->where('user_id', auth()->user()->id)
            ->first();

        $episodes_revenue = DB::table('stream_stats')
            ->select(DB::raw('sum(revenue) AS total, count(*) AS count'))
            ->where('streamable_type', (new Episode())->getMorphClass())
            ->where('user_id', auth()->user()->id)
            ->first();

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'artist' => $this->artist,
                'albums' => $this->artist->albums,
                'songs' => $this->artist->songs,
                'songs_revenue' => $songs_revenue,
                'episodes_revenue' => $episodes_revenue,
                'counts' => $counts
            ));
        }

        $view = View::make('artist-management.index')
            ->with('songs', $this->artist->songs)
            ->with('albums', $this->artist->albums)
            ->with('artist', $this->artist)
            ->with('songs_revenue', $songs_revenue)
            ->with('episodes_revenue', $episodes_revenue)
            ->with('counts', $counts);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function withdraw()
    {
        $this->request->validate([
            'amount' => 'required|integer',
        ]);

        if($this->request->amount > auth()->user()->balance ) {
            abort(403, "No, don't do this.");
        }

        $withdraw = new Withdraw();
        $withdraw->user_id = auth()->user()->id;
        $withdraw->amount = $this->request->amount;
        $withdraw->save();

        return response()->json(array(
            'success' => true,
        ));
    }

    public function reports()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);

        $view = View::make('artist-management.reports')
            ->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function events()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $this->artist->setRelation('songs', $this->artist->songs()->paginate(20));

        $view = View::make('artist-management.events')
            ->with('songs', $this->artist->songs)
            ->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function uploaded()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $this->artist->setRelation('songs', $this->artist->songs()->withoutGlobalScopes()->with('tags')->orderBy('approved', 'asc')->latest()->paginate(20));

        $view = View::make('artist-management.uploaded')
            ->with('songs', $this->artist->songs)
            ->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        return $view;
    }


    public function profile()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);

        $view = View::make('artist-management.profile')
            ->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function saveProfile()
    {
        $artist = Artist::findOrFail(auth()->user()->artist_id);

        $this->request->validate([
            'name' => 'required|max:100',
            'location' => 'nullable|max:100',
            'website' => 'nullable|url|max:100',
            'facebook' => 'nullable|url|max:100',
            'twitter' => 'nullable|url|max:100',
            'youtube' => 'nullable|url|max:100',
            'instagram' => 'nullable|url|max:100',
            'soundcloud' => 'nullable|url|max:100',
            'bio' => 'nullable|max:180',
            'genre' => 'nullable|array',
            'mood' => 'nullable|array',
        ]);

        $artist->name = $this->request->input('name');
        $artist->location = $this->request->input('location');
        $artist->website = $this->request->input('website');
        $artist->facebook = $this->request->input('facebook');
        $artist->twitter = $this->request->input('twitter');
        $artist->youtube = $this->request->input('youtube');
        $artist->instagram = $this->request->input('instagram');
        $artist->soundcloud = $this->request->input('soundcloud');
        $artist->bio = $this->request->input('bio');

        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');

        if(is_array($genre))
        {
            $artist->genre = implode(",", $this->request->input('genre'));
        } else {
            $artist->genre = null;
        }

        if(is_array($mood))
        {
            $artist->mood = implode(",", $this->request->input('mood'));
        } else {
            $artist->mood = null;
        }

        if ($this->request->hasFile('artwork')) {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);
            $artist->clearMediaCollection('artwork');
            $artist->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $artist->save();

        $user = auth()->user();

        if($this->request->input('payment_method') == 'paypal') {
            $this->request->validate([
                'paypal_email' => 'required|email',
            ]);
            $user->payment_method = 'paypal';
            $user->payment_paypal = $this->request->input('paypal_email');
            $user->save();
        }

        if($this->request->input('payment_method') == 'bank') {
            $this->request->validate([
                'bank_details' => 'required|string',
            ]);
            $user->payment_method = 'bank';
            $user->payment_bank = $this->request->input('bank_details');
            $user->save();
        }

        return response()->json($artist);
    }

    public function editSongPost()
    {
        $this->request->validate([
            'id' => 'required|numeric',
            'title' => 'required|max:100',
            'genre' => 'nullable|array',
            'mood' => 'nullable|array',
            'tag' => 'nullable|array',
            'copyright' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:280',
            'selling' => 'nullable',
            'release_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
        ]);

        /**
         * Validate if song belong to artist (by user_id)
         */

        if(Song::withoutGlobalScopes()->where('user_id', '=', auth()->user()->id)->where('id', '=', $this->request->input('id'))->exists()) {
            $song = Song::withoutGlobalScopes()->findOrFail($this->request->input('id'));

            if(isset($song->bpm)) {
                $this->request->validate([
                    'bpm' => 'required|numeric',
                ]);
            }

            if(intval(Role::getValue('artist_day_edit_limit')) != 0 && Carbon::parse($song->created_at)->addDay(Role::getValue('artist_day_edit_limit'))->lt(Carbon::now())) {
                return response()->json([
                    'message' => 'React the limited time to edit',
                    'errors' => array('message' => array(__('web.POPUP_EDIT_SONG_DENIED')))
                ], 403);
            } else {
                /**
                 * Change artwork if have to
                 */

                if ($this->request->hasFile('artwork')) {
                    $this->request->validate([
                        'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
                    ]);
                    $song->clearMediaCollection('artwork');
                    $song->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                        ->usingFileName(time(). '.jpg')
                        ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
                }

                if(isset($song->bpm)) {
                    $song->bpm = $this->request->input('bpm');
                }

                if ($this->request->hasFile('attachment')) {
                    $this->request->validate([
                        'attachment' => 'required|mimes:zip,rar|max:' . config('settings.max_attachment_file_size', 80960)
                    ]);
                    $song->clearMediaCollection('attachment');
                    $song->addMedia($this->request->file('attachment'))
                        ->toMediaCollection('attachment', config('settings.storage_audio_location', 'public'));
                }

                $song->title = $this->request->input('title');
                $genre = $this->request->input('genre');
                $mood = $this->request->input('mood');

                if($this->request->input('created_at'))
                {
                    $song->created_at = Carbon::parse($this->request->input('created_at'));
                }

                if(is_array($genre))
                {
                    $song->genre = implode(",", $this->request->input('genre'));
                } else {
                    $song->genre = null;
                }

                if(is_array($mood))
                {
                    $song->mood = implode(",", $this->request->input('mood'));
                } else {
                    $song->mood = null;
                }

                $tags = $this->request->input('tag');

                if(is_array($tags))
                {
                    $tags = implode(",", $tags);
                    DB::table('song_tags')->where('song_id', $song->id)->delete();

                    if( $tags ) {
                        $tags = explode( ",", $tags );
                        foreach ( $tags as $tag ) {
                            DB::table('song_tags')->insert([
                                'song_id' => $song->id,
                                'tag' => $tag
                            ]);
                        }
                    }
                }

                if($this->request->input('copyright'))
                {
                    $song->copyright = $this->request->input('copyright');
                } else {
                    $song->copyright = null;
                }

                if($this->request->input('description'))
                {
                    $song->description = $this->request->input('description');
                } else {
                    $song->description = null;
                }

                if($this->request->input('released_at'))
                {
                    $song->released_at =  Carbon::parse($this->request->input('released_at'));
                } else {
                    $song->released_at = null;
                }

                if(! $song->approved && Role::getValue('artist_mod')) {
                    $song->approved = 1;
                }

                if($this->request->input('visibility')) {
                    $song->visibility = 1;
                } else {
                    $song->visibility = 0;
                }

                if($this->request->input('comments')) {
                    $song->allow_comments = 1;
                } else {
                    $song->allow_comments = 0;
                }

                if($this->request->input('downloadable')) {
                    $song->allow_download = 1;
                } else {
                    $song->allow_download = 0;
                }

                if($this->request->input('explicit')) {
                    $song->explicit = 1;
                } else {
                    $song->explicit = 0;
                }

                if($this->request->input('selling')) {
                    $this->request->validate([
                        'price' => 'required|numeric|min:' . Role::getValue('monetization_song_min_price') . '|max:' . Role::getValue('monetization_song_max_price'),
                    ]);
                    $song->selling = 1;
                    $song->price = $this->request->input('price');
                } else {
                    $song->selling = 0;
                }

                if($this->request->input('notification')) {
                    if($this->request->input('created_at')) {
                        makeActivity(
                            auth()->user()->id,
                            auth()->user()->artist_id,
                            (new Artist)->getMorphClass(),
                            'addSong',
                            $song->id,
                            false,
                            Carbon::parse($this->request->input('created_at'))
                        );
                    } else {
                        makeActivity(
                            auth()->user()->id,
                            auth()->user()->artist_id,
                            (new Artist)->getMorphClass(),
                            'addSong',
                            $song->id
                        );
                    }
                }

                $song->copyright = $this->request->input('copyright');
                $song->save();

                return response()->json($song);
            }
        } else {
            abort(403, 'Not your song.');
        }
    }

    /**
     * Get Available Genres (set available genre in Admin panel user group and role)
     * @return array
     */

    public function genres()
    {
        $allowGenres = Genre::where('discover', 1)->get();

        $selectedGenres = array();

        if($this->request->input('object_type')) {

            if($this->request->input('object_type') == 'song') {
                $song = Song::withoutGlobalScopes()->findOrFail($this->request->input('id'));
                $selectedGenres = explode(',', $song->genre);
            } else if($this->request->input('object_type') == 'album') {
                if($this->request->input('id')) {
                    $song = Album::withoutGlobalScopes()->findOrFail($this->request->input('id'));
                    $selectedGenres = explode(',', $song->genre);
                } else {
                    $selectedGenres = [];
                }
            }

        }

        $allowGenres = $allowGenres->map(function($genre) use ($selectedGenres) {
            if( in_array($genre->id, $selectedGenres) ) $genre->selected = true;

            else $genre->selected = false;

            return $genre;
        });

        if($this->request->ajax()) {
            response()->json($allowGenres);
        }

        return $allowGenres;
    }

    /**
     * Get Available Moods (set available moods in Admin panel user group and role)
     * @return array
     */

    public function moods()
    {
        $allowMoods = Mood::all();

        $selectedMoods = array();

        if($this->request->input('object_type')) {

            if($this->request->input('object_type') == 'song') {
                $song = Song::withoutGlobalScopes()->findOrFail($this->request->input('id'));
                $selectedMoods = explode(',', $song->mood);
            } else if($this->request->input('object_type') == 'album') {

                if($this->request->input('id')) {
                    $song = Album::withoutGlobalScopes()->findOrFail($this->request->input('id'));
                    $selectedMoods = explode(',', $song->mood);
                } else {
                    $selectedMoods = [];
                }
            }

        }

        $allowMoods = $allowMoods->map(function($mood) use ($selectedMoods) {
            if( in_array($mood->id, $selectedMoods) ) $mood->selected = true;

            else $mood->selected = false;

            return $mood;
        });

        if($this->request->ajax()) {
            response()->json($allowMoods);
        }

        return $allowMoods;
    }

    public function categories()
    {
        if($this->request->ajax()) {
            response()->json(PodcastCategory::all());
        }

        return PodcastCategory::all();
    }

    public function countries()
    {
        $countries = Country::all();

        if($this->request->ajax()) {
            response()->json($countries);
        }

        return $countries;
    }

    public function languages()
    {
        $languages = CountryLanguage::where('country_code', $this->request->input('country_code'))->get();

        if($this->request->ajax()) {
            response()->json($languages);
        }

        return $languages;
    }

    public function artistChart()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);

        $fromDate = Carbon::now()->subDays(15)->format('Y/m/d H:i:s');
        $toDate = Carbon::now()->format('Y/m/d H:i:s');

        $rows = DB::table('popular')
            ->select(DB::raw('sum(plays) AS playSong'), DB::raw('sum(favorites) AS favoriteSong'),  DB::raw('sum(collections) AS collectSong'), DB::raw('DATE(created_at) as date'))
            ->where('popular.created_at', '<=',  $toDate)
            ->where('popular.created_at', '>=',  $fromDate)
            ->where('artist_id', $this->artist->id)
            ->groupBy('date')
            ->limit(50)
            ->get();

        $rows = insertMissingData($rows, ['playSong', 'favoriteSong', 'collectSong'], $fromDate, $toDate);

        $data = array();

        foreach ($rows as $item) {
            $item = (array) $item;
            $data['playSong'][] = $item['playSong'];
            $data['favoriteSong'][] = $item['favoriteSong'];
            $data['collectSong'][] = $item['collectSong'];
            $data['period'][] = Carbon::parse($item['date'])->format('m/d');
        }

        return response()->json(array(
            'success' => true,
            'data' => $data

        ));
    }

    public function songChart()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);

        $start_date = date ( "Y-m-d", (time() - (30*24*3600)) );
        $end_date = date ( "Y-m-d", time() );

        $from = strtotime( date("Y-m-d")) - (14 * 24 * 60 * 60);
        $from = date("Y-m-d", $from);

        $play_data = DB::table('popular')
            ->select(DB::raw('sum(plays) AS playSong'), DB::raw('sum(favorites) AS favoriteSong'),  DB::raw('sum(channels) AS collectSong'), 'created_at')
            ->where('popular.created_at', '<=',  date("Y-m-d"))
            ->where('popular.created_at', '>=',  $from)
            ->where('popular.song_id', $this->request->route('id'))
            //->groupBy('popular.trackId')
            ->groupBy('popular.created_at')
            ->limit(50)
            ->get();

        $data = insertMissingDate($play_data, "playSong", $from, date("Y-m-d"));

        return response()->json(array(
            'success' => true,
            'data' => $data

        ));
    }

    public function albums()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $this->artist->setRelation('albums', $this->artist->albums()->withoutGlobalScopes()->paginate(20));

        $view = View::make('artist-management.albums')
            ->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function createAlbum()
    {
        $this->request->validate([
            'title' => 'required|string|max:50',
            'type' => 'required|numeric|between:1,10',
            'description' => 'nullable|string|max:1000',
            'copyright' => 'nullable|string|max:100',
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
            'released_at' => 'nullable|date_format:m/d/Y|before:' . Carbon::now(),
            'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
        ]);

        $album = new Album();

        $album->title = $this->request->input('title');
        $album->artistIds = auth()->user()->artist_id;
        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');
        $album->type = $this->request->input('type');
        $album->description = $this->request->input('description');
        $album->copyright = $this->request->input('copyright');
        $album->visibility = $this->request->input('visibility');
        $album->user_id = auth()->user()->id;

        if($this->request->input('released_at'))
        {
            $album->created_at = Carbon::parse($this->request->input('released_at'));
        }

        if($this->request->input('created_at'))
        {
            $album->created_at = Carbon::parse($this->request->input('created_at'));
        }

        if(is_array($genre))
        {
            $album->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($mood))
        {
            $album->mood = implode(",", $this->request->input('mood'));
        }

        if(Role::getValue('artist_mod')) {
            $album->approved = 1;
        }


        $album->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
            ->usingFileName(time(). '.jpg')
            ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));

        if($this->request->input('visibility')) {
            $album->visibility = 1;
        } else {
            $album->visibility = 0;
        }

        if($this->request->input('comments')) {
            $album->allow_comments = 1;
        } else {
            $album->allow_comments = 0;
        }

        if($this->request->input('selling')) {
            $this->request->validate([
                'price' => 'required|numeric|min:' . Role::getValue('monetization_album_min_price') . '|max:' . Role::getValue('monetization_album_max_price'),
            ]);
            $album->selling = 1;
            $album->price = $this->request->input('price');
        } else {
            $album->selling = 0;
        }

        $album->save();

        return $album->makeVisible(['approved']);
    }


    public function showAlbum()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $album->makeVisible(['description']);
        $album->setRelation('songs', $album->songs()->withoutGlobalScopes()->get());

        $view = View::make('artist-management.edit-album')
            ->with('artist', $this->artist)
            ->with('album', $album);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function deleteAlbum()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        if(Album::withoutGlobalScopes()->where('user_id', '=', auth()->user()->id)->where('id', '=', $this->request->input('id'))->exists()) {
            $album = Album::withoutGlobalScopes()->findOrFail($this->request->input('id'));
            if(intval(Role::getValue('artist_day_edit_limit')) != 0 && Carbon::parse($album->created_at)->addDay(Role::getValue('artist_day_edit_limit'))->lt(Carbon::now())) {
                return response()->json([
                    'message' => 'React the limited time to edit',
                    'errors' => array('message' => array(__('web.POPUP_DELETE_ALBUM_DENIED')))
                ], 403);
            } else {
                $album->delete();
                return response()->json(array('success' => true));
            }
        } else {
            abort(403, 'Not your album.');
        }
    }

    public function deleteSong()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        if(Song::withoutGlobalScopes()->where('user_id', '=', auth()->user()->id)->where('id', '=', $this->request->input('id'))->exists()) {
            $song = Song::withoutGlobalScopes()->findOrFail($this->request->input('id'));
            if(intval(Role::getValue('artist_day_edit_limit')) != 0 && Carbon::parse($song->created_at)->addDay(Role::getValue('artist_day_edit_limit'))->lt(Carbon::now())) {
                return response()->json([
                    'message' => 'React the limited time to edit',
                    'errors' => array('message' => array(__('web.POPUP_DELETE_SONG_DENIED')))
                ], 403);
            } else {
                $song->delete();
                return response()->json(array('success' => true));
            }
        } else {
            abort(403, 'Not your song.');
        }
    }

    public function deletePodcast()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        if(Podcast::withoutGlobalScopes()->where('user_id', '=', auth()->user()->id)->where('id', '=', $this->request->input('id'))->exists()) {
            $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->input('id'));
            if(intval(Role::getValue('artist_podcast_day_edit_limit')) != 0 && Carbon::parse($podcast->created_at)->addDay(Role::getValue('artist_podcast_day_edit_limit'))->lt(Carbon::now())) {
                return response()->json([
                    'message' => 'React the limited time to edit',
                    'errors' => array('message' => array(__('web.POPUP_DELETE_PODCAST_DENIED')))
                ], 403);
            } else {
                $podcast->delete();
                return response()->json(array('success' => true));
            }
        } else {
            abort(403, 'Not your podcast.');
        }
    }


    public function sortAlbumSongs()
    {
        $this->request->validate([
            'album_id' => 'required|int',
            'removeIds' => 'nullable|string',
            'nextOrder' => 'required|string',
        ]);

        $album_id = $this->request->input('album_id');
        $removeIds = json_decode($this->request->input('removeIds'));
        $nextOrder = json_decode($this->request->input('nextOrder'));

        if(is_array($removeIds))
        {
            foreach ($removeIds as $trackId){
                DB::table('album_songs')
                    ->where('album_id', $album_id)
                    ->where('song_id', $trackId)
                    ->delete();
            }
        }

        if(is_array($nextOrder))
        {
            foreach ($nextOrder as $index => $trackId) {
                DB::table('album_songs')
                    ->where('album_id', $album_id)
                    ->where('song_id', $trackId)
                    ->update(['priority' => $index]);
            }
        }

        return response()->json(array("success" => true));
    }


    public function editAlbum()
    {
        $this->request->validate([
            'title' => 'required|string|max:50',
            'type' => 'required|numeric|between:1,10',
            'description' => 'nullable|string|max:1000',
            'copyright' => 'nullable|string|max:100',
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
            'released_at' => 'nullable|date_format:m/d/Y|before:' . Carbon::now(),
        ]);

        if(Album::withoutGlobalScopes()->where('user_id', '=', auth()->user()->id)->where('id', '=', $this->request->input('id'))->exists()) {
            $album = Album::withoutGlobalScopes()->findOrFail($this->request->input('id'));
            if(intval(Role::getValue('artist_day_edit_limit')) != 0 && Carbon::parse($album->created_at)->addDay(Role::getValue('artist_day_edit_limit'))->lt(Carbon::now())) {
                return response()->json([
                    'message' => 'React the limited time to edit',
                    'errors' => array('message' => array(__('web.POPUP_EDIT_ALBUM_DENIED')))
                ], 403);
            } else {
                if ($this->request->hasFile('artwork'))
                {
                    $this->request->validate([
                        'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
                    ]);

                    $album->clearMediaCollection('artwork');
                    $album->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                        ->usingFileName(time(). '.jpg')
                        ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
                }

                $album->title = $this->request->input('title');
                $album->description = $this->request->input('description');
                $album->visibility = $this->request->input('visibility');
                $genre = $this->request->input('genre');
                $mood = $this->request->input('mood');
                $album->type = $this->request->input('type');
                $album->description = $this->request->input('description');
                $album->copyright = $this->request->input('copyright');

                if(is_array($genre))
                {
                    $album->genre = implode(",", $this->request->input('genre'));
                } else {
                    $album->genre = null;
                }

                if(is_array($mood))
                {
                    $album->mood = implode(",", $this->request->input('mood'));
                } else {
                    $album->mood = null;
                }

                if($this->request->input('visibility')) {
                    $album->visibility = 1;
                } else {
                    $album->visibility = 0;
                }

                if($this->request->input('comments')) {
                    $album->allow_comments = 1;
                } else {
                    $album->allow_comments = 0;
                }

                if($this->request->input('selling')) {
                    $this->request->validate([
                        'price' => 'required|numeric|min:' . Role::getValue('monetization_album_min_price') . '|max:' . Role::getValue('monetization_album_max_price'),
                    ]);
                    $album->selling = 1;
                    $album->price = $this->request->input('price');
                } else {
                    $album->selling = 0;
                }

                $album->save();

                return response()->json($album);
            }
        } else {
            abort(403, 'Not your album.');
        }
    }

    public function uploadAlbum()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $allowGenres = Genre::where('discover', 1)->get();
        $allowMoods = Mood::all();

        $view = View::make('artist-management.upload')
            ->with('artist', $this->artist)
            ->with('album', $album)
            ->with('allowGenres', $allowGenres)
            ->with('allowMoods', $allowMoods);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function handleUpload()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        /** Check if user have permission to upload */

        if(! Role::getValue('artist_allow_upload')) {
            abort(403);
        }

        $res = (new Upload)->handle($this->request, $artistIds = auth()->user()->artist_id, $album->id);

        return response()->json($res);
    }

    public function podcasts()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $this->artist->setRelation('podcasts', $this->artist->podcasts()->withoutGlobalScopes()->paginate(20));

        $view = View::make('artist-management.podcasts')
            ->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function importPodcast()
    {
        $this->request->validate([
            'rss_url' => 'required|string',
            'country' => 'required|string|max:3',
            'language' => 'required|int',
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
        ]);

        $podcast = new Podcast();
        @libxml_use_internal_errors(true);
        $rss = @simplexml_load_file($this->request->input('rss_url'));

        if (false === $rss) {
            return response()->json([
                'message' => 'error',
                'errors' => array('message' => array('Can not fetch the rss.'))
            ], 403);
        }

        if (isset($rss->channel)) {
            $podcast->artist_id = auth()->user()->artist_id;
            $podcast->title = $rss->channel->title;
            $podcast->description = $rss->channel->description;
            $podcast->rss_feed_url = $this->request->input('rss_url');
            $podcast->country_code = $this->request->input('country');
            $podcast->language_id = $this->request->input('language');
            $podcast->user_id = auth()->user()->id;

            $podcast->addMediaFromUrl(reset($rss->channel->image->url))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
            $podcast->created_at = Carbon::parse($rss->channel->pubDate);
            $podcast->updated_at = Carbon::parse($rss->channel->lastBuildDate);
            $podcast->save();
        } else {
            return response()->json([
                'message' => 'error',
                'errors' => array('message' => array('RSS format does not match a podcast feed.'))
            ], 403);
        }

        if (isset($rss->channel->item)) {
            foreach ($rss->channel->item as $item) {
                if (!Episode::where('created_at', Carbon::parse($item->pubDate))->where('podcast_id', $podcast->id)->exists()) {
                    $episode = new Episode();
                    $episode->podcast_id = $podcast->id;
                    $episode->title = $item->title;
                    $episode->description = $item->description;
                    $episode->created_at = Carbon::parse($item->pubDate);
                    $episode->type = $item->enclosure['type'];
                    $episode->stream_url = $item->enclosure['url'];
                    $itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                    $episode->type = 1;
                    $episode->duration = intval(reset($itunes->duration));
                    $episode->explicit = (reset($itunes->explicit) == 'clean' || reset($itunes->explicit) == 'no' ) ? 0 : 1;
                    $episode->save();
                }
            }
        }

        return response()->json($podcast);
    }

    public function createPodcast()
    {
        if(! Role::getValue('artist_allow_podcast')) {
            abort(403, 'No permission!');
        }

        $this->request->validate([
            'title' => 'required|string|max:50',
            'category' => 'required|array',
            'description' => 'nullable|string|max:1000',
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
            'released_at' => 'nullable|date_format:m/d/Y|before:' . Carbon::now(),
            'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096),
            'language_id' => 'nullable|numeric',
            'country_code' => 'nullable|string|max:3',
        ]);

        $podcast = new Podcast();

        $podcast->title = $this->request->input('title');
        $podcast->country_code = $this->request->input('country');
        $podcast->language_id = $this->request->input('language_id');
        $podcast->artist_id = auth()->user()->artist_id;
        $category = $this->request->input('category');
        $podcast->description = $this->request->input('description');

        $this->request->input('visibility') ? $podcast->visibility = 1 : $podcast->visibility = 0;
        $this->request->input('allow_comments') ? $podcast->allow_comments = 1 : $podcast->allow_comments = 0;
        $this->request->input('allow_download') ? $podcast->allow_download = 1 : $podcast->allow_download = 0;
        $this->request->input('explicit') ? $podcast->explicit = 1 : $podcast->explicit = 0;

        $podcast->user_id = auth()->user()->id;

        if($this->request->input('created_at'))
        {
            $podcast->created_at = Carbon::parse($this->request->input('created_at'));
        }

        if(is_array($category))
        {
            $podcast->category = implode(",", $category);
        }

        if(Role::getValue('artist_podcast_mod')) {
            $podcast->approved = 1;
        }

        $podcast->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
            ->usingFileName(time(). '.jpg')
            ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));

        if($this->request->input('visibility')) {
            $podcast->visibility = 1;
        } else {
            $podcast->visibility = 0;
        }

        $podcast->save();

        return $podcast->makeVisible(['approved']);
    }


    public function editPodcast()
    {
        $this->request->validate([
            'title' => 'required|string|max:50',
            'category' => 'required|array',
            'description' => 'nullable|string|max:1000',
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
            'released_at' => 'nullable|date_format:m/d/Y|before:' . Carbon::now(),
            'language_id' => 'nullable|numeric',
            'country_code' => 'nullable|string|max:3',
        ]);

        $podcast = Podcast::findOrFail($this->request->input('id'));
        $podcast->title = $this->request->input('title');
        $podcast->country_code = $this->request->input('country');
        $podcast->language_id = $this->request->input('language');

        $category = $this->request->input('category');
        $podcast->description = $this->request->input('description');

        $this->request->input('visibility') ? $podcast->visibility = 1 : $podcast->visibility = 0;
        $this->request->input('allow_comments') ? $podcast->allow_comments = 1 : $podcast->allow_comments = 0;
        $this->request->input('allow_download') ? $podcast->allow_download = 1 : $podcast->allow_download = 0;
        $this->request->input('explicit') ? $podcast->explicit = 1 : $podcast->explicit = 0;

        $podcast->user_id = auth()->user()->id;

        if($this->request->input('created_at'))
        {
            $podcast->created_at = Carbon::parse($this->request->input('created_at'));
        }

        if(is_array($category))
        {
            $podcast->category = implode(",", $category);
        }

        if(Role::getValue('artist_podcast_mod')) {
            $podcast->approved = 1;
        }

        if($this->request->file('artwork')) {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);
            $podcast->clearMediaCollection('artwork');
            $podcast->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if($this->request->input('visibility')) {
            $podcast->visibility = 1;
        } else {
            $podcast->visibility = 0;
        }

        $podcast->save();

        return $podcast->makeVisible(['approved']);
    }

    public function showPodcast()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $podcast->setRelation('episodes', $podcast->episodes()->with('podcast')->withoutGlobalScopes()->paginate(20));

        $view = View::make('artist-management.edit-podcast')
            ->with('artist', $this->artist)
            ->with('podcast', $podcast);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function uploadPodcast()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        $view = View::make('artist-management.podcast-upload')
            ->with('artist', $this->artist)
            ->with('podcast', $podcast);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function handlePodcastUpload()
    {
        $this->artist = Artist::findOrFail(auth()->user()->artist_id);
        $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        /** Check if user have permission to upload */

        if(! Role::getValue('artist_allow_upload')) {
            abort(403);
        }

        $res = (new Upload)->handleEpisode($this->request, $podcast->id);

        return response()->json($res);
    }

    public function editEpisode()
    {
        $this->request->validate([
            'id' => 'required|numeric',
            'title' => 'required|max:100',
            'description' => 'nullable|string',
            'season' => 'nullable|numeric',
            'number' => 'nullable|numeric',
            'type' => 'nullable|numeric:in:1,2,3',
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
        ]);

        if(Episode::withoutGlobalScopes()->where('user_id', '=', auth()->user()->id)->where('id', '=', $this->request->input('id'))->exists()) {
            $episode = Episode::withoutGlobalScopes()->findOrFail($this->request->input('id'));
            if(intval(Role::getValue('artist_day_edit_limit')) != 0 && Carbon::parse($episode->created_at)->addDay(Role::getValue('artist_day_edit_limit'))->lt(Carbon::now())) {
                return response()->json([
                    'message' => 'React the limited time to edit',
                    'errors' => array('message' => array(__('web.POPUP_EDIT_SONG_DENIED')))
                ], 403);
            } else {

                $episode->title = $this->request->input('title');
                $episode->description = $this->request->input('description');
                $episode->season = $this->request->input('season');
                $episode->number = $this->request->input('number');
                $episode->type = $this->request->input('type');

                if($this->request->input('created_at'))
                {
                    $episode->created_at = Carbon::parse($this->request->input('created_at'));
                }

                if($episode->podcast->catetory && Role::getValue('artist_podcast_mod')) {
                    if(Role::getValue('artist_trusted_genre')) {
                        $trustedSection = is_array(Role::getValue('artist_podcast_trusted_categories')) ? Role::getValue('artist_podcast_trusted_categories') : array();
                        if(!array_diff(explode(',', $episode->podcast->catetory), $trustedSection)) {
                            $episode->approved = 1;
                        } else {
                            $episode->approved = 0;
                        }
                    }
                }

                if($this->request->input('visibility')) {
                    $episode->visibility = 1;
                } else {
                    $episode->visibility = 0;
                }

                if($this->request->input('downloadable')) {
                    $episode->allow_download = 1;
                } else {
                    $episode->allow_download = 0;
                }

                if($this->request->input('explicit')) {
                    $episode->explicit = 1;
                } else {
                    $episode->explicit = 0;
                }

                if($this->request->input('allow_comments')) {
                    $episode->allow_comments = 1;
                } else {
                    $episode->allow_comments = 0;
                }

                if($this->request->input('notification')) {
                    if($this->request->input('created_at')) {
                        makeActivity(
                            auth()->user()->id,
                            auth()->user()->artist_id,
                            (new Artist)->getMorphClass(),
                            'addEpisode',
                            $episode->id,
                            false,
                            Carbon::parse($this->request->input('created_at'))
                        );
                    } else {
                        makeActivity(
                            auth()->user()->id,
                            auth()->user()->artist_id,
                            (new Artist)->getMorphClass(),
                            'addEpisode',
                            $episode->id
                        );
                    }
                }

                $episode->save();

                return response()->json($episode);
            }
        } else {
            abort(403, 'Not your episode.');
        }
    }

    public function createEvent()
    {
        $this->request->validate([
            'title' => 'required|string|max:50',
            'location' => 'required|string|max:100',
            'link' => 'nullable|string|max:100',
            'started_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
        ]);

        $event = new Event();
        $event->artist_id = auth()->user()->artist_id;
        $event->title = $this->request->input('title');
        $event->location = $this->request->input('location');
        $event->link = $this->request->input('link');
        $event->started_at = Carbon::parse($this->request->input('started_at'));
        $event->save();

        makeActivity(
            auth()->user()->id,
            auth()->user()->artist_id,
            (new Artist)->getMorphClass(),
            'addEvent',
            $event->id,
            false
        );

        return response()->json($event);
    }

    public function editEvent()
    {
        $this->request->validate([
            'id' => 'required|integer',
            'title' => 'required|string|max:50',
            'location' => 'required|string|max:100',
            'link' => 'nullable|string|max:100',
            'started_at' => 'nullable|date_format:m/d/Y',
        ]);

        $event = Event::where('artist_id', auth()->user()->artist_id)
            ->where('id', $this->request->input('id'))
            ->firstOrFail();

        $event->title = $this->request->input('title');
        $event->location = $this->request->input('location');
        $event->link = $this->request->input('link');
        $event->started_at = Carbon::parse($this->request->input('started_at'));
        $event->save();

        return response()->json($event);
    }

    public function deleteEvent()
    {
        $this->request->validate([
            'id' => 'required|integer',
        ]);

        $event = Event::where('artist_id', auth()->user()->artist_id)
            ->where('id', $this->request->input('id'))
            ->firstOrFail();

        Activity::where('user_id', auth()->user()->id)
            ->where('activityable_id', auth()->user()->artist_id)
            ->where('activityable_type', 'App\Models\Artist')
            ->where('action', 'addEvent')
            ->delete();

        $event->delete();

        return response()->json(array(
            'success' => true
        ));
    }
}
