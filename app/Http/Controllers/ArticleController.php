<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function edit(Article $article)
    {
        return view('articles.edit', ['article' => $article]);
    }

    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article->with('user')->first()]);
    }
}
