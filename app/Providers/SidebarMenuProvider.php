<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

class SidebarMenuProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.sidebar', function ($view) {

            $menu = config('sidebar.menu');

            // Get distinct roles from users table
            $roles = User::select('role')
                        ->distinct()
                        ->whereNotNull('role')
                        ->get();

            // Insert roles dynamically into Reports submenu
            foreach ($menu as &$item) {
                if ($item['title'] === 'Reports') {

                    $dynamic = [];

                    foreach ($roles as $role) {
                        $dynamic[] = [
                            'title' => ucfirst(str_replace('_', ' ', $role->role)) . ' Reports',
                            'route-name' => 'daily_reports.role',
                            'params' => ['role' => $role->role]
                        ];
                    }

                    // Merge into the submenu
                    $item['sub_menu'] = array_merge($item['sub_menu'], $dynamic);
                }
            }

            $view->with('sidebarMenu', $menu);
        });
    }
}
