<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class StoreController extends Controller
{
    protected $CommonController;
    protected $CompanyController;

    public function __construct(CommonController $CommonController, CompanyController $CompanyController)
    {
        $this->CommonController = $CommonController;
        $this->CompanyController = $CompanyController;
    }

    public function index(Request $request)
    {
        $request['sortBy'] = $request['sortBy'] ?? 'store_name';
        $request['orderBy'] = $request['orderBy'] ?? 'asc';
        $request['perPage'] = $request['perPage'] ?? '10';

        $validator = Validator::make($request->all(), [
            'sortBy' => [
                'required',
                Rule::in(['store_name', 'active', 'created_at'])
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
        $store_name_or_code = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['store_name_or_code']  ?? null : null;

        $stores = Store::query();
        if (!empty($store_name_or_code)) {
            $stores->where(function ($q) use ($store_name_or_code) {
                $q->where('store_name', 'like', '%' . $store_name_or_code . '%')
                    ->orWhere('store_code', 'like', '%' . $store_name_or_code . '%');
            });
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showDeleted'])) {
            switch ($request['tableFilterOptions']['showDeleted']) {
                case 'onlyDeleted':
                    $stores->onlyTrashed();
                    break;
                case 'both':
                    $stores->withTrashed();
                    break;
                case 'onlyNonDeleted':
                default:
                    break;
            }
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showActive'])) {
            switch ($request['tableFilterOptions']['showActive']) {
                case 'onlyActive':
                    $stores->where('active', '=', '1');
                    break;
                case 'onlyNonActive':
                    $stores->where('active', '=', '0');
                    break;
                case 'both':
                default:
                    break;
            }
        }

        if ($request['sortBy'] !== 'active') {
            $stores->orderBy('active', 'desc');
        }

        return Inertia::render('Admin/Infrastructure/Stores/Overview', [
            'sortBy' => $request['sortBy'],
            'orderBy' => $request['orderBy'],
            'paginatedResults' =>
            $stores
                ->with(['company' => function ($query) {
                    $query->select('id', 'company_name');
                }])
                ->orderBy($request['sortBy'], $request['orderBy'])
                ->paginate($request['perPage']),
            'tableFilterOptions' => $request['tableFilterOptions'],
            'companies' => $this->CompanyController->getValidCompanies()
        ]);
    }

    public function add()
    {
        return
            Inertia::render('Admin/Infrastructure/Stores/AddOrEditStore', ['companies' => $this->CompanyController->getValidCompanies()]);
    }

    public function view(Request $request)
    {
        $id = intval($request['id']);
        if ($id === 0) {
            return redirect()->route('404');
        }
        $store = Store::withTrashed()
            ->where('id', '=', $id)
            ->with(['company' => function ($query) {
                $query->select('id', 'company_name');
            }])
            ->first();

        if (!isset($store)) {
            return redirect()->route('stores.viewLandingPage');
        }

        return Inertia::render('Admin/Infrastructure/Stores/ViewStore', ['viewStore' => $store]);
    }

    public function store(Request $request)
    {
        $this->getValidatorForCreate($request)->validate();

        if (!empty($request['store_photo'])) {
            $path = $request->file('store_photo')->store('store-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $store = Store::create($request->all());
            DB::commit();

            return redirect()->route('stores.viewLandingPage')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Store created successfully.')
                ->with('route', 'stores.viewEditPageById')
                ->with('id', $store->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Infrastructure/Stores/AddOrEditStore', [
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
        $store = Store::whereId($id)->first();

        if (!isset($store)) {
            return redirect()->route('404');
        }

        return Inertia::render('Admin/Infrastructure/Stores/AddOrEditStore', ['store' => $store, 'companies' => $this->CompanyController->getValidCompanies()]);
    }

    public function update(Request $request)
    {
        $store = Store::find($request['id']);

        if (!isset($store)) {
            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'Store to be updated was not found.');
        }

        $validator = $this->getValidatorForEdit($request, $store);
        $validator->validate();

        if (isset($store['img_path'])) {
            $isDeleted = $this->CommonController->deletePhoto($store['img_path']);
            if ($isDeleted) {
                $request['img_path'] = null;
            }
        }
        if (!empty($request['store_photo'])) {
            $path = $request->file('store_photo')->store('store-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $store->update($request->all());
            DB::commit();

            return redirect()->route('stores.viewLandingPage')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Store updated successfully.')
                ->with('route', 'stores.viewEditPageById')
                ->with('id', $store->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->CommonController->handleException($e);
        }
    }

    public function bulkUpdate(Request $request)
    {
        try {
            // Start a DB transaction
            DB::beginTransaction();

            // Extract stores data from the request
            $storesInput = $request->all();
            $ids = array_map(function ($store) {
                return $store['id'];
            }, $storesInput);
            $storesFromDatabase = $this->getStoresByIds($ids);

            // Loop through each store data
            foreach ($storesInput as $storeData) {

                $storeToUpdate = null;
                foreach ($storesFromDatabase as $store) {
                    if ($store->id == $storeData['id']) {
                        $storeToUpdate = $store;
                    }
                }

                // Validate the data
                $validator = $this->getValidatorForEdit($storeData, $storeToUpdate);

                // If validation fails, stop and return error
                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                // If validation passes, locate the store and update
                $store = Store::find($storeData['id']);
                $store->update($storeData);
            }

            // If all updates are successful, commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', count($storesInput) . ' stores bulk edited successfully.');
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
        $store = Store::find($request['id']);

        if (isset($store)) {
            $isDeleted = $this->CommonController->deletePhoto($request['img_path']);
            if ($isDeleted || !$isDeleted && !empty($store->img_path)) {
                try {
                    DB::beginTransaction();
                    $store->update(['img_path' => null]);
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
            ->with('message', 'No image was deleted or store was not found.');
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
                $this->getValidatorForCreate($row)->validate();

                // Create the store
                Store::create([
                    'store_name' => $row['store_name'],
                    'store_code' => $row['store_code'],
                    'company_id' => $row['company_id'],
                    'address_1' => empty($row['address_1']) ? null : $row['address_1'],
                    'address_2' => empty($row['address_2']) ? null : $row['address_2'],
                    'email' => empty($row['email']) ? null : $row['email'],
                    'phone_number' => empty($row['phone_number']) ? null : $row['phone_number'],
                    'mobile_number' => empty($row['mobile_number']) ? null : $row['mobile_number'],
                    'website' => empty($row['website']) ? null : $row['website'],
                    'active' => $row['active'],
                    'include_tax' => $row['include_tax'],
                    'tax_percentage' => empty($row['tax_percentage']) ? null : $row['tax_percentage'],
                    'img_url' => empty($row['img_url']) ? null : $row['img_url'],
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'dialog')
                ->with('status', 'success')
                ->with('message', count($data) . ' stores imported successfully.');
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
            'store_name',
            'store_code',
            'company_id',
            'address_1',
            'address_2',
            'email',
            'phone_number',
            'mobile_number',
            'website',
            'img_url',
            'include_tax',
            'tax_percentage',
            'active'
        ];

        if ($request['with_data']) {
            array_splice($header, 3, 0, 'company_name');
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
            // Fetch stores with related company
            $stores = Store::withTrashed()->with(['company' => function ($query) {
                $query->select('id', 'company_name');
            }])->get();

            // Insert the stores data
            foreach ($stores as $store) {
                $row = [
                    $store->id,
                    $store->store_name,
                    $store->store_code,
                    $store->company->id,
                    $store->company->company_name,
                    $store->address_1,
                    $store->address_2,
                    $store->email,
                    $store->phone_number,
                    $store->mobile_number,
                    $store->website,
                    $store->img_url,
                    $store->include_tax,
                    $store->tax_percentage,
                    $store->active,
                    $store->created_at,
                    $store->updated_at,
                    $store->deleted_at,
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
        }, 'stores.csv');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:stores,id',
        ]);

        // Retrieve the ids
        $ids = $request->get('ids');

        DB::beginTransaction();

        try {
            // Delete the records
            $deletedCount = Store::destroy($ids);

            DB::commit();

            $context = 'stores';
            if ($deletedCount == 1) $context = 'store';

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

    public function getStoresByIds(array $ids)
    {
        return Store::whereIn('id', $ids)->get();
    }

    private function getValidatorForCreate($request)
    {
        if ($request instanceof Request) {
            $requestData = $request->all();
        } elseif (is_array($request)) {
            $requestData = $request;
        } else {
            return null;
        }

        return Validator::make($requestData, [
            'store_name' => 'required|max:255',
            'store_code' => 'required|max:4|unique:stores',
            'company_id' => 'required|exists:companies,id',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'include_tax' => 'required|boolean',
            'tax_percentage' => 'required|numeric|between:0,100',
            'store_photo' => 'nullable|image',
        ]);
    }

    private function getValidatorForEdit($request, Store $store)
    {
        if ($request instanceof Request) {
            $requestData = $request->all();
        } elseif (is_array($request)) {
            $requestData = $request;
        } else {
            return null;
        }

        return Validator::make($requestData, [
            'id' => 'required',
            'store_name' => 'required|max:255',
            'store_code' => [
                'required',
                'max:4',
                Rule::unique('stores')->ignore($store->id),
            ],
            'company_id' => 'required|exists:companies,id',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'include_tax' => 'required|boolean',
            'tax_percentage' => 'required|numeric|between:0,100',
            'store_photo' => 'nullable|image',
        ]);
    }
}
