<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyFrontendController extends Controller
{
    /**
     * Show the company login form.
     */
    public function login()
    {
        return view('company.auth.login');
    }

    /**
     * Show the company registration form.
     */
    public function register()
    {
        return view('company.auth.register');
    }

    /**
     * Show the company dashboard.
     */
    public function dashboard()
    {
        return view('company.dashboard');
    }

    /**
     * Show the company profile setup/edit page.
     */
    public function profile()
    {
        return view('company.profile');
    }
}
