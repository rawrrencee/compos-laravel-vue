<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SupplierController extends Controller
{
    protected $CommonController;

    public function __construct(CommonController $CommonController)
    {
        $this->CommonController = $CommonController;
    }

    public function index(Request $request)
    {
        $request['sortBy'] = $request['sortBy'] ?? 'supplier_name';
        $request['orderBy'] = $request['orderBy'] ?? 'asc';
        $request['perPage'] = $request['perPage'] ?? '10';

        $validator = Validator::make($request->all(), [
            'sortBy' => [
                'required',
                Rule::in(['supplier_name', 'active', 'created_at'])
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
        $supplier_name = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['supplier_name']  ?? null : null;

        $suppliers = Supplier::query();
        if (!empty($supplier_name)) {
            $suppliers->where(function ($q) use ($supplier_name) {
                $q->where('supplier_name', 'like', '%' . $supplier_name . '%');
            });
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showDeleted'])) {
            switch ($request['tableFilterOptions']['showDeleted']) {
                case 'onlyDeleted':
                    $suppliers->onlyTrashed();
                    break;
                case 'both':
                    $suppliers->withTrashed();
                    break;
                case 'onlyNonDeleted':
                default:
                    break;
            }
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showActive'])) {
            switch ($request['tableFilterOptions']['showActive']) {
                case 'onlyActive':
                    $suppliers->where('active', '=', '1');
                    break;
                case 'onlyNonActive':
                    $suppliers->where('active', '=', '0');
                    break;
                case 'both':
                default:
                    break;
            }
        }

        if ($request['sortBy'] !== 'active') {
            $suppliers->orderBy('active', 'desc');
        }

        return Inertia::render('Admin/Infrastructure/Suppliers/Overview', [
            'sortBy' => $request['sortBy'],
            'orderBy' => $request['orderBy'],
            'paginatedResults' =>
            $suppliers
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
        $supplier = Supplier::withTrashed()->where('id', '=', $id)->first();

        if (!isset($supplier)) {
            return redirect()->route('infrastructure.suppliers.viewLandingPage');
        }

        return Inertia::render('Admin/Infrastructure/Suppliers/ViewSupplier', ['viewSupplier' => $supplier]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|max:255',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'supplier_photo' => 'nullable|image',
        ]);

        if (!empty($request['supplier_photo'])) {
            $path = $request->file('supplier_photo')->store('supplier-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $supplier = Supplier::create($request->all());
            DB::commit();

            return redirect()->route('infrastructure.suppliers.viewLandingPage')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Supplier created successfully.')
                ->with('route', 'infrastructure.suppliers.viewEditPageById')
                ->with('id', $supplier->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Infrastructure/Suppliers/AddOrEditSupplier', [
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
        $supplier = Supplier::whereId($id)->first();

        if (!isset($supplier)) {
            return redirect()->route('404');
        }

        return Inertia::render('Admin/Infrastructure/Suppliers/AddOrEditSupplier', ['supplier' => $supplier]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'supplier_name' => 'required|max:255',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'supplier_photo' => 'nullable|image',
        ]);

        $supplier = Supplier::find($request['id']);

        if (!isset($supplier)) {
            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'Supplier to be updated was not found.');
        }

        if (isset($supplier['img_path'])) {
            $isDeleted = $this->CommonController->deletePhoto($supplier['img_path']);
            if ($isDeleted) {
                $request['img_path'] = null;
            }
        }
        if (!empty($request['supplier_photo'])) {
            $path = $request->file('supplier_photo')->store('supplier-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $supplier->update($request->all());
            DB::commit();

            return redirect()->route('infrastructure.suppliers.viewLandingPage')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Supplier updated successfully.')
                ->with('route', 'infrastructure.suppliers.viewEditPageById')
                ->with('id', $supplier->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->CommonController->handleException($e);
        }
    }

    public function bulkUpdate(Request $request)
    {
        try {
            // Extract suppliers data from the request
            $suppliers = $request->all();

            // Start a DB transaction
            DB::beginTransaction();

            // Loop through each supplier data
            foreach ($suppliers as $supplierData) {
                // Validate the data
                $validator = Validator::make($supplierData, [
                    'id' => 'required|exists:suppliers,id',
                    'supplier_name' => 'required|max:255',
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

                // If validation passes, locate the supplier and update
                $supplier = Supplier::find($supplierData['id']);
                $supplier->update($supplierData);
            }

            // If all updates are successful, commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', count($suppliers) . ' suppliers bulk edited successfully.');
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
        $supplier = Supplier::find($request['id']);

        if (isset($supplier)) {
            $isDeleted = $this->CommonController->deletePhoto($request['img_path']);
            if ($isDeleted || !$isDeleted && !empty($supplier->img_path)) {
                try {
                    DB::beginTransaction();
                    $supplier->update(['img_path' => null]);
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
            ->with('message', 'No image was deleted or supplier was not found.');
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

        try {
            DB::beginTransaction();

            foreach ($data as $row) {
                // Validate the row
                Validator::make($row, [
                    'supplier_name' => 'required|string|max:255',
                    'address_1' => 'nullable|max:255',
                    'address_2' => 'nullable|max:255',
                    'email' => 'nullable|email|max:255',
                    'phone_number' => 'nullable|max:255',
                    'mobile_number' => 'nullable|max:255',
                    'website' => 'nullable|url|max:255',
                    'img_url' => 'nullable|url|max:255',
                    'active' => 'required|in:0,1',
                ])->validate();

                // Create the supplier
                Supplier::create([
                    'supplier_name' => $row['supplier_name'],
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
                ->with('message', count($data) . ' suppliers imported successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of errors
            DB::rollBack();

            $this->CommonController->handleException($e, 'dialog');
        }
    }


    public function exportCsv(Request $request)
    {
        // Define the CSV header
        $header = [
            'supplier_name',
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
            // Fetch suppliers
            $suppliers = Supplier::withTrashed()->get();

            // Insert the suppliers data
            foreach ($suppliers as $supplier) {
                $row = [
                    $supplier->id,
                    $supplier->supplier_name,
                    $supplier->address_1,
                    $supplier->address_2,
                    $supplier->email,
                    $supplier->phone_number,
                    $supplier->mobile_number,
                    $supplier->website,
                    $supplier->img_url,
                    $supplier->active,
                    $supplier->created_at,
                    $supplier->updated_at,
                    $supplier->deleted_at,
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
        }, 'suppliers.csv');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:suppliers,id',
        ]);

        // Retrieve the ids
        $ids = $request->get('ids');

        DB::beginTransaction();

        try {
            // Delete the records
            $deletedCount = Supplier::destroy($ids);

            DB::commit();

            $context = 'suppliers';
            if ($deletedCount == 1) $context = 'supplier';

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
}
