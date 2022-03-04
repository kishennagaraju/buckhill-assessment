<?php

namespace App\Http\Controllers;

use App\Traits\Models\Post;

class BlogController extends Controller
{
    use Post;

    public function index()
    {
        return response()->json($this->getPostModel()->listPosts())->setStatusCode(200);
    }

    public function show($uuid)
    {
        return response()->json($this->getPostModel()->getPostByUuid($uuid))->setStatusCode(200);
    }
}
