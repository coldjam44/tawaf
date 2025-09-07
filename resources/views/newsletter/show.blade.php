@extends('admin.layouts.app')

@section('title', trans('main_trans.newsletter'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-12">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-primary">{{ trans('main_trans.newsletter') }}</h5>
                                <div>
                                    <a href="{{ route('newsletter.edit', $newsletter->id) }}" class="btn btn-warning">
                                        <i class="ti ti-edit"></i> {{ trans('main_trans.edit') }}
                                    </a>
                                    <a href="{{ route('newsletter.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-arrow-left"></i> {{ trans('main_trans.back') }}
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ trans('main_trans.id') }}</label>
                                    <p class="form-control-plaintext">{{ $newsletter->id }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ trans('main_trans.name') }}</label>
                                    <p class="form-control-plaintext">{{ $newsletter->name ?? '-' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ trans('main_trans.email') }}</label>
                                    <p class="form-control-plaintext">
                                        @if($newsletter->email)
                                            <a href="mailto:{{ $newsletter->email }}">{{ $newsletter->email }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ trans('main_trans.phone') }}</label>
                                    <p class="form-control-plaintext">
                                        @if($newsletter->phone)
                                            <a href="tel:{{ $newsletter->phone }}">{{ $newsletter->phone }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">{{ trans('main_trans.message') }}</label>
                                    <div class="form-control-plaintext" style="min-height: 100px; white-space: pre-wrap;">
                                        {{ $newsletter->message ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ trans('main_trans.created_at') }}</label>
                                    <p class="form-control-plaintext">{{ $newsletter->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ trans('main_trans.updated_at') }}</label>
                                    <p class="form-control-plaintext">{{ $newsletter->updated_at->format('Y-m-d H:i:s') }}</p>
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
