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
                                <a href="{{ route('newsletter.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> {{ trans('main_trans.add') }}
                                </a>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- أزرار التصدير --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('newsletter.export') }}" class="btn btn-success">
                                                <i class="fas fa-file-excel me-1"></i>
                                                {{ trans('main_trans.export_to_excel') }}
                                            </a>
                                        </div>
                                        <div>
                                            <span class="badge bg-info">{{ $newsletters->total() }} {{ trans('main_trans.total_newsletters') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('main_trans.id') }}</th>
                                            <th>{{ trans('main_trans.name') }}</th>
                                            <th>{{ trans('main_trans.email') }}</th>
                                            <th>{{ trans('main_trans.phone') }}</th>
                                            <th>{{ trans('main_trans.message') }}</th>
                                            <th>{{ trans('main_trans.processes') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($newsletters as $newsletter)
                                            <tr>
                                                <td>{{ $newsletter->id }}</td>
                                                <td>{{ $newsletter->name ?? '-' }}</td>
                                                <td>{{ $newsletter->email ?? '-' }}</td>
                                                <td>{{ $newsletter->phone ?? '-' }}</td>
                                                <td>
                                                    @if($newsletter->message)
                                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $newsletter->message }}">
                                                            {{ Str::limit($newsletter->message, 50) }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('newsletter.show', $newsletter->id) }}" class="btn btn-info btn-sm">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('newsletter.edit', $newsletter->id) }}" class="btn btn-warning btn-sm">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form action="{{ route('newsletter.destroy', $newsletter->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">{{ trans('main_trans.no_data_found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination hidden as requested --}}
                            {{-- @if($newsletters->hasPages())
                                <div class="d-flex justify-content-center">
                                    {{ $newsletters->links() }}
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
