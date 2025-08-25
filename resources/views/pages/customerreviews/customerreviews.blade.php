@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ trans('main_trans.customerreview') }}</h2>
        <a href="{{ route('customerreviews.create') }}" class="btn btn-primary">
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
                                <th>{{ trans('Counters_trans.description') }}</th>
                                <th>{{ trans('Counters_trans.comment') }}</th>
                                <th>{{ trans('Counters_trans.position') }}</th>
                                <th>{{ trans('Counters_trans.rate') }}</th>
                                <th>{{ trans('Counters_trans.Processes') }}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($customerreviews as $customerreview)
                    <tr>
                        <td>{{ $customerreview->id }}</td>
                        <td>@if(App::getLocale() == 'ar')
                            {{ $customerreview->name_ar }}
                        @else
                            {{ $customerreview->name_en }}
                        @endif</td>
                        <td>@if(App::getLocale() == 'ar')
                            {{ $customerreview->description_ar }}
                        @else
                            {{ $customerreview->description_en }}
                        @endif</td>

                        <td>@if(App::getLocale() == 'ar')
                            {{ $customerreview->comment_ar }}
                        @else
                            {{ $customerreview->comment_en }}
                        @endif</td>

                        <td>@if(App::getLocale() == 'ar')
                            {{ $customerreview->possition_ar }}
                        @else
                            {{ $customerreview->possition_en }}
                        @endif</td>

                        <td>{{ $customerreview->rate }}</td>
                            <td>
                                <a href="{{ route('customerreviews.edit', $customerreview->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> {{ trans('Counters_trans.Edit') }}
                                </a>
                                <form action="{{ route('customerreviews.destroy', $customerreview->id) }}" method="POST" style="display: inline;">
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
                {{ $customerreviews->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
