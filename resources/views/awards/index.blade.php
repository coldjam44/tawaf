@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- فورم إضافة جائزة جديدة -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ trans('main_trans.add_our_awards') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('awards.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- العناوين ثنائية اللغة --}}
                        <div class="mb-3">
                            <label for="title_ar" class="form-label">{{ trans('main_trans.title_ar') }} *</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar') }}" required>
                            @error('title_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title_en" class="form-label">{{ trans('main_trans.title_en') }} *</label>
                            <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en') }}" required>
                            @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- الوصف ثنائي اللغة --}}
                        <div class="mb-3">
                            <label for="description_ar" class="form-label">{{ trans('main_trans.description_ar') }} *</label>
                            <textarea name="description_ar" id="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="3" required>{{ old('description_ar') }}</textarea>
                            @error('description_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description_en" class="form-label">{{ trans('main_trans.description_en') }} *</label>
                            <textarea name="description_en" id="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3" required>{{ old('description_en') }}</textarea>
                            @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- الصورة والسنة والفئة --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">{{ trans('main_trans.image') }}</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF</small>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="year" class="form-label">{{ trans('main_trans.year') }}</label>
                                    <input type="text" name="year" id="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}" placeholder="2024">
                                    @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">{{ trans('main_trans.category') }}</label>
                                    <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="أفضل شركة عقارية">
                                    @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- الترتيب --}}
                        <div class="mb-3">
                            <label for="order_index" class="form-label">{{ trans('main_trans.order') }}</label>
                            <input type="number" name="order_index" id="order_index" class="form-control @error('order_index') is-invalid @enderror" value="{{ old('order_index', 0) }}" min="0">
                            @error('order_index')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- زر الحفظ --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                {{ trans('main_trans.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- جدول الجوائز -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ trans('main_trans.awards') }}</h5>
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

                    @if($awards->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">{{ trans('main_trans.order') }}</th>
                                        <th style="width: 80px;">{{ trans('main_trans.image') }}</th>
                                        <th>{{ trans('main_trans.title') }}</th>
                                        <th style="width: 80px;">{{ trans('main_trans.year') }}</th>
                                        <th style="width: 120px;">{{ trans('main_trans.category') }}</th>
                                        <th style="width: 80px;">{{ trans('main_trans.status') }}</th>
                                        <th style="width: 100px;">{{ trans('main_trans.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($awards as $award)
                                    <tr>
                                        <td class="text-center">{{ $award->order_index }}</td>
                                        <td class="text-center">
                                            @if($award->image)
                                                <img src="{{ $award->image_url }}" alt="Award Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                            @else
                                                <span class="text-muted small">{{ trans('main_trans.no_image') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $award->title_ar }}</div>
                                            <div class="text-muted small">{{ $award->title_en }}</div>
                                            <div class="text-muted small mt-1">{{ Str::limit($award->description_ar, 50) }}</div>
                                        </td>
                                        <td class="text-center">{{ $award->year ?: '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $award->category ?: '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($award->is_active)
                                                <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ trans('main_trans.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('awards.edit', $award->id) }}" class="btn btn-warning" title="{{ trans('main_trans.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('awards.destroy', $award->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="{{ trans('main_trans.delete') }}">
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
                            <i class="fas fa-trophy fa-2x text-muted mb-3"></i>
                            <h6 class="text-muted">{{ trans('main_trans.no_awards_found') }}</h6>
                            <p class="text-muted small">استخدم الفورم على اليسار لإضافة جائزة جديدة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
