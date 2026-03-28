<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeSelfDashboardController extends Controller
{
    /**
     * Show the employee self-service login form.
     */
    public function login()
    {
        return view('employee_self.auth.login');
    }

    /**
     * Show the employee self-service dashboard.
     */
    public function dashboard()
    {
        return view('employee_self.dashboard');
    }
}
