<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\StoreNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                // Generate notifikasi otomatis secara real-time setiap halaman diload
                StoreNotification::generateAutomated();

                $role = Auth::user()->role;
                
                $unreadNotifications = StoreNotification::where('is_read', false)
                    ->where(function ($query) use ($role) {
                        $query->where('role', $role)
                              ->orWhereNull('role');
                    })
                    ->latest()
                    ->take(5)
                    ->get();
                    
                $unreadCount = StoreNotification::where('is_read', false)
                    ->where(function ($query) use ($role) {
                        $query->where('role', $role)
                              ->orWhereNull('role');
                    })
                    ->count();
                    
                $view->with(compact('unreadNotifications', 'unreadCount'));
            }
        });
    }
}
