<?php

return [
    'available_channels' => ['browser', 'email'],
    'subscriptions' => [
        [
            'group_name' => 'Musique',
            'subscriptions' => [
                ['notif_id' => 'A01', 'name' => 'Nouvel upload d\'artiste suivi'],
            ],
        ],
        [
            'group_name' => 'Social',
            'subscriptions' => [
                ['notif_id' => 'C01', 'name' => 'Réponse à un commentaire'],
            ],
        ],
        [
            'group_name' => 'Compte',
            'subscriptions' => [
                ['notif_id' => 'B01', 'name' => 'Demande backstage traitée'],
            ],
        ],
    ],
];
