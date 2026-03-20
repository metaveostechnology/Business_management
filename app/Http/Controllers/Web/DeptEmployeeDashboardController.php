<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeptEmployeeDashboardController extends Controller
{
    /**
     * Show the employee login form.
     */
    public function login()
    {
        return view('department.employee.auth.login');
    }

    /**
     * Show the department-specific dashboard.
     */
    public function dashboard($slug)
    {
        // Map slug to view folder
        $viewPath = $this->mapSlugToView($slug);
        
        if (view()->exists("department.employee.dashboards.{$viewPath}.dashboard")) {
            return view("department.employee.dashboards.{$viewPath}.dashboard", compact('slug'));
        }

        // Fallback to a general dashboard if specific one doesn't exist
        return view('department.employee.dashboards.general.dashboard', compact('slug'));
    }

    /**
     * Map department slug to view folder name.
     */
    private function mapSlugToView($slug)
    {
        $map = [
            'ceo-cfo' => 'ceo_cfo',
            'ceo_cfo' => 'ceo_cfo',
            'human-resource-hr-department' => 'hr',
            'hr' => 'hr',
            'finance-accounts-department' => 'finance',
            'finance' => 'finance',
            'sales-marketing-department' => 'sales',
            'sales' => 'sales',
            'project-management-department' => 'project',
            'project' => 'project',
            'education-management-department' => 'education',
            'education' => 'education',
            'hotel-management-department' => 'hotel',
            'hotel' => 'hotel',
            'service-management-department' => 'service',
            'service' => 'service',
        ];


        return $map[$slug] ?? 'general';
    }
}
