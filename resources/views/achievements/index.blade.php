@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ trans('main_trans.our_achievement') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('achievements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.name_ar') }} *</label>
                            <input type="text" name="name_ar" value="{{ old('name_ar') }}" class="form-control @error('name_ar') is-invalid @enderror" required>
                            @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.name_en') }} *</label>
                            <input type="text" name="name_en" value="{{ old('name_en') }}" class="form-control @error('name_en') is-invalid @enderror" required>
                            @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.image') }}</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            
                        </div>

                        

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ trans('main_trans.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ trans('main_trans.our_achievement') }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    
                                    <th>{{ trans('main_trans.name') }}</th>
                                    <th>{{ trans('main_trans.image') }}</th>
                                    <th style="width:100px;">{{ trans('main_trans.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($achievements as $ach)
                                <tr>
                                    
                                    <td>
                                        <div class="fw-bold">{{ $ach->name_ar }}</div>
                                        <div class="text-muted small">{{ $ach->name_en }}</div>
                                    </td>
                                    <td>
                                        @if($ach->image_url)
                                            <img src="{{ $ach->image_url }}" alt="" class="img-thumbnail" style="max-width:60px; max-height:60px;">
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('achievements.edit', $ach->id) }}" class="btn btn-warning btn-sm me-1">{{ trans('main_trans.edit') }}</a>
                                        <form action="{{ route('achievements.destroy', $ach->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">{{ trans('main_trans.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ trans('main_trans.no_achievements_found') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


