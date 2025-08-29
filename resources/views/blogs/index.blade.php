@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ trans('main_trans.blogs') }}</h4>
                    <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        {{ trans('main_trans.add_blog') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($blogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ trans('main_trans.order') }}</th>
                                        <th>{{ trans('main_trans.blog_title') }}</th>
                                        <th>{{ trans('main_trans.main_image') }}</th>
                                        <th>{{ trans('main_trans.additional_images') }}</th>
                                        <th>{{ trans('main_trans.blog_status') }}</th>
                                        <th>{{ trans('main_trans.status') }}</th>
                                        <th>{{ trans('main_trans.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blogs as $blog)
                                    <tr>
                                        <td>{{ $blog->order_index }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ trans('main_trans.title_ar') }}:</strong> {{ $blog->title_ar }}
                                            </div>
                                            <div>
                                                <strong>{{ trans('main_trans.title_en') }}:</strong> {{ $blog->title_en }}
                                            </div>
                                            <small class="text-muted">Slug: {{ $blog->slug }}</small>
                                        </td>
                                        <td>
                                            @if($blog->main_image)
                                                <img src="{{ $blog->main_image_url }}" alt="Main Image" class="img-thumbnail" style="max-width: 80px;">
                                            @else
                                                <span class="text-muted">{{ trans('main_trans.no_image') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $blog->images->count() }} {{ trans('main_trans.images') }}</span>
                                            @if($blog->images->count() > 0)
                                                <br><small class="text-muted">{{ trans('main_trans.click_to_view') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($blog->status === 'published')
                                                <span class="badge bg-success">{{ trans('main_trans.published') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ trans('main_trans.draft') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($blog->is_active)
                                                <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ trans('main_trans.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ trans('main_trans.no_blogs_found') }}</h5>
                            <a href="{{ route('blogs.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>
                                {{ trans('main_trans.add_blog') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
