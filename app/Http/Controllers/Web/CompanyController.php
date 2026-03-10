<?php

namespace App\Http\Controllers\Web;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    public function index(Request $request)
    {
        try {
            $companies = $this->companyService->getCompanies(
                search: $request->query('search'),
                isActive: $request->query('is_active'),
                perPage: (int) $request->query('per_page', 10)
            );

            return view('appadmin.companies.index', compact('companies'));
        } catch (Throwable $e) {
            Log::error('Web Company index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while fetching companies.');
        }
    }

    public function create()
    {
        return view('appadmin.companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        try {
            $this->companyService->createCompany($request->validated());
            return redirect()->route('companies.index')->with('success', 'Company created successfully.');
        } catch (Throwable $e) {
            Log::error('Web Company store error', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'An error occurred while creating the company.');
        }
    }

    public function show(Company $company)
    {
        try {
            return view('appadmin.companies.show', compact('company'));
        } catch (Throwable $e) {
            Log::error('Web Company show error', ['id' => $company->id, 'error' => $e->getMessage()]);
            return redirect()->route('companies.index')->with('error', 'An error occurred while fetching the company.');
        }
    }

    public function edit(Company $company)
    {
        try {
            return view('appadmin.companies.edit', compact('company'));
        } catch (Throwable $e) {
            Log::error('Web Company edit error', ['id' => $company->id, 'error' => $e->getMessage()]);
            return redirect()->route('companies.index')->with('error', 'An error occurred while fetching the company.');
        }
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {
            $this->companyService->updateCompany($company, $request->validated());
            return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
        } catch (Throwable $e) {
            Log::error('Web Company update error', ['id' => $company->id, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'An error occurred while updating the company.');
        }
    }

                public function destroy(Company $company)
{
    try {
        $this->companyService->deleteCompany($company);
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    } catch (Throwable $e) {
        Log::error('Web Company destroy error', [
            'company_id' => $company->id,
            'error' => $e->getMessage()
        ]);
        return back()->with('error', 'An error occurred while deleting the company.');
    }
}
}
