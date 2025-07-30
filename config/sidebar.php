<?php

return [
    'menu' => [
        // Dashboard
        [
            'icon'       => 'fa fa-tachometer-alt',
            'title'      => 'Dashboard',
            'route-name' => 'dashboard-v2'
        ],

        // User Section
        [
            'icon'     => 'fa fa-user',
            'title'    => 'User',
            'sub_menu' => [
                [
                    'title'      => 'Add',
                    'route-name' => 'user.create'
                ],
                [
                    'title'      => 'All',
                    'route-name' => 'user.index'
                ]
            ]
        ],

        // Occurrence Section
        [
            'icon'     => 'fa fa-book',
            'title'    => 'Occurrence',
            'sub_menu' => [
                [
                    'title'      => 'Add',
                    'route-name' => 'occurrence.create'
                ],
                [
                    'title'      => 'All',
                    'route-name' => 'occurrence.index'
                ]
            ]
        ],

        // Zones Section
        [
            'icon'     => 'fa fa-map-marker-alt',
            'title'    => 'Zones',
            'sub_menu' => [
                [
                    'title'      => 'Add',
                    'route-name' => 'zones.create'
                ],
                [
                    'title'      => 'All',
                    'route-name' => 'zones.index'
                ]
            ]
        ],

        // Hostels Section
        [
            'icon'     => 'fa fa-building',
            'title'    => 'Hostels',
            'sub_menu' => [
                [
                    'title'      => 'Add',
                    'route-name' => 'hostels.create'
                ],
                [
                    'title'      => 'All',
                    'route-name' => 'hostels.index'
                ]
            ]
        ]
    ]
];
