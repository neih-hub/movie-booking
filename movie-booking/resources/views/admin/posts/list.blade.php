@extends('layouts.admin')

@section('title', 'Quản lý bài viết')
@section('page-title', 'Danh sách bài viết')

@section('content')

<div class="content-card">

    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Tạo bài viết mới
    </a>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th width="80">Ảnh</th>
                <th>Tiêu đề</th>
                <th width="120">Danh mục</th>
                <th width="70">Views</th>
                <th width="120">Trạng thái</th>
                <th width="150">Ngày xuất bản</th>
                <th width="120">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $p)
            <tr>
                <td>
                    @if($p->thumbnail)
                        <img src="{{ asset($p->thumbnail) }}" width="70" class="img-thumbnail">
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>

                <td>{{ $p->title }}</td>

                <td><span class="badge bg-info">{{ $p->category }}</span></td>

                <td>{{ $p->views }}</td>

                <td>
                    @if($p->status == 'published')
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </td>

                <td>
                    {{ $p->published_at ? $p->published_at->format('d/m/Y H:i') : '—' }}
                </td>

                <td>
                    <a href="{{ route('admin.posts.edit', $p->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <form action="{{ route('admin.posts.delete', $p->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button onclick="return confirm('Xóa bài viết này?')" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $posts->links() }}
    </div>

</div>

@endsection
