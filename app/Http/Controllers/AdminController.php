<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $pendingReports = Report::count();

        return view('admin.dashboard', compact('totalUsers', 'pendingReports'));
    }
}
