<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-27
 * Time: 21:28
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Song;
use Illuminate\Http\Request;
use View;
use App\Models\User;
use App\Models\Artist;
use App\Songs;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Notification;
use App\Models\Comment;
use DB;

class ProfileController
{
    private $request;
    private $profile;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function getProfile(){
        if( $this->request->is('api*') )
        {
            $this->profile = User::findOrFail($this->request->route('id'));
        } else {
            $this->profile = User::where('username', $this->request->route('username'))->firstOrFail();
        }
    }

    public function index()
    {
        $this->getProfile();

        $this->profile->setRelation('activities', $this->profile->activities()->latest()->paginate(10))
            ->setRelation('playlists', $this->profile->playlists()->with('user')->paginate(10))
            ->setRelation('recent', $this->profile->recent()->latest()->paginate(10));

        if( $this->request->is('api*') )
        {
            if($this->request->get('callback'))
            {
                $this->profile->setRelation('loved', $this->profile->loved()->limit(50)->get());

                return response()->jsonp($this->request->get('callback'), $this->profile->loved)->header('Content-Type', 'application/javascript');
            }

            return response()->json(array(
                'profile' => $this->profile,
            ));
        }

        $view = View::make('profile.index')
            ->with('profile', $this->profile);


        if ($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return $view;
    }

    public function recent(){
        $this->getProfile();

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile,
                'songs' => $this->profile->recent()->latest()->paginate(20)
            ));
        }
    }

    public function feed()
    {
        $this->getProfile();

        $this->profile->setRelation('feed', $this->profile->feed()->latest()->paginate(20));

        $view = View::make('profile.feed')
            ->with('profile', $this->profile);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile
            ));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return $view;
    }

    public function posts()
    {
        $this->getProfile();

        $activity = Activity::findOrFail($this->request->route('id'));

        if($activity->user_id != $this->profile->id) {
            abort(404);
        }

        $view = View::make('profile.posts')
            ->with('profile', $this->profile)
            ->with('activity', $activity);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile,
                'activity' => $activity
            ));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return $view;
    }

    public function playlists(){
        $this->getProfile();

        if( $this->request->is('api*') )
        {
            $this->profile->setRelation('playlists', $this->profile->playlists()->with('user')->paginate(20));

            return response()->json(array(
                'profile' => $this->profile
            ));
        }

        $view = View::make('profile.playlists')
            ->with('profile', $this->profile);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return $view;
    }

    public function collection()
    {
        $this->getProfile();

        $this->profile->setRelation('collection', $this->profile->collection()->paginate(20));

        $view = View::make('profile.collection')
            ->with('profile', $this->profile);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile,
                'songs' => $this->profile->collection
            ));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        getMetatags($this->profile);

        return $view;
    }

    public function favorites()
    {
        $this->getProfile();

        $this->profile->setRelation('loved', $this->profile->loved()->paginate(20));

        $view = View::make('profile.favorites')
            ->with('profile', $this->profile);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile,
                'songs' => $this->profile->loved
            ));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        getMetatags($this->profile);

        return $view;
    }

    public function subscribed()
    {
        $this->getProfile();

        $this->profile = User::where('username', $this->request->route('username'))->firstOrFail();
        $this->profile->setRelation('subscribed', $this->profile->subscribed()->paginate(20));

        $view = View::make('profile.subscribed')
            ->with('profile', $this->profile);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile,
                'subscribed' => $this->profile->subscribed
            ));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return $view;
    }

    public function followers()
    {
        $this->getProfile();

        if( $this->request->is('api*') )
        {
            $this->profile->setRelation('followers', $this->profile->followers()->paginate(20));

            return response()->json(array(
                'profile' => $this->profile
            ));
        }

        $view = View::make('profile.followers')
            ->with('profile', $this->profile);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return $view;
    }

    public function following()
    {
        $this->getProfile();

        if( $this->request->is('api*') )
        {
            $this->profile->setRelation('following', $this->profile->following()->paginate(20));

            return response()->json(array(
                'profile' => $this->profile
            ));
        }

        $view = View::make('profile.following')
            ->with('profile', $this->profile);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return $view;
    }

    public function notifications()
    {
        $this->getProfile();

        $this->profile->setRelation('notifications', $this->profile->notifications());

        $view = View::make('profile.notifications')
            ->with('profile', $this->profile);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile
            ));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        getMetatags($this->profile);

        return $view;
    }

    public function now_playing()
    {
        $this->getProfile();

        if( $this->request->is('api*') || $this->request->isMethod('post'))
        {
            $currentSong = Song::find($this->request->input('currentId'));
            $queueIds = $this->request->input('queueIds');
            //remove empty values in array
            $queueIds = array_filter($queueIds, 'strlen');
            //safe sql by remove set integer for all song id key
            $queueIds = array_filter($queueIds, function($a) {return intval($a);});
            $queueSongs = Song::whereIn('id', $queueIds)->orderBy(DB::raw('FIELD(id, ' .  implode(',', $queueIds) . ')', 'FIELD'))->get();

            return response()->json(array(
                'success' => true,
                'currentSong' => $currentSong,
                'queueSongs' => $queueSongs,
            ));
        }

        $this->profile->suggest = Song::where('plays', '>', 0)->limit(10)->get();

        if($this->request->ajax()) {
            $view = View::make('profile.now_playing')->with('profile', $this->profile);
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->profile);

        return view('profile.now_playing')->with('profile', $this->profile);
    }

    public function purchased()
    {
        $this->getProfile();

        $this->profile->setRelation('purchased', Order::where('user_id', $this->profile->id)->latest()->paginate(20));

        $view = View::make('profile.purchased')
            ->with('profile', $this->profile);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'profile' => $this->profile
            ));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        getMetatags($this->profile);

        return $view;
    }

}