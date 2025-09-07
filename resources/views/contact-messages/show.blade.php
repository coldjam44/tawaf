@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.contact_message_details') }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('contact-messages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- رسائل النجاح --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- رسائل الخطأ --}}
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- معلومات الرسالة --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.name') }}</h6>
                        <p class="fw-bold">{{ $message->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.email') }}</h6>
                        <p>
                            <a href="mailto:{{ $message->email }}" class="text-decoration-none">
                                {{ $message->email }}
                            </a>
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.phone') }}</h6>
                        <p>
                            @if($message->phone)
                                <a href="tel:{{ $message->phone }}" class="text-decoration-none">
                                    {{ $message->phone }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.status') }}</h6>
                        <p>
                            @if($message->is_read)
                                <span class="badge bg-success">{{ trans('main_trans.read') }}</span>
                            @else
                                <span class="badge bg-warning">{{ trans('main_trans.unread') }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.created_at') }}</h6>
                        <p>{{ $message->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.updated_at') }}</h6>
                        <p>{{ $message->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                {{-- محتوى الرسالة --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted">{{ trans('main_trans.message') }}</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $message->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- أزرار العمليات --}}
                <div class="row">
                    <div class="col-12">
                        <div class="btn-group" role="group">
                            <form action="{{ route('contact-messages.toggle-read', $message->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn {{ $message->is_read ? 'btn-warning' : 'btn-success' }}">
                                    <i class="fas {{ $message->is_read ? 'fa-envelope-open' : 'fa-envelope' }} me-1"></i>
                                    {{ $message->is_read ? trans('main_trans.mark_as_unread') : trans('main_trans.mark_as_read') }}
                                </button>
                            </form>
                            
                            <a href="{{ route('contact-messages.edit', $message->id) }}" class="btn btn-info">
                                <i class="fa fa-edit me-1"></i> {{ trans('main_trans.edit') }}
                            </a>
                            
                            <form action="{{ route('contact-messages.destroy', $message->id) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash me-1"></i> {{ trans('main_trans.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
