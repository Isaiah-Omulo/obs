<?php

return [
    'menu' => [
        [
            'icon'       => 'fa fa-tachometer-alt',
            'title'      => 'Dashboard',
            'route-name' => 'dashboard.attendant' // This is their dashboard route
        ],
        [
            'icon'       => 'fa fa-book',
            'title'      => 'Occurrence',
            'route-name' => 'occurrence.index'
        ],
        [
            'icon'       => 'fa fa-chart-bar',
            'title'      => 'Student Statistics',
            'route-name' => 'student_statistics.index'
        ],

         [
            'icon'     => 'fa fa-people-arrows',
            'title'    => 'Handover/Takeover',
            'route-name' => 'takeover.create' 
        ],
         [
            'icon'     => 'fa fa-file-alt',
            'title'    => 'Reports',
            'route-name' => 'daily_reports.index' 
        ],
           [
            'icon'       => 'fa fa-tint', // A water droplet icon
            'title'      => 'Water Monitoring',
            'route-name' => 'water_monitoring.index'
        ],
    ],
];
