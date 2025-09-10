@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ trans('main_trans.message_details') }}</h4>
                    <div>
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ trans('main_trans.actions_back') }}
                        </a>
                        <a href="{{ route('messages.edit', $message->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>
                            {{ trans('main_trans.actions_edit') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Image Section -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ trans('main_trans.image') }}</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($message->image_path)
                                        <img src="{{ asset('messagesfiles/' . $message->image_path) }}" 
                                             alt="{{ $message->name_en }}" class="img-thumbnail" 
                                             style="width: 200px; height: 250px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('admin/img/no-image.png') }}" 
                                             alt="No Image" class="img-thumbnail" 
                                             style="width: 200px; height: 250px; object-fit: cover;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.person_name') }} ({{ trans('main_trans.english') }})</label>
                                        <p class="form-control-plaintext">{{ $message->name_en }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.person_name') }} ({{ trans('main_trans.arabic') }})</label>
                                        <p class="form-control-plaintext">{{ $message->name_ar }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.person_position') }} ({{ trans('main_trans.english') }})</label>
                                        <p class="form-control-plaintext">{{ $message->position_en }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.person_position') }} ({{ trans('main_trans.arabic') }})</label>
                                        <p class="form-control-plaintext">{{ $message->position_ar }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.message_content') }} ({{ trans('main_trans.english') }})</label>
                                        <div class="border p-3 bg-light" style="min-height: 150px;">
                                            {{ $message->message_en }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.message_content') }} ({{ trans('main_trans.arabic') }})</label>
                                        <div class="border p-3 bg-light" style="min-height: 150px;">
                                            {{ $message->message_ar }}
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.created_at') }}</label>
                                        <p class="form-control-plaintext">{{ $message->created_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">{{ trans('main_trans.updated_at') }}</label>
                                        <p class="form-control-plaintext">{{ $message->updated_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
