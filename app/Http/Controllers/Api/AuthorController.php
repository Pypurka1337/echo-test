<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'articles' => ['array'],
            'sort' => ['array'],
            'filters' => ['array'],
            'fields' => ['array'],
        ]);

        $builder = Author::where('id', '>', 0);

        $builder = Author::whereArticles($request, $builder);
        Author::doSorting($request, $builder);
        Author::doFiltering($request, $builder);
        Author::selectFields($request, $builder);

        $result = Author::makePaginate($request, $builder);


        foreach ($result->items() as $author) {
            $author->detail_page_url = $request->url() . '/' . $author->slug;
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param Author $author
     * @return JsonResponse
     */
    public function show(Request $request, Author $author): JsonResponse
    {
        $articlesData = $request->query('articles-data');
        if ($articlesData == 'true') {
            $articles = $author->articles;
        }

        return response()->json([
            'data' => $author,
        ]);
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
                'first_name' => ['required', 'max:255'],
                'middle_name' => ['required', 'max:255'],
                'last_name' => ['required', 'max:255'],
                'avatar' => ['required', 'max:255'],
                'birth_year' => ['required'],
                'biography' => ['required'],
            ]
        );

        $newAuthor = Author::create($request->all());
        return response()->json(
            [
                'data' => $newAuthor,
            ],
            201
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Author $author
     * @return JsonResponse
     */
    public function update(Request $request, Author $author): JsonResponse
    {
        $request->validate(
            [
                'first_name' => ['max:255'],
                'middle_name' => ['max:255'],
                'last_name' => ['max:255'],
                'avatar' => ['max:255'],
            ]
        );

        $author->update($request->all());

        return response()->json([
            'data' => $author,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Author $author
     * @return JsonResponse
     */
    public function destroy(Author $author): JsonResponse
    {
        Author::destroy($author->id);
        return response()->json()->setStatusCode(204);
    }
}
