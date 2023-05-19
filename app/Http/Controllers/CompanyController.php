<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CompanyController extends Controller
{
    protected $CommonController;

    public function __construct(CommonController $CommonController)
    {
        $this->CommonController = $CommonController;
    }

    public function index(Request $request)
    {
        $request['sortBy'] = $request['sortBy'] ?? 'created_at';
        $request['orderBy'] = $request['orderBy'] ?? 'desc';
        $request['perPage'] = $request['perPage'] ?? '10';

        $validator = Validator::make($request->all(), [
            'sortBy' => [
                'required',
                Rule::in(['company_name', 'active', 'created_at'])
            ],
            'orderBy' => [
                'required',
                Rule::in(['asc', 'desc'])
            ],
            'perPage' => 'numeric|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('404');
        }

        // Filters
        $company_name = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['company_name']  ?? null : null;
        $show_deleted = isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['show_deleted']) ? ($request['tableFilterOptions']['show_deleted'] === 'false' ? false : true) : null;

        $companies = Company::query();
        if (!empty($company_name)) {
            $companies->where(function ($q) use ($company_name) {
                $q->where('company_name', 'like', '%' . $company_name . '%');
            });
        }
        if ($show_deleted) {
            $companies->onlyTrashed();
        }

        return Inertia::render('Admin/Infrastructure/Companies/Overview', ['sortBy' => $request['sortBy'], 'orderBy' => $request['orderBy'], 'paginatedResults' => $companies->orderBy($request['sortBy'], $request['orderBy'])->paginate($request['perPage']), 'tableFilterOptions' => ['company_name' => $company_name, 'show_deleted' => $show_deleted]]);
    }

    public function view(Request $request)
    {
        $id = intval($request['id']);
        if ($id === 0) {
            return redirect()->route('404');
        }
        $company = Company::whereId($id)->first();

        if (!isset($company)) {
            return Inertia::render('Admin/Infrastructure/Companies/Overview')
                ->with('show', true)
                ->with('status', 'error')
                ->with('message', 'An error occurred.');
        }

        return Inertia::render('Admin/Infrastructure/Companies/Overview', ['viewCompany' => $company]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|max:255',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'company_photo' => 'nullable|image',
        ]);
        if (isset($request['company_photo'])) {
            $path = $request->file('company_photo')->store('company-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $company = Company::create($request->all());
            DB::commit();

            return redirect()->route('admin/infrastructure/companies')
                ->with('show', true)
                ->with('status', 'success')
                ->with('message', 'Company created successfully.')
                ->with('route', 'admin/infrastructure/companies/edit')
                ->with('id', $company->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', [
                'errorMessage' => 'Failed to create company: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Request $request)
    {
        $id = intval($request['id']);
        if ($id === 0) {
            return redirect()->route('404');
        }
        $company = Company::whereId($id)->first();

        return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', ['company' => $company]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'company_name' => 'required|max:255',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'company_photo' => 'nullable|image',
        ]);

        $company = Company::find($request['id']);
        if (isset($company['img_path'])) {
            $isDeleted = $this->CommonController->deletePhoto($company['img_path']);
            if ($isDeleted) {
                $request['img_path'] = null;
            }
        }
        if (!empty($request['company_photo'])) {
            $path = $request->file('company_photo')->store('company-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $company->update($request->all());
            DB::commit();

            return redirect()->route('admin/infrastructure/companies')
                ->with('show', true)
                ->with('status', 'success')
                ->with('message', 'Company updated successfully.')
                ->with('route', 'admin/infrastructure/companies/edit')
                ->with('id', $company->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', [
                'errorMessage' => 'Failed to update company: ' . $e->getMessage(),
            ]);
        }
    }

    public function deletePhoto(Request $request)
    {
        $company = Company::find($request['id']);

        if (isset($company)) {
            $isDeleted = $this->CommonController->deletePhoto($request['img_path']);
            if ($isDeleted || !$isDeleted && !empty($company->img_path)) {
                try {
                    DB::beginTransaction();
                    $company->update(['img_path' => null]);
                    DB::commit();

                    return redirect()->back()
                        ->with('show', true)
                        ->with('status', 'success')
                        ->with('message', 'Photo removed successfully.');
                } catch (\Exception $e) {
                    DB::rollBack();

                    return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', [
                        'errorMessage' => 'Failed to update company: ' . $e->getMessage(),
                    ]);
                }
            }
        }

        return redirect()->back()
            ->with('show', true)
            ->with('status', 'error')
            ->with('message', 'No image was deleted or company was not found.');
    }

    public function exportCsv(Request $request)
    {
        // Define the CSV header
        $header = [
            'company_name',
            'address_1',
            'address_2',
            'email',
            'phone_number',
            'mobile_number',
            'website',
            'img_path',
            'img_url',
            'active'
        ];

        // Open a memory "file" for write
        $file = fopen('php://memory', 'w');

        // Insert the CSV header
        fputcsv($file, $header);

        if ($request['with_data']) {
            // Fetch companies
            $companies = Company::all();

            // Insert the companies data
            foreach ($companies as $company) {
                $row = [
                    $company->company_name,
                    $company->address_1,
                    $company->address_2,
                    $company->email,
                    $company->phone_number,
                    $company->mobile_number,
                    $company->website,
                    $company->img_path,
                    $company->img_url,
                    $company->active
                ];
                fputcsv($file, $row);
            }
        }

        // Reset the file pointer to the start of the file
        fseek($file, 0);

        // Return a CSV file for download
        return response()->streamDownload(function () use ($file) {
            fpassthru($file);
        }, 'companies.csv');
    }
}
