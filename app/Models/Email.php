<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\SendEmail;

class Email extends Model {

    public $fillable = [
        'type',
        'description',
        'subject',
        'content',
        'variables'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string',
        'subject' => 'string',
        'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required',
        'subject' => 'required'
    ];

    public function parse($data)
    {
        $parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
            list($shortCode, $index) = $matches;
            if (isset($data[$index])) {
                return $data[$index];
            } else {
                /**
                 * for testing only
                 */
                //throw new Exception("Shortcode {$shortCode} not found in template id {$this->id}", 1);
            }

        }, $this->content);

        return $parsed;
    }

    public function newUser($user){
        $data = ['name' => $user->name];
        dispatch(new SendEmail('newUser', $user->email, $data));
    }

    public function verifyAccount($user, $validationLink){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'validationLink' => $validationLink
        ];

        dispatch(new SendEmail('verifyAccount', $user->email, $data));
    }

    public function resetPassword($user, $resetLink){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'resetLink' => $resetLink
        ];
        dispatch(new SendEmail('resetPassword', $user->email, $data));
    }

    public function newArtist($user, $artist){
        $data = [
            'artistName' => $artist->name,
        ];

        dispatch(new SendEmail('newArtist', $user->email, $data));
    }

    public function feedback($feedback){
        $data = [
            'email' => $feedback->email,
            'feeling' => $feedback->feeling,
            'about' => $feedback->about,
            'comment' => $feedback->comment,
            'ip' => request()->ip(),
        ];

        dispatch(new SendEmail('feedback', config('settings.admin_mail'), $data, $feedback->email));
    }

    public function subscribePlaylist($user, $playlist){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'playlistName' => $playlist->title,
            'playlistLink' => $playlist->permalink_url,
        ];

        dispatch(new SendEmail('shareMedia', $user->email, $data));
    }

    public function followUser($user, $follower){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'followerName' => $follower->name,
            'followerLink' => $follower->url,
        ];

        dispatch(new SendEmail('followUser', $user->email, $data));
    }

    public function newComment($comment){
        if($comment->commentable_type == 'App\Models\Playlist') {
            $object = Playlist::findOrFail($comment->commentable_id);
        } else if($comment->commentable_type == 'App\Models\Song') {
            $object = Song::findOrFail($comment->commentable_id);
        } else if($comment->commentable_type == 'App\Models\Album') {
            $object = Album::findOrFail($comment->commentable_id);
        } else if($comment->commentable_type == 'App\Models\Artist') {
            $object = Artist::findOrFail($comment->commentable_id);
        } else if($comment->commentable_type == 'App\Models\User') {
            $object = User::findOrFail($comment->commentable_id);
        } else if($comment->commentable_type == 'App\Models\Post') {
            $object = Post::findOrFail($comment->commentable_id);
        } else if($comment->commentable_type == 'App\Models\Station') {
            $object = Station::findOrFail($comment->commentable_id);
        }

        $data = [
            'name' => $comment->user->name,
            'username' => $comment->user->username,
            'created_at' => $comment->created_at,
            'url' => $object->permalink_url,
            'text' => $comment->content,
        ];

        dispatch(new SendEmail('newComment', config('settings.admin_mail'), $data));
    }

    public function rejectedSong($user, $song, $comment){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'title' => $song->title,
            'url' => $song->permalink_url,
            'comment' => $comment,
        ];

        dispatch(new SendEmail('rejectedSong', $user->email, $data));
    }

    public function rejectedAlbum($user, $album, $comment){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'title' => $album->title,
            'url' => $album->permalink_url,
            'comment' => $comment,
        ];

        dispatch(new SendEmail('rejectedAlbum', $user->email, $data));
    }

    public function approvedSong($user, $song){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'title' => $song->title,
            'url' => $song->permalink_url,
        ];

        dispatch(new SendEmail('approvedSong', $user->email, $data));
    }

    public function approvedAlbum($user, $album){
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'title' => $album->title,
            'url' => $album->permalink_url,
        ];

        dispatch(new SendEmail('approvedAlbum', $user->email, $data));
    }

    public function rejectedArtist($request, $comment = ""){
        $data = [
            'name' => $request->user->name,
            'artist_name' => $request->artist_name,
            'comment' => $comment,
        ];

        dispatch(new SendEmail('rejectedArtist', $request->user->email, $data));
    }

    public function approvedArtist($request){
        $data = [
            'name' => $request->user->name,
            'artist_name' => $request->artist_name,
        ];

        dispatch(new SendEmail('approvedArtist', $request->user->email, $data));
    }


    public function subscriptionReceipt($user, $subscription) {

        switch ($subscription->service->plan_period_format) {
            case 'D':
                $frequency = 'Day';
                break;
            case 'W':
                $frequency = 'Week';
                break;
            case 'M':
                $frequency = 'Month';
                break;
            case 'Y':
                $frequency = 'Year';
                break;
            default:
                $frequency = 'Month';
        }

        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'plan' => $subscription->service->title ,
            'plan_price' => (in_array(config('settings.currency', 'USD'), config('payment.currency_decimals')) ? $subscription->service->price : intval($subscription->service->price)),
            'plan_frequency' => $frequency,
            'invoice_id' => $subscription->transaction_id,
            'receipt_id' => $subscription->id . '_RECEIPT',
            'currency' => trans('symbol.' . config('settings.currency', 'USD')),
            'amount' => $subscription->last_payment_date ? (in_array(config('settings.currency', 'USD'), config('payment.currency_decimals')) ? $subscription->amount : intval($subscription->amount)) : 0.00,
            'issued_date' => $subscription->last_payment_date ? Carbon::parse($subscription->last_payment_date)->format('M d, Y') : Carbon::parse($subscription->created_at)->format('M d, Y'),
            'next_billing' => Carbon::parse($subscription->next_billing_date)->format('M d, Y'),
            'payment_gate' => ucwords($subscription->gate),
        ];

        dispatch(new SendEmail('subscriptionReceipt', $user->email, $data));
    }

    public function paymentHasBeenPaid($user, $amount) {
        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'amount' => $amount,
        ];
        dispatch(new SendEmail('paymentHasBeenPaid', $user->email, $data));
    }

    public function paymentHasBeenDeclined($user) {
        $data = [
            'name' => $user->name,
            'username' => $user->username,
        ];
        dispatch(new SendEmail('paymentHasBeenDeclined', $user->email, $data));
    }
}