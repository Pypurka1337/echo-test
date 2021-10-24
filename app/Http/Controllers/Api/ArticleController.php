<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
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
            'author' => ['array'],
            'categories' => ['array'],
            'sort' => ['array'],
            'filters' => ['array'],
            'fields' => ['array'],
        ]);

        $articles = Article::where('id', '>', 0);

        $author = $request->query('author');
        if ($author) {
            $articles = $articles->whereHas('author', function ($query) use ($author) {
                $query->where($author);
            });
        }

        $categories = $request->query('categories');
        if ($categories) {
            $articles = $articles->whereHas('categories', function ($query) use ($categories) {
                $query->where($categories);
            });
        }

        $sort = $request->query('sort');
        if ($sort) {
            foreach ($sort as $value) {
                $is_asc = (substr($value, 0, 1) !== '-');
                if ($is_asc) {
                    $articles->orderBy($value);
                } else {
                    $value = substr($value, 1);
                    $articles->orderByDesc($value);
                }
            }
        }

        $filters = $request->query('filters');
        if ($filters) {
            $articles->where($filters);
        }

        $fields = $request->query('fields');
        if ($fields) {
            $fields[] = 'id';
            $fields = in_array('id', $fields) ? $fields : $fields[] = 'id';
            $articles->select($fields);
        }

        $size = $request->query('size', 10);
        $result = $articles->paginate($size)->withQueryString();
        foreach ($result->items() as $article) {
            $article->detail_page_url = $request->url() . '/' . $article->id;
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        return response()->json([
            'data' => $article,
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
                'name' => ['required', 'max:255'],
                'image' => ['required', 'max:400'],
                'preview_text' => ['required'],
                'detail_text' => ['required'],
                'author_id' => ['required', 'integer'],
                'category_ids' => ['required', 'array'],
                'category_ids.*' => ['integer'],
            ]
        );

        $newArticle = Article::create($request->all());
        $newArticle->changeCategories($request->category_ids);

        return response()->json(
            [
                'data' => $newArticle,
            ],
            201
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $request->validate(
            [
                'name' => ['max:255'],
                'image' => ['max:400',],
                'author_id' => ['integer'],
                'category_ids' => ['array'],
                'category_ids.*' => ['integer'],
            ]
        );
        $article->update($request->all());
        if ($request->category_ids) {
            $article->changeCategories($request->category_ids);
        }
        return response()->json([
            'data' => $article,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        Article::destroy($article->id);
        return response()->json()->setStatusCode(204);
    }
}
