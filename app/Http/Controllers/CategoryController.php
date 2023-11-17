<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CategoryController extends Controller
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
                Rule::in(['category_name', 'category_code', 'active', 'created_at'])
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
        $category_name = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['category_name']  ?? null : null;
        $category_code = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['category_code']  ?? null : null;

        $categories = Category::query();
        if (!empty($category_name)) {
            $categories->where(function ($q) use ($category_name) {
                $q->where('category_name', 'like', '%' . $category_name . '%');
            });
        }
        if (!empty($category_code)) {
            $categories->where(function ($q) use ($category_code) {
                $q->where('category_code', 'like', '%' . $category_code . '%');
            });
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showDeleted'])) {
            switch ($request['tableFilterOptions']['showDeleted']) {
                case 'onlyDeleted':
                    $categories->onlyTrashed();
                    break;
                case 'both':
                    $categories->withTrashed();
                    break;
                case 'onlyNonDeleted':
                default:
                    break;
            }
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showActive'])) {
            switch ($request['tableFilterOptions']['showActive']) {
                case 'onlyActive':
                    $categories->where('active', '=', '1');
                    break;
                case 'onlyNonActive':
                    $categories->where('active', '=', '0');
                    break;
                case 'both':
                default:
                    break;
            }
        }

        return Inertia::render('Admin/Commerce/Categories/Overview', ['sortBy' => $request['sortBy'], 'orderBy' => $request['orderBy'], 'paginatedResults' => $categories->orderBy($request['sortBy'], $request['orderBy'])->paginate($request['perPage']), 'tableFilterOptions' => $request['tableFilterOptions']]);
    }

    public function view(Request $request)
    {
        $id = intval($request['id']);
        if ($id === 0) {
            return redirect()->route('404');
        }
        $category = Category::withTrashed()->where('id', '=', $id)->first();

        if (!isset($category)) {
            return Inertia::render('Admin/Commerce/Categories/Overview')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'An error occurred.');
        }

        return Inertia::render('Admin/Commerce/Categories/ViewCategory', ['viewCategory' => $category]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|max:255',
            'category_code' => 'required|max:4|unique:categories',
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'category_photo' => 'nullable|image',
        ]);

        if (!empty($request['category_photo'])) {
            $path = $request->file('category_photo')->store('category-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $category = Category::create($request->all());
            DB::commit();

            return redirect()->route('admin/commerce/categories')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Category created successfully.')
                ->with('route', 'admin/commerce/categories/edit')
                ->with('id', $category->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Commerce/Categories/AddOrEditCategory', [
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
        $category = Category::whereId($id)->first();

        if (!isset($category)) {
            return redirect()->route('404');
        }

        return Inertia::render('Admin/Commerce/Categories/AddOrEditCategory', ['category' => $category]);
    }

    public function update(Request $request)
    {
        $category = Category::find($request['id']);

        if (!isset($category)) {
            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'error')
                ->with('message', 'Category to be updated was not found.');
        }

        $request->validate([
            'id' => 'required',
            'category_name' => 'required|max:255',
            'category_code' => [
                'required',
                'max:4',
                Rule::unique('categories')->ignore($category->id),
            ],
            'address_1' => 'nullable|max:255',
            'address_2' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'img_url' => 'nullable|url',
            'active' => 'required|boolean',
            'category_photo' => 'nullable|image',
        ]);

        if (isset($category['img_path'])) {
            $isDeleted = $this->CommonController->deletePhoto($category['img_path']);
            if ($isDeleted) {
                $request['img_path'] = null;
            }
        }

        if (!empty($request['category_photo'])) {
            $path = $request->file('category_photo')->store('category-photos', 'private');
            $request['img_path'] = $path;
        }

        try {
            DB::beginTransaction();
            $category->update($request->all());
            DB::commit();

            return redirect()->route('admin/commerce/categories')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Category updated successfully.')
                ->with('route', 'admin/commerce/categories/edit')
                ->with('id', $category->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Commerce/Categories/AddOrEditCategory')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Failed to update record: ' . $this->CommonController->formatException($e));
        }
    }

    public function bulkUpdate(Request $request)
    {
        try {
            // Extract categories data from the request
            $categories = $request->all();

            // Start a DB transaction
            DB::beginTransaction();

            // Loop through each category data
            foreach ($categories as $categoryData) {
                // If validation passes, locate the category and update
                $category = Category::find($categoryData['id']);

                // Validate the data
                $validator = Validator::make($categoryData, [
                    'id' => 'required|exists:categories,id',
                    'category_name' => 'required|max:255',
                    'category_code' => [
                        'required',
                        'max:4',
                        Rule::unique('categories')->ignore($category->id),
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

                $category->update($categoryData);
            }

            // If all updates are successful, commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', count($categories) . ' categories bulk edited successfully.');
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
        $category = Category::find($request['id']);

        if (isset($category)) {
            $isDeleted = $this->CommonController->deletePhoto($request['img_path']);
            if ($isDeleted || !$isDeleted && !empty($category->img_path)) {
                try {
                    DB::beginTransaction();
                    $category->update(['img_path' => null]);
                    DB::commit();

                    return redirect()->back()
                        ->with('show', true)
                        ->with('type', 'default')
                        ->with('status', 'success')
                        ->with('message', 'Photo removed successfully.');
                } catch (\Exception $e) {
                    DB::rollBack();

                    return Inertia::render('Admin/Commerce/Categories/AddOrEditCategory')
                        ->with('show', true)
                        ->with('type', 'default')
                        ->with('status', 'success')
                        ->with('message', 'Failed to update record: ' . $this->CommonController->formatException($e));
                }
            }
        }

        return redirect()->back()
            ->with('show', true)
            ->with('type', 'default')
            ->with('status', 'error')
            ->with('message', 'No image was deleted or category was not found.');
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
                    'category_name' => 'required|string|max:255',
                    'category_code' => 'required|max:4|unique:categories',
                    'address_1' => 'nullable|max:255',
                    'address_2' => 'nullable|max:255',
                    'email' => 'nullable|email|max:255',
                    'phone_number' => 'nullable|max:255',
                    'mobile_number' => 'nullable|max:255',
                    'website' => 'nullable|url|max:255',
                    'img_url' => 'nullable|url|max:255',
                    'active' => 'required|in:0,1',
                ])->validate();

                // Create the category
                Category::create([
                    'category_name' => $row['category_name'],
                    'category_code' => $row['category_code'],
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
                ->with('message', count($data) . ' categories imported successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of errors
            DB::rollBack();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'dialog')
                ->with('status', 'error')
                ->with('message', 'Failed to update record: ' . $this->CommonController->formatException($e));
        }
    }


    public function exportCsv(Request $request)
    {
        // Define the CSV header
        $header = [
            'category_name',
            'category_code',
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
            // Fetch categories
            $categories = Category::withTrashed()->get();

            // Insert the categories data
            foreach ($categories as $category) {
                $row = [
                    $category->id,
                    $category->category_name,
                    $category->category_code,
                    $category->address_1,
                    $category->address_2,
                    $category->email,
                    $category->phone_number,
                    $category->mobile_number,
                    $category->website,
                    $category->img_url,
                    $category->active,
                    $category->created_at,
                    $category->updated_at,
                    $category->deleted_at,
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
        }, 'categories.csv');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);

        // Retrieve the ids
        $ids = $request->get('ids');

        DB::beginTransaction();

        try {
            // Delete the records
            $deletedCount = Category::destroy($ids);

            DB::commit();

            $context = 'categories';
            if ($deletedCount == 1) $context = 'category';

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
                ->with('message', 'Failed to delete record: ' . $this->CommonController->formatException($e));
        }
    }
}
