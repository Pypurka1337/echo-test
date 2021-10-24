<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
                'data' => Category::get()->toTree(),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        $category = Category::withDepth()->with('ancestors', 'descendants')->find($category->id);
        $category->siblings = $category->getSiblings();
        return response()->json([
                'data' => $category,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(
            [
                'name' => ['required', 'max:255'],
                'image' => ['required', 'max:400'],
                'description' => ['required'],
                'parent_id' => ['integer'],
            ]
        );

        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            $newCategory = Category::create($request->all(), $parent);
        } else {
            $newCategory = Category::create($request->all());
        }

        return response()->json(
            [
                'data' => $newCategory,
            ],
            201
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $request->validate(
            [
                'name' => ['max:255'],
                'image' => ['max:400'],
                'parent_id' => ['integer'],
            ]
        );

        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            $category->parent()->associate($parent);
        }
        $category->update($request->all());

        return response()->json([
            'data' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        Category::destroy($category->id);
        Category::fixTree();
        return response()->json()->setStatusCode(204);
    }
}
