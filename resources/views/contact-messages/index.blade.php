@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.contact_messages') }}</h3>
                <div class="text-right mb-3">
                    <span class="badge bg-primary">{{ $messages->total() }} {{ trans('main_trans.total_messages') }}</span>
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

                {{-- أزرار التصدير --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('contact-messages.export') }}" class="btn btn-success">
                                    <i class="fas fa-file-excel me-1"></i>
                                    {{ trans('main_trans.export_to_excel') }}
                                </a>
                            </div>
                            <div>
                                <span class="badge bg-info">{{ $messages->total() }} {{ trans('main_trans.total_messages') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- جدول الرسائل --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.id') }}</th>
                                <th>{{ trans('main_trans.name') }}</th>
                                <th>{{ trans('main_trans.email') }}</th>
                                <th>{{ trans('main_trans.phone') }}</th>
                                <th>{{ trans('main_trans.message') }}</th>
                                <th>{{ trans('main_trans.status') }}</th>
                                <th>{{ trans('main_trans.created_at') }}</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $message)
                            <tr class="{{ !$message->is_read ? 'table-warning' : '' }}">
                                <td>{{ $message->id }}</td>
                                <td>
                                    <div class="text-start">
                                        <div class="fw-bold">{{ $message->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $message->email }}" class="text-decoration-none">
                                        {{ $message->email }}
                                    </a>
                                </td>
                                <td>
                                    @if($message->phone)
                                        <a href="tel:{{ $message->phone }}" class="text-decoration-none">
                                            {{ $message->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-start">
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $message->message }}">
                                            {{ Str::limit($message->message, 100) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($message->is_read)
                                        <span class="badge bg-success">{{ trans('main_trans.read') }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ trans('main_trans.unread') }}</span>
                                    @endif
                                </td>
                                <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('contact-messages.show', $message->id) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('contact-messages.toggle-read', $message->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn {{ $message->is_read ? 'btn-warning' : 'btn-success' }} btn-sm" 
                                                    title="{{ $message->is_read ? trans('main_trans.mark_as_unread') : trans('main_trans.mark_as_read') }}">
                                                <i class="fas {{ $message->is_read ? 'fa-envelope-open' : 'fa-envelope' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('contact-messages.destroy', $message->id) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('main_trans.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ trans('main_trans.no_messages_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $messages->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
