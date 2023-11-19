<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Rules\UniqueSubcategoryCode;
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
        $request['sortBy'] = $request['sortBy'] ?? 'category_name';
        $request['orderBy'] = $request['orderBy'] ?? 'asc';
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
        $category_name_or_code = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['category_name_or_code']  ?? null : null;
        $subcategory_name_or_code = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['subcategory_name_or_code']  ?? null : null;

        $categories = Category::query();
        if (!empty($category_name_or_code)) {
            $categories->where(function ($q) use ($category_name_or_code) {
                $q->where('category_name', 'like', '%' . $category_name_or_code . '%')
                    ->orWhere('category_code', 'like', '%' . $category_name_or_code . '%');
            });
        }

        if (!empty($subcategory_name_or_code)) {
            $categories->whereHas('subcategories', function ($query) use ($subcategory_name_or_code) {
                $query->where('subcategory_name', 'like', '%' . $subcategory_name_or_code . '%')
                    ->orWhere('subcategory_code', 'like', '%' . $subcategory_name_or_code . '%');
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

        // Join with subcategories
        $categories->with(['subcategories' => function ($query) {
            $query->select('id', 'category_id', 'subcategory_name', 'subcategory_code');
            $query->orderBy('subcategory_code', 'asc');
        }]);

        return Inertia::render('Admin/Commerce/Categories/Overview', [
            'sortBy' => $request['sortBy'],
            'orderBy' => $request['orderBy'],
            'paginatedResults' =>
            $categories
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
        $category = Category::withTrashed()->where('id', '=', $id)->with('subcategories')->firstOrFail();

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
            'description' => 'nullable|string|max:1000',
            'subcategories' => 'array',
            'subcategories.*.subcategory_name' => 'required|string|max:255',
            'subcategories.*.subcategory_code' => 'required|string|max:4',
            'subcategories.*.description' => 'nullable|string|max:1000',
            'subcategories.*.category_id' => 'required|exists:categories,id',
        ], [
            'subcategories.*.subcategory_name.required' => 'The subcategory name is required.',
            'subcategories.*.subcategory_code.required' => 'The subcategory code is required.',
            'subcategories.*.description.required' => 'The subcategory description is required.'
        ]);

        try {
            DB::beginTransaction();
            $category = Category::create($request->only(['category_name', 'category_code', 'description']));

            $subcategoriesData = $this->getSubcategoriesFromRequest($request);

            foreach ($subcategoriesData as $subcategoryData) {
                $subcategory = new Subcategory($subcategoryData);
                $category->subcategories()->save($subcategory);
            }

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
        $category = Category::whereId($id)
            ->with(['subcategories' => function ($query) {
                $query
                    ->withoutTrashed()
                    ->orderBy('subcategory_name');
            }])
            ->firstOrFail();

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

        $this->getEditCategoryValidator($request, $category)->validate();

        try {
            DB::beginTransaction();
            $category->update($request->only(['category_name', 'category_code', 'description']));
            $subcategoriesData = $this->getSubcategoriesFromRequest($request);
            foreach ($subcategoriesData as $subcategoryData) {
                unset($s);
                if (isset($subcategoryData['id'])) {
                    $s = Subcategory::whereId($subcategoryData['id']);
                }

                if (isset($s) && $subcategoryData['is_deleted']) {
                    Subcategory::destroy($subcategoryData['id']);
                } else {
                    unset($subcategoryData['is_deleted']);

                    if (!isset($s)) {
                        $subcategory = new Subcategory($subcategoryData);
                        $category->subcategories()->save($subcategory);
                    } else {
                        $s->update($subcategoryData);
                    }
                }
            }

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

            return $this->CommonController->handleException($e);
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
                $validator = $this->getEditCategoryValidator($categoryData, $category);

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

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);

        // Retrieve the ids
        $ids = $request->get('ids');


        try {
            DB::beginTransaction();

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

            return $this->CommonController->handleException($e, 'default', 'delete');
        }
    }

    private function getSubcategoriesFromRequest(Request $request)
    {
        $subcategoriesData = $request->input('subcategories', []);
        $allDeleted = collect($subcategoriesData)->every(function ($subcategory) {
            return $subcategory['is_deleted'] ?? false; // Return true if is_deleted is not set or is true
        });

        if (count($subcategoriesData) == 0 || $allDeleted) {
            $defaultSubcategory = [
                'subcategory_name' => 'DEFAULT',
                'subcategory_code' => 'DFLT',
                'description' => 'Default subcategory'
            ];

            // Merge the default subcategory into $subcategoriesData
            $subcategoriesData[] = $defaultSubcategory;
        }

        return $subcategoriesData;
    }

    private function getEditCategoryValidator(mixed $request, Category $category)
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
            'category_name' => 'required|max:255',
            'category_code' => [
                'required',
                'max:4',
                Rule::unique('categories')->ignore($category->id),
            ],
            'subcategories' => 'array',
            'subcategories.*' => [new UniqueSubcategoryCode($category->id)],
            'subcategories.*.subcategory_name' => 'required|string|max:255',
            'subcategories.*.subcategory_code' => [
                'required',
                'max:4',
            ],
            'subcategories.*.description' => 'nullable|string|max:1000',
            'subcategories.*.category_id' => 'required|exists:categories,id',
        ], [
            'subcategories.*.subcategory_name.required' => 'The subcategory name is required.',
            'subcategories.*.subcategory_code.required' => 'The subcategory code is required.',
            'subcategories.*.description.required' => 'The subcategory description is required.'
        ]);
    }
}
