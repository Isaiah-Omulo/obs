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
            'route-name' => 'user.index'
            
        ],

        // Occurrence Section
        [
            'icon'     => 'fa fa-book',
            'title'    => 'Occurrence',
            'route-name' => 'occurrence.index'
            
        ],

        // Zones Section
        [
            'icon'     => 'fa fa-map-marker-alt',
            'title'    => 'Zones',
            'route-name' => 'zones.index'
            
        ],

        // Hostels Section
        [
            'icon'     => 'fa fa-building',
            'title'    => 'Hostels',
            'route-name' => 'hostels.index'
            

        ],

         // Reports Section
        [
            'icon'     => 'fa fa-file-alt',
            'title'    => 'Reports',
            'sub_menu' => [
                [
                    'title'      => 'Add',
                    'route-name' => 'daily_reports.create'
                ],
                [
                    'title'      => 'Zonal Reports',
                    'route-name' => 'daily_reports.index' // You can filter by user role or zone in controller/view
                ],
                [
                    'title'      => 'Administrator/Coordinator',
                    'route-name' => 'daily_reports.admin' // You'll define this route in web.php and controller
                ]
            ]
        ],
         [
            'icon'     => 'fa fa-chart-bar',
            'title'    => 'Student Statistics',
            'route-name' => 'student_statistics.index'
          
        ],

        
    ],
   

];
