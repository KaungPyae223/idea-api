<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryRepository->create($request->all());
        return response()->json(['message' => 'Category created successfully.', 'category' => new CategoryResource($category)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:categories,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid category ID'
            ], 404);
        }

        $category = $this->categoryRepository->find($id);
        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:categories,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid category ID'
            ], 404);
        }

        $category = $this->categoryRepository->update($id, $request->all());
        return response()->json(['message' => 'Category updated successfully.', 'category' => new CategoryResource($category)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:categories,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid category ID'
            ], 404);
        }

        $category = $this->categoryRepository->destroy($id);
        return response()->json(['message' => 'Category deleted successfully.']);
    }
}