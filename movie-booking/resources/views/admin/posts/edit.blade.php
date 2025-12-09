@extends('layouts.admin')

@section('title', 'Sửa bài viết')
@section('page-title', 'Sửa bài viết')

@section('content')
<div class="content-card">

    <div class="card-header">
        <h2 class="card-title">
            <i class="bi bi-pencil-square"></i> Sửa bài viết
        </h2>
        <a href="{{ route('admin.posts.list') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.posts.update', $post->id) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid-2">
            {{-- Tiêu đề --}}
            <div class="form-group">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $post->title) }}" required>
            </div>

            {{-- Danh mục --}}
            <div class="form-group">
                <label class="form-label">Danh mục</label>
                <select name="category" class="form-select">
                    <option value="review"  {{ $post->category == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="news"    {{ $post->category == 'news' ? 'selected' : '' }}>Tin tức</option>
                    <option value="article" {{ $post->category == 'article' ? 'selected' : '' }}>Bài viết</option>
                </select>
            </div>

            {{-- Trạng thái --}}
            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="draft"     {{ $post->status == 'draft' ? 'selected' : '' }}>Nháp</option>
                    <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Xuất bản</option>
                </select>
            </div>

            {{-- Ngày xuất bản --}}
            <div class="form-group">
                <label class="form-label">Ngày xuất bản</label>
                <input type="datetime-local" name="published_at" class="form-control"
                       value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        {{-- Thumbnail --}}
        <div class="form-group mt-3">
            <label class="form-label">Thumbnail</label>

            @if ($post->thumbnail)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset($post->thumbnail) }}" 
                         style="height: 120px; border-radius: 6px;">
                </div>
            @endif

            <input type="file" name="thumbnail" class="form-control">
        </div>

        {{-- Tóm tắt --}}
        <div class="form-group mt-3">
            <label class="form-label">Tóm tắt</label>
            <textarea name="excerpt" rows="3" class="form-control">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        {{-- Nội dung --}}
        <div class="form-group mt-3">
            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
            <textarea id="editor" name="content" rows="10" class="form-control">
                {{ old('content', $post->content) }}
            </textarea>
        </div>

        <div class="mt-4" style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.posts.list') }}" class="btn btn-danger">
                <i class="bi bi-x-lg"></i> Hủy
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
{{-- TinyMCE 5 (KHÔNG cần API Key) --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@5/tinymce.min.js"></script>

<script>
tinymce.init({
    selector: '#editor',
    height: 450,
    plugins: 'image link media code lists table',
    toolbar:
        'undo redo | styleselect | bold italic underline | ' +
        'alignleft aligncenter alignright | bullist numlist | ' +
        'image media link | code',

    images_upload_url: "{{ route('admin.posts.upload-image') }}",
    images_upload_credentials: true,

    images_upload_handler: function (blobInfo, success, failure) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "{{ route('admin.posts.upload-image') }}");
        xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");

        xhr.onload = function () {
            if (xhr.status !== 200) {
                failure("Error: " + xhr.status);
                return;
            }

            let json = JSON.parse(xhr.responseText);
            if (!json.location) {
                failure("Invalid response: " + xhr.responseText);
                return;
            }

            success(json.location);
        };

        let formData = new FormData();
        formData.append("file", blobInfo.blob());
        xhr.send(formData);
    }
});
</script>
@endsection
