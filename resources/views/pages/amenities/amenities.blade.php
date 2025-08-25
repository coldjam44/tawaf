@extends('admin.layouts.app')
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ trans('main_trans.amenitys') }}</h2>
        <a href="{{ route('amenitys.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>  {{ trans('Counters_trans.add_Grade') }}
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-striped table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>{{ trans('Counters_trans.Name') }}</th>
                        <th>{{ trans('Counters_trans.image') }}</th>

                        <th>{{ trans('Counters_trans.Processes') }}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($amenitys as $amenity)
                        <tr>
                            <td>{{ $amenity->id }}</td>
                            <td>{{ App::getLocale() == 'ar' ? $amenity->name_ar : $amenity->name_en }}</td>

                            <td>
                                <img src="{{ asset('amenity/' . $amenity->image) }}" width="50" height="50">
                            </td>

                            <td>
                                <a href="{{ route('amenitys.edit', $amenity->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> {{ trans('Counters_trans.Edit') }}
                                </a>
                                <form action="{{ route('amenitys.destroy', $amenity->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ trans('Counters_trans.confirm_delete') }}')">
                                        <i class="fas fa-trash"></i> {{ trans('Counters_trans.Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">{{ trans('Counters_trans.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $amenitys->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
