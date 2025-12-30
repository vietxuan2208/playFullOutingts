<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BlogController extends Controller
{

    public function index()
    {
        $blogs = Blog::where('is_delete', 0)
            ->with('user')
            ->orderBy('posted_date', 'desc')
            ->paginate(6);
        return view('admin.blog.index', compact('blogs'));
    }


    public function create()
    {
        $users = User::all();
        return view('admin.blog.create', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'blog_name' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        Blog::create([
            'blog_name' => $request->blog_name,
            'user_id' => $request->user_id,
            'posted_date' => Carbon::now(),
            'image' => $imagePath,
            'content' => $request->content,
            'is_delete' => 0,
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Create a successful blog!');
    }


    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $users = User::all();

        return view('admin.blog.edit', compact('blog', 'users'));
    }


    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'blog_name' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = $request->file('image')->store('blogs', 'public');
        }

        $blog->blog_name = $request->blog_name;
        $blog->user_id = $request->user_id;
        $blog->content = $request->content;

        $blog->save();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog update successful!');
    }


    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        $blog->is_delete = 1;
        $blog->save();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog deleted successfully!');
    }

    public function show($id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('admin.blog.show', compact('blog'));
    }
}
