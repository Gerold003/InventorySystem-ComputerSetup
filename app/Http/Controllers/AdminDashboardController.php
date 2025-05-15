<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Fetch departments with employee count and head (ensure 'head' is a valid column or relationship)
        $departments = Department::withCount('users')->get();

        // Fetch recent users (latest 5)
        $recentUsers = User::latest()->take(5)->get();

        // Fetch total users count
        $totalUsers = User::count();

        return view('admin.dashboard', compact('departments', 'recentUsers', 'totalUsers'));
    }
}
