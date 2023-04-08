<?php

use App\Actions\Channel\LoadChannelMenuItems;

return [
    [
        'name' => 'Channel',
        'type' => 'channels',
        'itemsLoader' => LoadChannelMenuItems::class,
    ]
];
