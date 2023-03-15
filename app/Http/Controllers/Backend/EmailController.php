<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 21:01
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Models\Email;

class EmailController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $emails =  Email::paginate(20);
        return view('backend.email.index')->with('emails', $emails);
    }

    public function edit()
    {
        $email =  Email::findOrFail($this->request->route('id'));

        return view('backend.email.edit')->with('email', $email);
    }

    public function editPost()
    {
        $this->request->validate([
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);

        $email = Email::findOrFail($this->request->route('id'));

        $email->subject = $this->request->input('subject');
        $email->content = $this->request->input('content');
        $email->save();

        return redirect()->route('backend.email')->with('status', 'success')->with('message', 'Email template successfully saved!');
    }
}