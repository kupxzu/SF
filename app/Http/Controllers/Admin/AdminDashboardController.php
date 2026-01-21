<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Pet;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'staff_users' => User::where('role', 'staff')->count(),
            'total_clients' => Client::count(),
            'total_pets' => Pet::count(),
            'canine_count' => Pet::where('pet_type', 'canine')->count(),
            'feline_count' => Pet::where('pet_type', 'feline')->count(),
            'recent_users' => User::latest()->take(5)->get(['id', 'name', 'username', 'email', 'role', 'created_at']),
            'recent_clients' => Client::withCount('pets')->latest()->take(5)->get(),
        ];

        return Inertia::render('admin/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
