@extends('admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4"> {{ trans('main_trans.ramadanoffers') }}</h3>
                <div class="text-right mb-3">
                    @can('create apartments')
                    <a href="{{ route('ramadanoffers.create') }}" class="btn-bid-now" style="color:white; cursor:pointer">
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





            <button type="button" class=" button x-small  " data-toggle="modal" data-target="#exampleModal">
                {{ trans('Counters_trans.add_Grade') }}
            </button>




                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="data-table">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>{{ trans('Counters_trans.hotel') }}</th>
                                <th>{{ trans('Counters_trans.pricepernight') }}</th>
                                <th>{{ trans('Counters_trans.numberofroom') }}</th>
                                <th>{{ trans('Counters_trans.title') }}</th>
                               <th>{{ trans('Counters_trans.breakfast_price') }}</th>
                               <th>{{ trans('Counters_trans.suhoor_price') }}</th>

                                <th>{{ trans('Counters_trans.image') }}</th>
                                <th>{{ trans('Counters_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($ramadanoffers as $ramadanoffer)

                                <tr>
                                    <td>{{ $ramadanoffer->id }}</td>
                                    <td>@if(App::getLocale() == 'ar')
                                        {{ $ramadanoffer->hotelname_ar }}
                                    @else
                                        {{ $ramadanoffer->hotelname_en }}
                                        @endif</td>
                                        <td>{{ $ramadanoffer->price }} / {{ $ramadanoffer->number_of_night }}</td>


                                        <td>@if(App::getLocale() == 'ar')
                                            {{ $ramadanoffer->roomtype_ar }}
                                        @else
                                            {{ $ramadanoffer->roomtype_en }}
                                        @endif</td>
                                  
                                  <td>@if(App::getLocale() == 'ar')
                                            {{ $ramadanoffer->title_ar }}
                                        @else
                                            {{ $ramadanoffer->title_en }}
                                        @endif</td>
                                  
                                   <td>{{ $ramadanoffer->breakfast_price }}</td>
                                   <td>{{ $ramadanoffer->suhoor_price }}</td>




                                    <td>
                                        <img src="{{ asset('ramadanoffer/' . $ramadanoffer->image) }}" width="50" height="50">
                                    </td>








                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $ramadanoffer->id }}"
                                            title="{{ trans('Counters_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $ramadanoffer->id }}"
                                            title="{{ trans('Counters_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>



                                </tr>

                                    <!-- edit_modal_Grade -->
                                    <div class="modal fade" id="edit{{ $ramadanoffer->id }}" tabindex="-1" role="dialog"
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
                                                     <form action="{{ route('ramadanoffers.update',$ramadanoffer->id) }}" method="post" enctype="multipart/form-data">
                                                        {{ method_field('patch') }}
                                                        @csrf

                                                        {{-- <div class="row">
                                                            <div class="col">
                                                                <label for="hotel_id"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.hotel') }}
                                                                    :</label>
                                                                <input id="hotel_id" type="text" name="hotel_id"
                                                                    class="form-control"
                                                                    value="{{ $ramadanoffer->hotel->hotel_id }}"
                                                                    required>
                                                            </div>
                                                        </div> --}}

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="hotelname_ar"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.hotelname_ar') }}
                                                                    :</label>
                                                                <input id="hotelname_ar" type="text" name="hotelname_ar"
                                                                    class="form-control"
                                                                    value="{{ $ramadanoffer->hotelname_ar }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $ramadanoffer->id }}">
                                                            </div>
                                                            <div class="col">
                                                                <label for="hotelname_en"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.hotelname_en') }}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $ramadanoffer->hotelname_en }}"
                                                                    name="hotelname_en" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="title_ar"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.title_ar') }}
                                                                    :</label>
                                                                <input id="title_ar" type="text" name="title_ar"
                                                                    class="form-control"
                                                                    value="{{ $ramadanoffer->title_ar }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $ramadanoffer->id }}">
                                                            </div>
                                                            <div class="col">
                                                                <label for="title_en"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.title_en') }}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $ramadanoffer->title_en }}"
                                                                    name="title_en" required>
                                                            </div>
                                                        </div>
                                                       
                                                       
                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="breakfast_price"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.breakfast_price') }}
                                                                    :</label>
                                                                <input id="breakfast_price" type="text" name="breakfast_price"
                                                                    class="form-control"
                                                                    value="{{ $ramadanoffer->breakfast_price }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $ramadanoffer->id }}">
                                                            </div>
                                                            <div class="col">
                                                                <label for="suhoor_price"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.suhoor_price') }}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $ramadanoffer->suhoor_price }}"
                                                                    name="suhoor_price" required>
                                                            </div>
                                                        </div>
                                                       
                                                       
                                                       

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="price"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.price') }}
                                                                    :</label>
                                                                <input id="price" type="text" name="price"
                                                                    class="form-control"
                                                                    value="{{ $ramadanoffer->price }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $ramadanoffer->id }}">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="number_of_night"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.number_of_night') }}
                                                                    :</label>
                                                                <input id="number_of_night" type="text" name="number_of_night"
                                                                    class="form-control"
                                                                    value="{{ $ramadanoffer->number_of_night }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $ramadanoffer->id }}">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="roomtype_ar"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.roomtype_ar') }}
                                                                    :</label>
                                                                <input id="roomtype_ar" type="text" name="roomtype_ar"
                                                                    class="form-control"
                                                                    value="{{ $ramadanoffer->roomtype_ar }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $ramadanoffer->id }}">
                                                            </div>
                                                            <div class="col">
                                                                <label for="roomtype_en"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.roomtype_en') }}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $ramadanoffer->roomtype_en }}"
                                                                    name="roomtype_en" required>
                                                            </div>
                                                        </div>

                                                        <br>

                                                            <div class="div_design">
                                                                <label for="">current image :</label>
                                                                <img src="{{ asset('ramadanoffer/' . $ramadanoffer->image) }}" width="50" height="50">
                                                            </div>
                                                            <br>
                                                            <div class="div_design">
                                                                <label for="">chance image :</label>
                                                                <input type="file" name="image" >
                                                            </div>




                                                        <br><br>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ trans('Counters_trans.Close') }}</button>
                                                            <button type="submit"
                                                                class="btn btn-success">{{ trans('Counters_trans.Submit') }}</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
    {{-- //////////////////////////////////// --}}
                                    <!-- delete_modal_Grade -->
                                    <div class="modal fade" id="delete{{ $ramadanoffer->id }}" tabindex="-1" role="dialog"
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
                                                    <form action="{{route('ramadanoffers.destroy',$ramadanoffer->id)}}" method="post">
                                                        {{method_field('Delete')}}
                                                        @csrf
                                                        {{ trans('Counters_trans.Warning_Grade') }}
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $ramadanoffer->id }}">
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
                        {{ $ramadanoffers->links('pagination::bootstrap-5') }}


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
                        <form action="{{ route('ramadanoffers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- <div class="form-group">
                                <label for="hotel_id">{{ trans('Counters_trans.hotel') }}</label>
                                <select name="hotel_id" class="form-control form-select">
                                    <option value="">{{ trans('Counters_trans.hotel') }}</option>
                                    @foreach ($hotels as $category)
                                        <option value="{{ $category->id }}">
                                            {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="row">
                                <div class="col">
                                    <label for="hotelname_ar" class="mr-sm-2">{{ trans('Counters_trans.hotelname_ar') }}
                                        :</label>
                                    <input id="hotelname_ar" type="text" name="hotelname_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="hotelname_en" class="mr-sm-2">{{ trans('Counters_trans.hotelname_en') }}
                                        :</label>
                                    <input id="hotelname_en" type="text" class="form-control" name="hotelname_en" required>
                                </div>
                            </div>
                          
                           <div class="row">
                                <div class="col">
                                    <label for="breakfast_price" class="mr-sm-2">{{ trans('Counters_trans.breakfast_price') }}
                                        :</label>
                                    <input id="breakfast_price" type="text" name="breakfast_price" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="suhoor_price" class="mr-sm-2">{{ trans('Counters_trans.suhoor_price') }}
                                        :</label>
                                    <input id="suhoor_price" type="text" class="form-control" name="suhoor_price" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="price" class="mr-sm-2">{{ trans('Counters_trans.price') }}
                                        :</label>
                                    <input id="price" type="text" name="price" class="form-control">
                                </div>

                                <div class="col">
                                    <label for="number_of_night" class="mr-sm-2">{{ trans('Counters_trans.number_of_night') }}
                                        :</label>
                                    <input id="number_of_night" type="text" name="number_of_night" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="roomtype_ar" class="mr-sm-2">{{ trans('Counters_trans.roomtype_ar') }}
                                        :</label>
                                    <input id="roomtype_ar" type="text" name="roomtype_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="roomtype_en" class="mr-sm-2">{{ trans('Counters_trans.roomtype_en') }}
                                        :</label>
                                    <input id="roomtype_en" type="text" class="form-control" name="roomtype_en" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="title_ar" class="mr-sm-2">{{ trans('Counters_trans.title_ar') }}
                                        :</label>
                                    <input id="title_ar" type="text" name="title_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="title_en" class="mr-sm-2">{{ trans('Counters_trans.title_en') }}
                                        :</label>
                                    <input id="title_en" type="text" class="form-control" name="title_en" required>
                                </div>
                            </div>





                            {{-- <div class="row">
                                <div class="col">
                                    <label for="number_of_night" class="mr-sm-2">{{ trans('Counters_trans.number_of_night') }}
                                        :</label>
                                    <input id="number_of_night" type="text" name="number_of_night" class="form-control">
                                </div>
                            </div> --}}


<br>
                            <div class="row">
                                <div class="div_design">
                                    <label for="image">Image:</label>
                                    <input type="file" name="image" multiple required>
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



