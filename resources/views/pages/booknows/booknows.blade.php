@extends('admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4"> {{ trans('main_trans.booknow') }}</h3>
                <div class="text-right mb-3">
                    @can('create apartments')
                    <a href="{{ route('booknows.create') }}" class="btn-bid-now" style="color:white; cursor:pointer">
                        <i class="fas fa-plus-circle"></i> {{ trans('web.add') }}
                    </a>
                    @endcan
                </div>

            </div>

            <div class="card-body">


                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif





           




                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="data-table">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>{{ trans('Counters_trans.fullname') }}</th>
                                <th>{{ trans('Counters_trans.phonenumber') }}</th>
                                <th>{{ trans('Counters_trans.email') }}</th>
                                <th>{{ trans('Counters_trans.checkin') }}</th>
                                <th>{{ trans('Counters_trans.checkout') }}</th>
                                <th>{{ trans('Counters_trans.room') }}</th>
                                                              <th>{{ trans('Counters_trans.offer') }}</th>

                                <th>{{ trans('Counters_trans.numberofroom') }}</th>
                                <th>{{ trans('Counters_trans.numberofadult') }}</th>
                                <th>{{ trans('Counters_trans.numberofchild') }}</th>
                                <th>{{ trans('Counters_trans.age_child') }}</th>
                                <th>{{ trans('Counters_trans.totalprice') }}</th>
                                <th>{{ trans('Counters_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($booknows as $booknow)

                                <tr>
                                    <td>{{ $booknow->id }}</td>
                                    <td>{{ $booknow->fullname }}</td>
                                    <td>{{ $booknow->phonenumber }}</td>
                                    <td>{{ $booknow->specialrequest }}</td>
                                    <td>{{ App::getLocale() == 'ar' ? $booknow->check_in_ar : $booknow->check_in_en }}</td>
                                            <td>{{ App::getLocale() == 'ar' ? $booknow->check_out_ar : $booknow->check_out_en }}</td>
<td>
    @if ($booknow->room)
        {{ App::getLocale() == 'ar' ? $booknow->room->availableroom_type_ar : $booknow->room->availableroom_type_en }}
    @else
        <!-- يمكنك عرض رسالة أو قيمة بديلة هنا إذا كان الكائن `room` غير موجود -->
        {{ __('Room not available') }}
    @endif
</td>
                                  <td>
    @if ($booknow->offer)
        {{ App::getLocale() == 'ar' ? $booknow->offer->roomtype_ar : $booknow->offer->roomtype_en }}
    @else
        <!-- يمكنك عرض رسالة أو قيمة بديلة هنا إذا كان الكائن `room` غير موجود -->
        {{ __('Room type not available') }}
    @endif
</td>
                                  

                                            <td>{{ $booknow->numberofroom }}</td>
                                            <td>{{ $booknow->numberofadult }}</td>
                                            <td>{{ $booknow->numberofchild }}</td>
                                            <td>{{ $booknow->age_child }}</td>
                                            <td>{{ $booknow->totalprice }}</td>




                                    <td>
                                        {{-- <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $booknow->id }}"
                                            title="{{ trans('Counters_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button> --}}
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $booknow->id }}"
                                            title="{{ trans('Counters_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>



                                </tr>

                                    <!-- edit_modal_Grade -->
                                    <div class="modal fade" id="edit{{ $booknow->id }}" tabindex="-1" role="dialog"
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
                                                    <!-- add_form -->
                                                    {{-- <form action="{{ route('places.update',$place->id) }}" method="post" enctype="multipart/form-data">
                                                        {{ method_field('patch') }}
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="name_ar"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.name_ar') }}
                                                                    :</label>
                                                                <input id="name_ar" type="text" name="name_ar"
                                                                    class="form-control"
                                                                    value="{{ $place->name_ar }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $place->id }}">
                                                            </div>
                                                            <div class="col">
                                                                <label for="name_en"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.name_en') }}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $place->name_en }}"
                                                                    name="name_en" required>
                                                            </div>
                                                        </div>




                                                        <br><br>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ trans('Counters_trans.Close') }}</button>
                                                            <button type="submit"
                                                                class="btn btn-success">{{ trans('Counters_trans.Submit') }}</button>
                                                        </div>
                                                    </form> --}}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
    {{-- //////////////////////////////////// --}}
                                    <!-- delete_modal_Grade -->
                                    <div class="modal fade" id="delete{{ $booknow->id }}" tabindex="-1" role="dialog"
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
                                                    <form action="{{route('booknows.destroy',$booknow->id)}}" method="post">
                                                        {{method_field('Delete')}}
                                                        @csrf
                                                        {{ trans('Counters_trans.Warning_Grade') }}
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $booknow->id }}">
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
                        {{ $booknows->links('pagination::bootstrap-5') }}


                    </div>
                </div>
            </div>
        </div>


        <!-- add_modal_Grade -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <!-- add_form -->
                        <form action="{{ route('booknows.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fullname" class="form-label">{{ trans('Counters_trans.fullname') }}</label>
                                    <input type="text" name="fullname" class="form-control" id="fullname" placeholder="{{ trans('Counters_trans.fullname') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phonenumber" class="form-label">{{ trans('Counters_trans.phonenumber') }}</label>
                                    <input type="text" name="phonenumber" class="form-control" id="phonenumber" placeholder="{{ trans('Counters_trans.phonenumber') }}" required>
                                </div>

                            </div>
                            {{-- <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phonenumber" class="form-label">{{ trans('Counters_trans.phonenumber') }}</label>
                                    <input type="text" name="phonenumber" class="form-control" id="phonenumber" placeholder="{{ trans('Counters_trans.phonenumber') }}" required>
                                </div>

                            </div> --}}

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="specialrequest" class="form-label">{{ trans('Counters_trans.email') }}</label>
                                    <input type="text" name="specialrequest" class="form-control" id="specialrequest" placeholder="{{ trans('Counters_trans.email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="numberofroom" class="form-label">{{ trans('Counters_trans.numberofrooms') }}</label>
                                    <input type="text" name="numberofroom" class="form-control" id="numberofroom" placeholder="{{ trans('Counters_trans.numberofrooms') }}">
                                </div>

                            </div>


                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="check_in_ar" class="form-label">{{ trans('Counters_trans.check_in_ar') }}</label>
                                    <input type="text" name="check_in_ar" class="form-control" id="check_in_ar" placeholder="{{ trans('Counters_trans.check_in_ar') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="check_in_en" class="form-label">{{ trans('Counters_trans.check_in_en') }}</label>
                                    <input type="text" name="check_in_en" class="form-control" id="check_in_en" placeholder="{{ trans('Counters_trans.check_in_en') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="check_out_ar" class="form-label">{{ trans('Counters_trans.check_out_ar') }}</label>
                                    <input type="text" name="check_out_ar" class="form-control" id="check_out_ar" placeholder="{{ trans('Counters_trans.check_out_ar') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="check_out_en" class="form-label">{{ trans('Counters_trans.check_out_en') }}</label>
                                    <input type="text" name="check_out_en" class="form-control" id="check_out_en" placeholder="{{ trans('Counters_trans.check_out_en') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="room_id">{{ trans('Counters_trans.room') }}</label>
                                <select name="room_id" class="form-control form-select">
                                    <option value="">{{ trans('Counters_trans.room') }}</option>
                                    @foreach ($rooms as $category)
                                        <option value="{{ $category->id }}">
                                            {{ App::getLocale() === 'ar' ? $category->availableroom_type_ar : $category->availableroom_type_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                          
                          <div class="form-group">
                                <label for="offer_id">{{ trans('Counters_trans.offer') }}</label>
                                <select name="offer_id" class="form-control form-select">
                                    <option value="">{{ trans('Counters_trans.offer') }}</option>
                                    @foreach ($offers as $category)
                                        <option value="{{ $category->id }}">
                                            {{ App::getLocale() === 'ar' ? $category->roomtype_ar : $category->roomtype_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>




                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="numberofadult" class="form-label">{{ trans('Counters_trans.numberofadults') }}</label>
                                    <input type="text" name="numberofadult" class="form-control" id="numberofadult" placeholder="{{ trans('Counters_trans.numberofadults') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="numberofchild" class="form-label">{{ trans('Counters_trans.numberofchilds') }}</label>
                                    <input type="text" name="numberofchild" class="form-control" id="numberofchild" placeholder="{{ trans('Counters_trans.numberofchilds') }}">
                                </div>

                            </div>










                <div class="row">
                    <div class="col">
                        <label for="age_child" class="mr-sm-2">{{ trans('Counters_trans.age_child') }} :</label>
                        <input id="age_child" type="text" name="age_child[]" class="form-control" placeholder="{{ trans('Counters_trans.age_child') }}">
                    </div>
                    <div class="col">
                        <label for="totalprice" class="mr-sm-2">{{ trans('Counters_trans.totalprice') }} :</label>
                        <input id="totalprice" type="text" name="totalprice" class="form-control" placeholder="{{ trans('Counters_trans.totalprice') }}">
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


@endsection



