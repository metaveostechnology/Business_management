<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchAdminController extends Controller
{
    /**
     * Show the branch admin login form.
     */
    public function login()
    {
        return view('branch.auth.login');
    }

    /**
     * Show the branch admin dashboard.
     */
    public function dashboard()
    {
        return view('branch.dashboard');
    }
    /**
     * Show the branch employees management page.
     */
    public function employees()
    {
        return view('branch.employees.index');
    }
}
