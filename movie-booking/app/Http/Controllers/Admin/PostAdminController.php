<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostAdminController extends Controller
{
    // danh sách bài viết
    public function list()
    {
        $posts = Post::with('author')->orderBy('created_at','desc')->paginate(15);
        return view('admin.posts.list', compact('posts'));
    }

    // tạo bài viết
    public function create()
    {
        return view('admin.posts.create');
    }

    // lưu bài viết
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'slug'=>'nullable|string|max:255|unique:posts,slug',
            'content'=>'required|string',
            'excerpt'=>'nullable|string|max:1000',
            'thumbnail'=>'nullable|image|max:2048',
            'category'=>'required|in:review,news,article',
            'status'=>'required|in:draft,published',
            'published_at'=>'nullable|date',
        ]);

        // handle thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('posts','public');
            $data['thumbnail'] = 'storage/'.$path;
        }

        $data['author_id'] = auth()->id();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        if ($data['status'] == 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Post::create($data);

        return redirect()->route('admin.posts.list')->with('success','Tạo bài viết thành công');
    }

    // chỉnh sửa bài viết
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    // cập nhật bài viết
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->validate([
            'title'=>'required|string|max:255',
            'slug'=>'nullable|string|max:255|unique:posts,slug,'.$post->id,
            'content'=>'required|string',
            'excerpt'=>'nullable|string|max:1000',
            'thumbnail'=>'nullable|image|max:2048',
            'category'=>'required|in:review,news,article',
            'status'=>'required|in:draft,published',
            'published_at'=>'nullable|date',
        ]);

        if ($request->hasFile('thumbnail')) {
            // remove old
            if ($post->thumbnail) {
                // Handle both old format (/storage/...) and new format (storage/...)
                $old = str_replace(['/storage/', 'storage/'], '', $post->thumbnail);
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('thumbnail')->store('posts','public');
            $data['thumbnail'] = 'storage/'.$path;
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($data['status'] == 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()->route('admin.posts.list')->with('success','Cập nhật bài viết thành công');
    }

    // xóa bài viết
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->thumbnail) {
            // Xử lý cả định dạng cũ (/storage/...) và định dạng mới (storage/...).
            $old = str_replace(['/storage/', 'storage/'], '', $post->thumbnail);
            Storage::disk('public')->delete($old);
        }
        $post->delete();
        return back()->with('success','Xóa bài viết thành công');
    }

    // Endpoint upload tùy chọn cho trình soạn thảo rich text
    public function uploadImage(Request $request)
{
    if (!$request->hasFile('file')) {
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    $file = $request->file('file');
    $path = $file->store('uploads/posts', 'public');

    return response()->json([
        'location' => asset('storage/' . $path)
    ]);
}

}
