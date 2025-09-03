@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.property_management') }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('properties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add_property') }}
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

                {{-- جدول العقارات --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.id') }}</th>
                                <th>{{ trans('main_trans.project') }}</th>
                                <th>{{ trans('main_trans.price') }}</th>
                                <th>{{ trans('main_trans.rooms') }}</th>
                                <th>{{ trans('main_trans.area') }}</th>
                                <th>{{ trans('main_trans.location') }}</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($properties as $property)
                            <tr>
                                <td>{{ $property->propertyid }}</td>
                                <td>
                                    @if($property->project)
                                        {{ $property->project->getTitle() }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $property->getFormattedPriceAttribute() }}</td>
                                <td>{{ $property->propertyrooms }}</td>
                                <td>{{ $property->getFormattedAreaAttribute() }}</td>
                                <td>{{ $property->propertyloaction }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('properties.show', $property->propertyid) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('properties.edit', $property->propertyid) }}" class="btn btn-warning btn-sm" title="{{ trans('main_trans.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('properties.destroy', $property->propertyid) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('main_trans.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ trans('main_trans.no_properties_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $properties->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
