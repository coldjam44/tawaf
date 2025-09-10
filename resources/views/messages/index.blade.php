@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ trans('main_trans.messages') }}</h4>
                    <div>
                        <a href="{{ route('messages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            {{ trans('main_trans.add_message') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ trans('main_trans.id') }}</th>
                                        <th>{{ trans('main_trans.image') }}</th>
                                        <th>{{ trans('main_trans.person_name') }}</th>
                                        <th>{{ trans('main_trans.person_position') }}</th>
                                        <th>{{ trans('main_trans.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td>{{ $message->id }}</td>
                                            <td>
                                                @if($message->image_path)
                                                    <img src="{{ asset('messagesfiles/' . $message->image_path) }}" 
                                                         alt="{{ $message->name_en }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">{{ trans('main_trans.no_image') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $message->name_en }}</strong><br>
                                                    <small class="text-muted">{{ $message->name_ar }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $message->position_en }}</strong><br>
                                                    <small class="text-muted">{{ $message->position_ar }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('messages.show', $message->id) }}" class="btn btn-sm btn-info" title="{{ trans('main_trans.actions_view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('messages.edit', $message->id) }}" class="btn btn-sm btn-warning" title="{{ trans('main_trans.actions_edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('messages.destroy', $message->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ trans('main_trans.actions_delete') }}">
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
                            <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ trans('main_trans.no_messages_found') }}</h5>
                            <a href="{{ route('messages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                {{ trans('main_trans.add_message') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
