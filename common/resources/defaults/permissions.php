<?php

return [
    'roles' => [
        [
            'default' => true,
            'name' => 'users',
            'permissions' => [
                'users.view',
                'localizations.view',
                'custom_pages.view',
                'files.create',
                'plans.view',
                'tags.view',
            ]
        ],
        [
            'guests' => true,
            'name' => 'guests',
            'permissions' => [
                'users.view',
                'custom_pages.view',
                'plans.view',
                'tags.view',
                'localizations.view',
            ]
        ],
    ],
    'all' => [
        'admin' => [
            [
                'name' => 'admin.access',
                'description' => 'Required in order to access any admin area page.',
            ],
            [
                'name' => 'appearance.update',
                'description' => 'Allows access to appearance editor.'
            ],
            [
                'name' => 'reports.view',
                'description' => 'Allows access to analytics page in admin area.',
            ]
        ],

        'api' => [
            [
                'name' => 'api.access',
                'description' => 'Required in order for users to be able to use the API.',
            ],
        ],

        'roles' => [
            [
                'name' => 'roles.view',
                'advanced' => true,
            ],
            [
                'name' => 'roles.create',
                'advanced' => true,
            ],
            [
                'name' => 'roles.update',
                'advanced' => true,
            ],
            [
                'name' => 'roles.delete',
                'advanced' => true,
            ],
        ],

        'custom_pages' => [
            [
                'name' => 'custom_pages.view',
                'advanced' => true,
            ],
            [
                'name' => 'custom_pages.create',
                'advanced' => true,
                'restrictions' => [
                    [
                        'name' => 'count',
                        'type' => 'number',
                        'description' => __('policies.count_description', ['resources' => 'pages'])
                    ]
                ]
            ],
            [
                'name' => 'custom_pages.update',
                'advanced' => true,
            ],
            [
                'name' => 'custom_pages.delete',
                'advanced' => true,
            ],
        ],

        'custom_domains' => [
            'custom_domains.view',
            [
                'name' => 'custom_domains.create',
                'restrictions' => [
                    [
                        'name' => 'count',
                        'type' => 'number',
                        'description' => __('policies.count_description', ['resources' => 'domains'])
                    ]
                ]
            ],
            'custom_domains.update',
            'custom_domains.delete',
        ],

        'files' => [
            [
                'name' => 'files.view',
                'advanced' => true,
                'description' => 'Allow viewing all uploaded files on the site. Users can view their own uploads without this permission.',
            ],
            [
                'name' => 'files.create',
                'advanced' => true,
                'description' => 'Allow uploading files on the site. This permission is used by any page where it is possible for user to upload files.',
            ],
            [
                'name' => 'files.update',
                'advanced' => true,
                'description' => 'Allow editing all uploaded files on the site. Users can edit their own uploads without this permission.',
            ],
            [
                'name' => 'files.delete',
                'advanced' => true,
                'description' => 'Allow deleting all uploaded files on the site. Users can delete their own uploads (where applicable) without this permission.',
            ],
            [
                'name' => 'files.download',
                'advanced' => true,
                'description' => 'Allow downloading all uploaded files on the site. Users can download their own uploads (where applicable) without this permission.',
            ],
        ],

        'users' => [
            [
                'name' => 'users.view',
                'advanced' => false,
                'description' => 'Allow viewing user profile pages on the site. User can view their own profile without this permission.'
            ],
            [
                'name' => 'users.create',
                'advanced' => true,
                'description' => 'Allow creating users from admin area. Users can register for new accounts without this permission. Registration can be disabled from settings page.',
            ],
            [
                'name' => 'users.update',
                'advanced' => true,
                'description' => 'Allow editing details of any user on the site. User can edit their own details without this permission.',
            ],
            [
                'name' => 'users.delete',
                'advanced' => true,
                'description' => 'Allow deleting any user on the site. User can request deletion of their own account without this permission.',
            ],
        ],

        'localizations' => [
            [
                'name' => 'localizations.view',
                'advanced' => true,
            ],
            [
                'name' => 'localizations.create',
                'advanced' => true,
            ],
            [
                'name' => 'localizations.update',
                'advanced' => true,
            ],
            [
                'name' => 'localizations.delete',
                'advanced' => true,
            ],
        ],

        'settings' => [
            [
                'name' => 'settings.view',
                'advanced' => true,
            ],
            [
                'name' => 'settings.update',
                'advanced' => true,
            ],
        ],

        'plans' => [
            [
                'name' => 'plans.view',
                'advanced' => true,
            ],
            [
                'name' => 'plans.create',
                'advanced' => true,
            ],
            [
                'name' => 'plans.update',
                'advanced' => true,
            ],
            [
                'name' => 'plans.delete',
                'advanced' => true,
            ],
        ],

        'invoices' => [
            [
                'name' => 'invoices.view',
                'advanced' => true,
            ],
        ],

        'tags' => [
            [
                'name' => 'tags.view',
                'advanced' => true,
            ],
            [
                'name' => 'tags.create',
                'advanced' => true,
            ],
            [
                'name' => 'tags.update',
                'advanced' => true,
            ],
            [
                'name' => 'tags.delete',
                'advanced' => true,
            ],
        ],

        'workspaces' => [
            'workspaces.view',
            [
                'name' => 'workspaces.create',
                'restrictions' => [
                    [
                        'name' => 'count',
                        'type' => 'number',
                        'description' => __('policies.count_description', ['resources' => 'workspaces'])
                    ],
                    [
                        'name' => 'member_count',
                        'type' => 'number',
                        'description' => 'Maximum number of members workspace is allowed to have.',
                    ]
                ]
            ],
            'workspaces.update',
            'workspaces.delete'
        ],
        'workspace_members' => [
            [
                'name' => 'workspace_members.invite',
                'display_name' => 'Invite Members',
                'type' => 'workspace',
                'description' => 'Allow user to invite new members into a workspace.',
            ],
            [
                'name' => 'workspace_members.update',
                'display_name' => 'Update Members',
                'type' => 'workspace',
                'description' => 'Allow user to change role of other members.',
            ],
            [
                'name' => 'workspace_members.delete',
                'display_name' => 'Delete Members',
                'type' => 'workspace',
                'description' => 'Allow user to remove members from workspace.',
            ]
        ]
    ]
];
