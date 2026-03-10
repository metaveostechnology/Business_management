<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('is_active', true)->count();
        $totalAdmins = Admin::count();
        
        // Get recent companies (last 5)
        $recentCompanies = Company::latest()->take(5)->get();
        
        // Calculate growth percentages (you can implement your own logic)
        $companyGrowth = 12.5; // Example static value
        $activeGrowth = 8.3;
        $adminGrowth = 5.7;
        $pendingActions = Company::where('is_active', false)->count();
        $pendingGrowth = 2.1;
        
        return view('appadmin.dashboard', compact(
            'totalCompanies',
            'activeCompanies',
            'totalAdmins',
            'recentCompanies',
            'companyGrowth',
            'activeGrowth',
            'adminGrowth',
            'pendingActions',
            'pendingGrowth'
        ));
    }
}