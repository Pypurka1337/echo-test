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

        $authors = Author::where('id', '>', 0);

        $articles = $request->query('articles');
        if ($articles) {
            $authors = $authors->whereHas('articles', function ($query) use ($articles) {
                $query->where($articles);
            });
        }

        $sort = $request->query('sort');
        if ($sort) {
            foreach ($sort as $value) {
                $is_asc = (substr($value, 0, 1) !== '-');
                if ($is_asc) {
                    $authors->orderBy($value);
                } else {
                    $value = substr($value, 1);
                    $authors->orderByDesc($value);
                }
            }
        }

        $filters = $request->query('filters');
        if ($filters) {
            $authors->where($filters);
        }

        $fields = $request->query('fields');
        if ($fields) {
            $fields[] = 'id';
            $fields = in_array('id', $fields) ? $fields : $fields[] = 'id';
            $authors->select($fields);
        }

        $size = $request->query('size', 10);
        $result = $authors->paginate($size)->withQueryString();
        foreach ($result->items() as $author) {
            $author->detail_page_url = $request->url() . '/' . $author->id;
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
    public function store(Request $request)
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
    public function update(Request $request, Author $author)
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
