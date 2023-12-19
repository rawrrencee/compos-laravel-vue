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
    protected $HardcodedDataController;

    public function __construct(CommonController $CommonController, HardcodedDataController $HardcodedDataController)
    {
        $this->CommonController = $CommonController;
        $this->HardcodedDataController = $HardcodedDataController;
    }

    public function index(Request $request)
    {
        $request['sortBy'] = $request['sortBy'] ?? 'company_name';
        $request['orderBy'] = $request['orderBy'] ?? 'asc';
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
            'tableFilterOptions.showDeleted' => [
                'nullable',
                Rule::in(['onlyNonDeleted', 'onlyDeleted', 'both'])
            ],
            'tableFilterOptions.showActive' => [
                'nullable',
                Rule::in(['onlyNonActive', 'onlyActive', 'both'])
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

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showDeleted'])) {
            switch ($request['tableFilterOptions']['showDeleted']) {
                case 'onlyDeleted':
                    $companies->onlyTrashed();
                    break;
                case 'both':
                    $companies->withTrashed();
                    break;
                case 'onlyNonDeleted':
                default:
                    break;
            }
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showActive'])) {
            switch ($request['tableFilterOptions']['showActive']) {
                case 'onlyActive':
                    $companies->where('active', '=', '1');
                    break;
                case 'onlyNonActive':
                    $companies->where('active', '=', '0');
                    break;
                case 'both':
                default:
                    break;
            }
        }

        if ($request['sortBy'] !== 'active') {
            $companies->orderBy('active', 'desc');
        }

        return Inertia::render('Admin/Infrastructure/Companies/Overview', [
            'sortBy' => $request['sortBy'],
            'orderBy' => $request['orderBy'],
            'paginatedResults' =>
            $companies
                ->orderBy($request['sortBy'], $request['orderBy'])
                ->paginate($request['perPage']),
            'tableFilterOptions' => $request['tableFilterOptions']
        ]);
    }

    public function view(Request $request)
    {
        $id = intval($request['id']);
        if ($id === 0) {
            return redirect()->route('404');
        }
        $company = Company::withTrashed()
            ->where('id', $id)
            ->with(['stores' => function ($query) {
                $query
                    ->withoutTrashed()
                    ->select('id', 'company_id', 'store_name', 'store_code')
                    ->orderBy('store_name');
            }])
            ->firstOrFail();

        if (!isset($company)) {
            return redirect()->route('infrastructure.companies.viewLandingPage');
        }

        $company['identity_type'] = $this->CommonController->findValueByKey($this->HardcodedDataController->getCompanyIdentityTypes(), $company['identity_type']);
        $currencyWithSymbol = null;
        $currencies = $this->HardcodedDataController->getCurrencies();
        $currencyLabel = $this->CommonController->findValueByKey($currencies, $company['currency']);

        if (isset($currencyLabel)) {
            $symbol = $this->CommonController->findValueByKey($currencies, $company['currency'], 'key', 'symbol');
            if ($symbol) {
                $currencyWithSymbol = $currencyLabel . ' (' . html_entity_decode($symbol) . ')';
            }
        }

        $company['currency'] = $currencyWithSymbol;
        $company['country'] = $this->CommonController->findValueByKey($this->HardcodedDataController->getCountries(), $company['country'], 'alpha_3_code', 'en_short_name');

        return Inertia::render('Admin/Infrastructure/Companies/ViewCompany', ['viewCompany' => $company]);
    }

    public function viewCreatePage()
    {
        return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', [
            'countries' => $this->HardcodedDataController->getCountries(),
            'currencies' => $this->HardcodedDataController->getCurrencies(),
            'companyIdentityTypes' => $this->HardcodedDataController->getCompanyIdentityTypes(),
        ]);
    }

    public function store(Request $request)
    {
        $this->getCompanyValidator($request->all(), false)->validate();

        if (!empty($request['company_photo'])) {
            $path = $request->file('company_photo')->store('company-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $company = Company::create($request->all());
            DB::commit();

            return redirect()->route('infrastructure.companies.viewLandingPage')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Company created successfully.')
                ->with('route', 'infrastructure.companies.viewEditPageById')
                ->with('id', $company->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', [
                'errorMessage' => 'Failed to create record: ' . $this->CommonController->formatException($e),
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

        if (!isset($company)) {
            return redirect()->route('404');
        }

        return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany', [
            'company' => $company,
            'countries' => $this->HardcodedDataController->getCountries(),
            'currencies' => $this->HardcodedDataController->getCurrencies(),
            'companyIdentityTypes' => $this->HardcodedDataController->getCompanyIdentityTypes(),
        ]);
    }

    public function update(Request $request)
    {
        $this->getCompanyValidator($request->all(), true)->validate();

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

            return redirect()->route('infrastructure.companies.viewLandingPage')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Company updated successfully.')
                ->with('route', 'infrastructure.companies.viewEditPageById')
                ->with('id', $company->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->CommonController->handleException($e);
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
                $validator = $this->getCompanyValidator($companyData, true);

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
                ->with('message', 'Failed to bulk edit records: ' . $this->CommonController->formatException($e));
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

                    return $this->CommonController->handleException($e);
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
        $bom = pack('H*', 'EFBBBF'); // UTF-8 BOM

        if (strpos(fread($file, 3), $bom) !== 0) {
            // BOM not detected, rewind the pointer to the start of the file and read all contents
            rewind($file);
        }

        // BOM detected, continue reading the file starting from the 4th byte
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

        try {
            DB::beginTransaction();

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
                ->with('message', 'Failed to create record: ' . $this->CommonController->formatException($e));
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

        foreach ($header as &$item) {
            $item = mb_convert_encoding($item, 'UTF-8');
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

                // Convert each item in the row to UTF-8
                foreach ($row as &$item) {
                    $item = mb_convert_encoding($item, 'UTF-8');
                }

                fputcsv($file, $row);
            }
        }

        // Reset the file pointer to the start of the file
        fseek($file, 0);

        // Send BOM header for UTF-8 CSV
        echo "\xEF\xBB\xBF";

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

            return $this->CommonController->handleException($e, 'default', 'delete');
        }
    }

    public function getValidCompanies()
    {
        return Company::all();
    }

    public function getCompanyValidator(array $requestArray, bool $isEdit)
    {
        $rules = [
            'company_name' => 'required|max:255',
            'identity_type' => 'required|max:255',
            'identity_number' => 'required|max:255',
            'currency' => 'nullable|max:255',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'country' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'company_photo' => 'nullable|image',
        ];

        if ($isEdit) {
            $rules['id'] = 'required|exists:companies,id';
        }

        return Validator::make($requestArray, $rules);
    }
}
