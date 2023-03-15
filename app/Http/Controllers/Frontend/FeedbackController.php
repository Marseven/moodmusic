<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-02
 * Time: 20:21
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Email;
use Illuminate\Http\Request;

class FeedbackController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $this->request->validate([
            'email' => 'required|string|max:50',
            'feeling' => 'required|string|max:50',
            'about' => 'required|string|max:50',
            'comment' => 'required|string|max:1000',
        ]);

        $feedback = new \stdClass();
        $feedback->email = $this->request->input('email');
        $feedback->feeling = $this->request->input('feeling');
        $feedback->about = $this->request->input('about');
        $feedback->comment = $this->request->input('comment');

        try {
            (new Email)->feedback($feedback);
        } catch (\Exception $e) {

        }

        return response()->json($feedback);
    }
}