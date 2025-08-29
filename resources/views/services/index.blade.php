@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ trans('main_trans.our_services') }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>{{ trans('main_trans.title') }}</th>
                                    <th>{{ trans('main_trans.image') }}</th>
                                    <th>{{ trans('main_trans.phone') }}</th>
                                    
                                    <th style="width:120px;">{{ trans('main_trans.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $svc)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $svc->title_ar }}</div>
                                        <div class="text-muted small">{{ $svc->title_en }}</div>
                                    </td>
                                    <td>
                                        @if($svc->image_url)
                                            <img src="{{ $svc->image_url }}" alt="" class="img-thumbnail" style="max-width:60px; max-height:60px;">
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $svc->contact_phone }}</td>
                                    
                                    <td class="text-center">
                                        <a href="{{ route('services.edit', $svc->id) }}" class="btn btn-warning btn-sm me-1">{{ trans('main_trans.edit') }}</a>
                                        <form action="{{ route('services.destroy', $svc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">{{ trans('main_trans.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">—</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.title_ar') }}</label>
                            <input type="text" name="title_ar" value="{{ old('title_ar') }}" class="form-control @error('title_ar') is-invalid @enderror" required>
                            @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.title_en') }}</label>
                            <input type="text" name="title_en" value="{{ old('title_en') }}" class="form-control @error('title_en') is-invalid @enderror" required>
                            @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.description_ar') }}</label>
                            <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="3">{{ old('description_ar') }}</textarea>
                            @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.description_en') }}</label>
                            <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en') }}</textarea>
                            @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.image') }}</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.phone') }}</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="form-control @error('contact_phone') is-invalid @enderror">
                            @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ trans('main_trans.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


