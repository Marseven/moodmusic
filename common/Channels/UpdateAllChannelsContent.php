<?php

namespace Common\Channels;

use Illuminate\Console\Command;

class UpdateAllChannelsContent extends Command
{
    protected $signature = 'channels:update';

    public function handle()
    {
        app(BaseChannel::class)
            ->limit(20)
            ->get()
            ->each(function (BaseChannel $channel) {
                $channel->updateContentFromExternal();
            });
    }
}
