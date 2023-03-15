<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Email;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $mail;
    protected $data;
    protected $from;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $mail, $data = [], $from = null)
    {
        $this->type = $type;
        $this->mail = $mail;
        $this->data = $data;
        $this->from = $from;
    }

    private function parse($data, $content)
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

        }, $content);

        return $parsed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send([], [], function ($message) {
            $template = Email::where('type', '=', $this->type)->first();
            $message->from($this->from ? $this->from : env('APP_ADMIN_EMAIL'));
            $message->to($this->mail);
            $message->subject((config('settings.mail_title') ? config('settings.mail_title') . ' ' : '') . $this->parse($this->data, $template->subject));
            $message->setBody($this->parse($this->data, $template->content), 'text/html');;
        });
    }
}
