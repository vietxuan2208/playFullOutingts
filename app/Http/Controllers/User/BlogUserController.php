<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blog;


class BlogUserController extends Controller
{

    public function index()
    {
        $blogs = Blog::with(['user:id,name,email'])
            ->where('is_delete', 0)
            ->latest('posted_date')
            ->paginate(6);

        return view('user.blog.index', compact('blogs'));
    }

    public function show($id)
    {
        $blog = Blog::with(['user:id,name,email'])
            ->findOrFail($id);

        return view('user.blog.show', compact('blog'));
    }
}
