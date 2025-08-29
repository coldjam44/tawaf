@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.add_project_detail') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('project-details.index', $project->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- رسائل الخطأ --}}
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- أخطاء الإدخال --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- فورم الإضافة --}}
                <form action="{{ route('project-details.store', $project->id) }}" method="POST">
                    @csrf

                    {{-- التفصيل ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="detail_ar" class="form-label">{{ trans('main_trans.detail_ar') }} *</label>
                                <input type="text" name="detail_ar" id="detail_ar" class="form-control @error('detail_ar') is-invalid @enderror" value="{{ old('detail_ar') }}" required>
                                @error('detail_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="detail_en" class="form-label">{{ trans('main_trans.detail_en') }} *</label>
                                <input type="text" name="detail_en" id="detail_en" class="form-control @error('detail_en') is-invalid @enderror" value="{{ old('detail_en') }}" required>
                                @error('detail_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- قيمة التفصيل ثنائية اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="detail_value_ar" class="form-label">{{ trans('main_trans.detail_value_ar') }} *</label>
                                <textarea name="detail_value_ar" id="detail_value_ar" class="form-control @error('detail_value_ar') is-invalid @enderror" rows="3" required>{{ old('detail_value_ar') }}</textarea>
                                @error('detail_value_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="detail_value_en" class="form-label">{{ trans('main_trans.detail_value_en') }} *</label>
                                <textarea name="detail_value_en" id="detail_value_en" class="form-control @error('detail_value_en') is-invalid @enderror" rows="3" required>{{ old('detail_value_en') }}</textarea>
                                @error('detail_value_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الترتيب --}}
                    <div class="mb-3">
                        <label for="order" class="form-label">{{ trans('main_trans.order') }}</label>
                        <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}" min="0">
                        <small class="form-text text-muted">الترتيب الذي سيظهر به هذا التفصيل في القائمة</small>
                        @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <a href="{{ route('project-details.index', $project->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ trans('main_trans.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ trans('main_trans.submit') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
