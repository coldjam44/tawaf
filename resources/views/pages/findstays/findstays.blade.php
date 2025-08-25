@extends('admin.layouts.app')


{{-- @section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4"> {{ trans('main_trans.findstays') }}</h3>
                <div class="text-right mb-3">
                    @can('create apartments')
                    <a href="{{ route('findstays.create') }}" class="btn-bid-now" style="color:white; cursor:pointer">
                        <i class="fas fa-plus-circle"></i> {{ trans('web.add') }}
                    </a>
                    @endcan
                </div>

            </div>

            <div class="card-body"> --}}
                @section('content')
                <div class="container mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2>{{ trans('main_trans.findstays') }}</h2>
                        <a href="{{ route('findstays.create') }}" class="btn btn-primary">
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
                                        <th>{{ trans('Counters_trans.checkin') }}</th>
                                        <th>{{ trans('Counters_trans.checkout') }}</th>
                                        <th>{{ trans('Counters_trans.place') }}</th>
                                        <th>{{ trans('Counters_trans.numberofroom') }}</th>
                                        <th>{{ trans('Counters_trans.numberofadult') }}</th>
                                        <th>{{ trans('Counters_trans.numberofchild') }}</th>
                                                                              <th>{{ trans('Counters_trans.age_child') }}</th>


                                        <th>{{ trans('Counters_trans.Processes') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($findstays as $findstay)
                                        <tr>
                                            <td>{{ $findstay->id }}</td>
                                            <td>{{ App::getLocale() == 'ar' ? $findstay->check_in_ar : $findstay->check_in_en }}</td>
                                            <td>{{ App::getLocale() == 'ar' ? $findstay->check_out_ar : $findstay->check_out_en }}</td>
                                            <td>{{ App::getLocale() == 'ar' ? $findstay->place->name_ar : $findstay->place->name_en }}</td>
                                            <td>{{ $findstay->numberofroom }}</td>
                                            <td>{{ $findstay->numberofadult }}</td>
                                            <td>{{ $findstay->numberofchild }}</td>
                                                                                      <td>{{ $findstay->age_child }}</td>

                                            <td>
                                                <a href="{{ route('findstays.edit', $findstay->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i> {{ trans('Counters_trans.Edit') }}
                                                </a>
                                                <form action="{{ route('findstays.destroy', $findstay->id) }}" method="POST" style="display: inline;">
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
                                            <td colspan="8">{{ trans('Counters_trans.no_data') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $findstays->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endsection





                {{-- <div class="table-responsive">
                    <table class="table table-bordered data-table" id="data-table">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>{{ trans('Counters_trans.checkin') }}</th>
                                <th>{{ trans('Counters_trans.checkout') }}</th>
                                <th>{{ trans('Counters_trans.promocode') }}</th>
                                <th>{{ trans('Counters_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($findstays as $findstay)

                                <tr>
                                    <td>{{ $findstay->id }}</td>
                                    <td>@if(App::getLocale() == 'ar')
                                        {{ $findstay->check_in_ar }}
                                    @else
                                        {{ $findstay->check_in_en }}
                                    @endif</td>
                                    <td>@if(App::getLocale() == 'ar')
                                        {{ $findstay->check_out_ar }}
                                    @else
                                        {{ $findstay->check_out_en }}
                                    @endif</td>


                                    <td>{{ $findstay->promo_code }}</td>







                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $findstay->id }}"
                                            title="{{ trans('Counters_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $findstay->id }}"
                                            title="{{ trans('Counters_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>



                                </tr>

                                    <div class="modal fade" id="edit{{ $findstay->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                                                        id="exampleModalLabel">
                                                        {{ trans('Counters_trans.edit_Grade') }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">


                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="modal fade" id="delete{{ $findstay->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                                                        id="exampleModalLabel">
                                                        {{ trans('Counters_trans.delete_Grade') }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('findstays.destroy',$findstay->id)}}" method="post">
                                                        {{method_field('Delete')}}
                                                        @csrf
                                                        {{ trans('Counters_trans.Warning_Grade') }}
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $findstay->id }}">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ trans('Counters_trans.Close') }}</button>
                                                            <button type="submit"
                                                                    class="btn btn-danger">{{ trans('Counters_trans.Submit') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                        </table>
                        {{ $findstays->links('pagination::bootstrap-5') }}


                    </div>
                </div>
            </div>
        </div> --}}


        {{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                            {{ trans('Counters_trans.add_Grade') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('findstays.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <label for="check_in_ar" class="mr-sm-2">{{ trans('Counters_trans.check_in_ar') }}
                                        :</label>
                                    <input id="check_in_ar" type="text" name="check_in_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="check_in_en" class="mr-sm-2">{{ trans('Counters_trans.check_in_en') }}
                                        :</label>
                                    <input id="check_in_en" type="text" class="form-control" name="check_in_en" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="check_out_ar" class="mr-sm-2">{{ trans('Counters_trans.check_out_ar') }}
                                        :</label>
                                    <input id="check_out_ar" type="text" name="check_out_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="check_out_en" class="mr-sm-2">{{ trans('Counters_trans.check_out_en') }}
                                        :</label>
                                    <input id="check_out_en" type="text" class="form-control" name="check_out_en" required>
                                </div>
                            </div>










                            <br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ trans('Counters_trans.Close') }}</button>
                        <button type="submit" class="btn btn-success">{{ trans('Counters_trans.Submit') }}</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>

    </div>


@endsection --}}



