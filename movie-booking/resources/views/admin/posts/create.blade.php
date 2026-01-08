@extends('layouts.admin')

@section('title', 'Tạo bài viết')
@section('page-title', 'Tạo bài viết mới')

@section('content')
    <div class="content-card">

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <div class="col-md-8">
                    <label class="form-label fw-bold">Tiêu đề bài viết</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select name="category" class="form-select">
                        <option value="review">Bình luận phim</option>
                        <option value="news">Tin điện ảnh</option>
                        <option value="article">Bài viết</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ảnh thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="draft">Nháp</option>
                        <option value="published">Xuất bản</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Ngày xuất bản</label>
                    <input type="datetime-local" name="published_at" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Tóm tắt</label>
                    <textarea name="excerpt" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Nội dung</label>
                    <textarea id="editor" name="content"></textarea>
                </div>

            </div>

            <button type="submit" class="btn btn-primary mt-4">
                <i class="bi bi-save"></i> Lưu bài viết
            </button>

        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@5/tinymce.min.js"></script>

    <script>
        tinymce.init({
            selector: '#editor',
            height: 450,
            plugins: 'image link media code lists table',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | image media link | code',

            images_upload_url: "{{ route('admin.posts.upload-image') }}",
            images_upload_credentials: true,

            images_upload_handler: function (blobInfo, success, failure) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('admin.posts.upload-image') }}");
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.onload = function () {
                    if (xhr.status !== 200) {
                        failure("HTTP Error: " + xhr.status);
                        return;
                    }
                    var json = JSON.parse(xhr.responseText);
                    if (!json.location) {
                        failure("Invalid JSON: " + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };

                var formData = new FormData();
                formData.append('file', blobInfo.blob());
                xhr.send(formData);
            }
        });
    </script>
@endsection