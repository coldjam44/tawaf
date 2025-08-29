@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- فورم إضافة عضو جديد -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ trans('main_trans.expert_team') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('expert-team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- الاسم ثنائي اللغة --}}
                        <div class="mb-3">
                            <label for="name_ar" class="form-label">{{ trans('main_trans.name_ar') }} *</label>
                            <input type="text" name="name_ar" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar') }}" required>
                            @error('name_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name_en" class="form-label">{{ trans('main_trans.name_en') }} *</label>
                            <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en') }}" required>
                            @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- المنصب ثنائي اللغة --}}
                        <div class="mb-3">
                            <label for="position_ar" class="form-label">{{ trans('main_trans.position_ar') }} *</label>
                            <input type="text" name="position_ar" id="position_ar" class="form-control @error('position_ar') is-invalid @enderror" value="{{ old('position_ar') }}" required>
                            @error('position_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position_en" class="form-label">{{ trans('main_trans.position_en') }} *</label>
                            <input type="text" name="position_en" id="position_en" class="form-control @error('position_en') is-invalid @enderror" value="{{ old('position_en') }}" required>
                            @error('position_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- الصورة والترتيب --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">{{ trans('main_trans.image') }}</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF</small>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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

        <!-- جدول أعضاء الفريق -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ trans('main_trans.expert_team') }}</h5>
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

                    @if($members->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">{{ trans('main_trans.order') }}</th>
                                        <th style="width: 80px;">{{ trans('main_trans.image') }}</th>
                                        <th>{{ trans('main_trans.name') }}</th>
                                        <th>{{ trans('main_trans.position') }}</th>
                                        <th style="width: 100px;">{{ trans('main_trans.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                    <tr>
                                        <td class="text-center">{{ $member->order_index }}</td>
                                        <td class="text-center">
                                            @if($member->image)
                                                <img src="{{ $member->image_url }}" alt="Member Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                            @else
                                                <span class="text-muted small">{{ trans('main_trans.no_image') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $member->name_ar }}</div>
                                            <div class="text-muted small">{{ $member->name_en }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $member->position_ar }}</div>
                                            <div class="text-muted small">{{ $member->position_en }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('expert-team.edit', $member->id) }}" class="btn btn-warning btn-sm me-1" title="{{ trans('main_trans.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('expert-team.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('main_trans.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                            <h6 class="text-muted">لا يوجد أعضاء في الفريق</h6>
                            <p class="text-muted small">استخدم الفورم على اليسار لإضافة عضو جديد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
