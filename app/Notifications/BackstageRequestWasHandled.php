<?php

namespace App\Notifications;

use App\BackstageRequest;
use App\Services\UrlGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BackstageRequestWasHandled extends Notification
{
    use Queueable;

    public function __construct(
        protected BackstageRequest $backstageRequest,
        protected ?string $notes,
    ) {
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage())
            ->greeting(
                __('Hi :name,', [
                    'name' => $this->backstageRequest->user->display_name,
                ]),
            )
            ->subject(
                __(':sitename backstage request :status', [
                    'sitename' => config('app.name'),
                    'status' => $this->backstageRequest->status,
                ]),
            )
            ->line(
                __('Your backstage request was :status.', [
                    'status' => $this->backstageRequest->status,
                ]),
            );

        if ($this->notes) {
            $message->line($this->notes);
        }

        return $message->action(
            __('Open artist profile'),
            $this->getMainAction(),
        );
    }

    public function toArray($notifiable)
    {
        return [
            'mainAction' => [
                'action' => $this->getMainAction(),
                'label' => __('Open artist profile'),
            ],
            'lines' => [
                [
                    'content' => __('Your backstage request was :status.', [
                        'status' => $this->backstageRequest->status,
                    ]),
                    'type' => 'primary',
                ],
            ],
        ];
    }

    private function getMainAction(): string
    {
        return $this->backstageRequest->artist
            ? app(UrlGenerator::class)->artist($this->backstageRequest->artist)
            : app(UrlGenerator::class)->home();
    }
}
