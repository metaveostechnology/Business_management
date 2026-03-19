<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeptAdminController extends Controller
{
    /**
     * Show the department admin login form.
     */
    public function login()
    {
        return view('department.auth.login');
    }

    /**
     * Show the department admin dashboard.
     */
    public function dashboard()
    {
        return view('department.dashboard');
    }

    /**
     * Show the department employees management page.
     */
    public function employees()
    {
        return view('department.employees.index');
    }
}
