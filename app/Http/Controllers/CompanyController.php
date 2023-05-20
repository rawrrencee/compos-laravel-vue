<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
            'perPage' => 'numeric|max:2000',
            'tableFilterOptions.show_deleted' => [
                'nullable',
                Rule::in(['only_non_deleted', 'only_deleted', 'both'])
            ],
            'tableFilterOptions.show_active' => [
                'nullable',
                Rule::in(['only_non_active', 'only_active', 'both'])
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->route('404');
        }

        // Filters
        $company_name = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['company_name']  ?? null : null;

        $companies = Company::query();
        if (!empty($company_name)) {
            $companies->where(function ($q) use ($company_name) {
                $q->where('company_name', 'like', '%' . $company_name . '%');
            });
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['show_deleted'])) {
            switch ($request['tableFilterOptions']['show_deleted']) {
                case 'only_deleted':
                    $companies->onlyTrashed();
                    break;
                case 'both':
                    $companies->withTrashed();
                    break;
                case 'only_non_deleted':
                default:
                    break;
            }
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['show_active'])) {
            switch ($request['tableFilterOptions']['show_active']) {
                case 'only_active':
                    $companies->where('active', '=', '1');
                    break;
                case 'only_non_active':
                    $companies->where('active', '=', '0');
                    break;
                case 'both':
                default:
                    break;
            }
        }

        return Inertia::render('Admin/Infrastructure/Companies/Overview', ['sortBy' => $request['sortBy'], 'orderBy' => $request['orderBy'], 'paginatedResults' => $companies->orderBy($request['sortBy'], $request['orderBy'])->paginate($request['perPage']), 'tableFilterOptions' => $request['tableFilterOptions']]);
    }

    public function view(Request $request)
    {
        $id = intval($request['id']);
        if ($id === 0) {
            return redirect()->route('404');
        }
        $company = Company::withTrashed()->where('id', '=', $id)->first();

        if (!isset($company)) {
            return Inertia::render('Admin/Infrastructure/Companies/Overview')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'An error occurred.');
        }

        return Inertia::render('Admin/Infrastructure/Companies/ViewCompany', ['viewCompany' => $company]);
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

        if (!empty($request['company_photo'])) {
            $path = $request->file('company_photo')->store('company-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $company = Company::create($request->all());
            DB::commit();

            return redirect()->route('admin/infrastructure/companies')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Company created successfully.')
                ->with('route', 'admin/infrastructure/companies/edit')
                ->with('id', $company->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', [
                'errorMessage' => 'Failed to create record: ' . $e->getMessage(),
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

        if (!isset($company)) {
            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'Company to be updated was not found.');
        }

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
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Company updated successfully.')
                ->with('route', 'admin/infrastructure/companies/edit')
                ->with('id', $company->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Failed to update record: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        try {
            // Extract companies data from the request
            $companies = $request->all();

            // Start a DB transaction
            DB::beginTransaction();

            // Loop through each company data
            foreach ($companies as $companyData) {
                // Validate the data
                $validator = Validator::make($companyData, [
                    'id' => 'required|exists:companies,id',
                    'company_name' => 'required|max:255',
                    'address_1' => 'nullable|max:255',
                    'address_2' => 'nullable|max:255',
                    'phone_number' => 'nullable|max:255',
                    'mobile_number' => 'nullable|max:255',
                    'email' => 'nullable|email',
                    'website' => 'nullable|url',
                    'img_url' => 'nullable|url',
                    'active' => 'required|boolean',
                ]);

                // If validation fails, stop and return error
                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                // If validation passes, locate the company and update
                $company = Company::find($companyData['id']);
                $company->update($companyData);
            }

            // If all updates are successful, commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', count($companies) . ' companies bulk edited successfully.');
        } catch (\Exception $e) {
            // If anything goes wrong, rollback the transaction
            DB::rollback();

            // Then return the error
            return redirect()->back()
                ->with('show', true)
                ->with('type', 'dialog')
                ->with('status', 'error')
                ->with('message', 'Failed to bulk edit records: ' . $e->getMessage());
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
                        ->with('type', 'default')
                        ->with('status', 'success')
                        ->with('message', 'Photo removed successfully.');
                } catch (\Exception $e) {
                    DB::rollBack();

                    return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany')
                        ->with('show', true)
                        ->with('type', 'default')
                        ->with('status', 'success')
                        ->with('message', 'Failed to update record: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()
            ->with('show', true)
            ->with('type', 'default')
            ->with('status', 'error')
            ->with('message', 'No image was deleted or company was not found.');
    }

    public function importCsv(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'import_file' => 'required|mimes:csv,txt'
        ]);

        // Read the CSV file
        $path = $request->file('import_file')->getRealPath();
        $file = fopen($path, 'r');

        // Get the header row
        $header = fgetcsv($file);

        // Read the rest of the data
        $data = [];
        while (($row = fgetcsv($file)) !== false) {
            $data[] = array_combine($header, $row);
        }

        fclose($file);

        if (count($data) == 0) {
            return redirect()->back()
                ->with('show', true)
                ->with('type', 'dialog')
                ->with('status', 'error')
                ->with('message', 'Unable to import data as none was provided.');
        }

        // Begin a database transaction
        DB::beginTransaction();

        try {
            foreach ($data as $row) {
                // Validate the row
                Validator::make($row, [
                    'company_name' => 'required|max:255',
                    'address_1' => 'nullable|max:255',
                    'address_2' => 'nullable|max:255',
                    'email' => 'nullable|email|max:255',
                    'phone_number' => 'nullable|max:255',
                    'mobile_number' => 'nullable|max:255',
                    'website' => 'nullable|url|max:255',
                    'img_url' => 'nullable|url|max:255',
                    'active' => 'required|in:0,1',
                ])->validate();

                // Create the company
                Company::create([
                    'company_name' => $row['company_name'],
                    'address_1' => empty($row['address_1']) ? null : $row['address_1'],
                    'address_2' => empty($row['address_2']) ? null : $row['address_2'],
                    'email' => empty($row['email']) ? null : $row['email'],
                    'phone_number' => empty($row['phone_number']) ? null : $row['phone_number'],
                    'mobile_number' => empty($row['mobile_number']) ? null : $row['mobile_number'],
                    'website' => empty($row['website']) ? null : $row['website'],
                    'img_url' => empty($row['img_url']) ? null : $row['img_url'],
                    'active' => $row['active'],
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'dialog')
                ->with('status', 'success')
                ->with('message', count($data) . ' companies imported successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of errors
            DB::rollBack();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'dialog')
                ->with('status', 'error')
                ->with('message', 'Failed to update record: ' . $e->getMessage());
        }
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
            'img_url',
            'active'
        ];

        if ($request['with_data']) {
            array_unshift($header, 'id');
            array_push($header, 'created_at', 'updated_at', 'deleted_at');
        }

        // Open a memory "file" for write
        $file = fopen('php://memory', 'w');

        // Insert the CSV header
        fputcsv($file, $header);

        if ($request['with_data']) {
            // Fetch companies
            $companies = Company::withTrashed()->get();

            // Insert the companies data
            foreach ($companies as $company) {
                $row = [
                    $company->id,
                    $company->company_name,
                    $company->address_1,
                    $company->address_2,
                    $company->email,
                    $company->phone_number,
                    $company->mobile_number,
                    $company->website,
                    $company->img_url,
                    $company->active,
                    $company->created_at,
                    $company->updated_at,
                    $company->deleted_at,
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

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:companies,id',
        ]);

        // Retrieve the ids
        $ids = $request->get('ids');

        DB::beginTransaction();

        try {
            // Delete the records
            $deletedCount = Company::destroy($ids);

            DB::commit();

            $context = 'companies';
            if ($deletedCount == 1) $context = 'company';

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', $deletedCount . ' ' . $context . ' deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'Failed to delete record: ' . $e->getMessage());
        }
    }
}
