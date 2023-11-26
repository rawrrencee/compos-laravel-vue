<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class BrandController extends Controller
{
    protected $CommonController;

    public function __construct(CommonController $CommonController)
    {
        $this->CommonController = $CommonController;
    }

    public function index(Request $request)
    {
        $request['sortBy'] = $request['sortBy'] ?? 'brand_name';
        $request['orderBy'] = $request['orderBy'] ?? 'asc';
        $request['perPage'] = $request['perPage'] ?? '10';

        $validator = Validator::make($request->all(), [
            'sortBy' => [
                'required',
                Rule::in(['brand_name', 'brand_code', 'active', 'created_at'])
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
        $brand_name = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['brand_name']  ?? null : null;
        $brand_code = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['brand_code']  ?? null : null;

        $brands = Brand::query();
        if (!empty($brand_name)) {
            $brands->where(function ($q) use ($brand_name) {
                $q->where('brand_name', 'like', '%' . $brand_name . '%');
            });
        }
        if (!empty($brand_code)) {
            $brands->where(function ($q) use ($brand_code) {
                $q->where('brand_code', 'like', '%' . $brand_code . '%');
            });
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showDeleted'])) {
            switch ($request['tableFilterOptions']['showDeleted']) {
                case 'onlyDeleted':
                    $brands->onlyTrashed();
                    break;
                case 'both':
                    $brands->withTrashed();
                    break;
                case 'onlyNonDeleted':
                default:
                    break;
            }
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showActive'])) {
            switch ($request['tableFilterOptions']['showActive']) {
                case 'onlyActive':
                    $brands->where('active', '=', '1');
                    break;
                case 'onlyNonActive':
                    $brands->where('active', '=', '0');
                    break;
                case 'both':
                default:
                    break;
            }
        }

        if ($request['sortBy'] !== 'active') {
            $brands->orderBy('active', 'desc');
        }

        return Inertia::render('Admin/Commerce/Brands/Overview', [
            'sortBy' => $request['sortBy'],
            'orderBy' => $request['orderBy'],
            'paginatedResults' =>
            $brands
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
        $brand = Brand::withTrashed()->where('id', '=', $id)->first();

        if (!isset($brand)) {
            return redirect()->route('admin/commerce/brands');
        }

        return Inertia::render('Admin/Commerce/Brands/ViewBrand', ['viewBrand' => $brand]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|max:255',
            'brand_code' => 'required|max:4|unique:brands',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'brand_photo' => 'nullable|image',
        ]);

        if (!empty($request['brand_photo'])) {
            $path = $request->file('brand_photo')->store('brand-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $brand = Brand::create($request->all());
            DB::commit();

            return redirect()->route('admin/commerce/brands')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Brand created successfully.')
                ->with('route', 'admin/commerce/brands/edit')
                ->with('id', $brand->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Commerce/Brands/AddOrEditBrand', [
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
        $brand = Brand::whereId($id)->first();

        if (!isset($brand)) {
            return redirect()->route('404');
        }

        return Inertia::render('Admin/Commerce/Brands/AddOrEditBrand', ['brand' => $brand]);
    }

    public function update(Request $request)
    {
        $brand = Brand::find($request['id']);

        if (!isset($brand)) {
            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'Brand to be updated was not found.');
        }

        $request->validate([
            'id' => 'required',
            'brand_name' => 'required|max:255',
            'brand_code' => [
                'required',
                'max:4',
                Rule::unique('brands')->ignore($brand->id),
            ],
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'brand_photo' => 'nullable|image',
        ]);

        if (isset($brand['img_path'])) {
            $isDeleted = $this->CommonController->deletePhoto($brand['img_path']);
            if ($isDeleted) {
                $request['img_path'] = null;
            }
        }

        if (!empty($request['brand_photo'])) {
            $path = $request->file('brand_photo')->store('brand-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $brand->update($request->all());
            DB::commit();

            return redirect()->route('admin/commerce/brands')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Brand updated successfully.')
                ->with('route', 'admin/commerce/brands/edit')
                ->with('id', $brand->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->CommonController->handleException($e);
        }
    }

    public function bulkUpdate(Request $request)
    {
        try {
            // Extract brands data from the request
            $brands = $request->all();

            // Start a DB transaction
            DB::beginTransaction();

            // Loop through each brand data
            foreach ($brands as $brandData) {
                // If validation passes, locate the brand and update
                $brand = Brand::find($brandData['id']);

                // Validate the data
                $validator = Validator::make($brandData, [
                    'id' => 'required|exists:brands,id',
                    'brand_name' => 'required|max:255',
                    'brand_code' => [
                        'required',
                        'max:4',
                        Rule::unique('brands')->ignore($brand->id),
                    ],
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

                $brand->update($brandData);
            }

            // If all updates are successful, commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', count($brands) . ' brands bulk edited successfully.');
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
        $brand = Brand::find($request['id']);

        if (isset($brand)) {
            $isDeleted = $this->CommonController->deletePhoto($request['img_path']);
            if ($isDeleted || !$isDeleted && !empty($brand->img_path)) {
                try {
                    DB::beginTransaction();
                    $brand->update(['img_path' => null]);
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
            ->with('message', 'No image was deleted or brand was not found.');
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
                    'brand_name' => 'required|string|max:255',
                    'brand_code' => 'required|max:4|unique:brands',
                    'address_1' => 'nullable|max:255',
                    'address_2' => 'nullable|max:255',
                    'email' => 'nullable|email|max:255',
                    'phone_number' => 'nullable|max:255',
                    'mobile_number' => 'nullable|max:255',
                    'website' => 'nullable|url|max:255',
                    'img_url' => 'nullable|url|max:255',
                    'active' => 'required|in:0,1',
                ])->validate();

                // Create the brand
                Brand::create([
                    'brand_name' => $row['brand_name'],
                    'brand_code' => $row['brand_code'],
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
                ->with('message', count($data) . ' brands imported successfully.');
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
            'brand_name',
            'brand_code',
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
            // Fetch brands
            $brands = Brand::withTrashed()->get();

            // Insert the brands data
            foreach ($brands as $brand) {
                $row = [
                    $brand->id,
                    $brand->brand_name,
                    $brand->brand_code,
                    $brand->address_1,
                    $brand->address_2,
                    $brand->email,
                    $brand->phone_number,
                    $brand->mobile_number,
                    $brand->website,
                    $brand->img_url,
                    $brand->active,
                    $brand->created_at,
                    $brand->updated_at,
                    $brand->deleted_at,
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
        }, 'brands.csv');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:brands,id',
        ]);

        // Retrieve the ids
        $ids = $request->get('ids');

        DB::beginTransaction();

        try {
            // Delete the records
            $deletedCount = Brand::destroy($ids);

            DB::commit();

            $context = 'brands';
            if ($deletedCount == 1) $context = 'brand';

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
