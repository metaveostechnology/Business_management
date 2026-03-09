<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    public function create()
    {
        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        try {
            $this->companyService->createCompany($request->validated());
            return redirect()->route('admins.index')->with('success', 'Company created successfully.');
        } catch (Throwable $e) {
            Log::error('Web Company store error', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'An error occurred while creating the company.');
        }
    }
}
