@extends('admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4"> {{ trans('main_trans.avilablerooms') }}</h3>
                <div class="text-right mb-3">
                    @can('create apartments')
                    <a href="{{ route('avilablerooms.create') }}" class="btn-bid-now" style="color:white; cursor:pointer">
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
                                <th>{{ trans('Counters_trans.availableroom_type') }}</th>
                                <th>{{ trans('Counters_trans.availableroom_price') }}</th>
                                <th>{{ trans('Counters_trans.hotel') }}</th>
                                <th>{{ trans('Counters_trans.availableroom_image') }}</th>

                                <th>{{ trans('Counters_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($avilablerooms as $avilableroom)

                                <tr>
                                    <td>{{ $avilableroom->id }}</td>
                                    <td>{{ App::getLocale() == 'ar' ? $avilableroom->availableroom_type_ar : $avilableroom->availableroom_type_en }}</td>
                                    <td>{{ $avilableroom->availableroom_price }}</td>
                                    <td>{{ App::getLocale() == 'ar' ? $avilableroom->hotel->name_ar : $avilableroom->hotel->name_en }}</td>
                                    <td>
                                        @if ($avilableroom->availableroom_image)
                                            @php
                                                $images = json_decode($avilableroom->availableroom_image);

                                                $totalImages = count($images);
                                            @endphp

                                            @foreach ($images as $index => $image)
                                                @if ($index < 2)
                                                    <img src="{{ asset('availableroom_image/' . $image) }}" width="50" height="50"
                                                        style="margin-right: 5px;">
                                                @endif
                                            @endforeach

                                            @if ($totalImages > 2)
                                                <span>+{{ $totalImages - 2 }} more</span>
                                            @endif
                                        @endif
                                    </td>






                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $avilableroom->id }}"
                                            title="{{ trans('Counters_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $avilableroom->id }}"
                                            title="{{ trans('Counters_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>



                                </tr>

                                    <!-- edit_modal_Grade -->
                                    <div class="modal fade" id="edit{{ $avilableroom->id }}" tabindex="-1" role="dialog"
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
                                                     <form action="{{ route('avilablerooms.update',$avilableroom->id) }}" method="post" enctype="multipart/form-data">
                                                        {{ method_field('patch') }}
                                                        @csrf

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="availableroom_type_ar"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.availableroom_type_ar') }}
                                                                    :</label>
                                                                <input id="availableroom_type_ar" type="text" name="availableroom_type_ar"
                                                                    class="form-control"
                                                                    value="{{ $avilableroom->availableroom_type_ar }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $avilableroom->id }}">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="availableroom_type_en"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.availableroom_type_en') }}
                                                                    :</label>
                                                                <input id="availableroom_type_en" type="text" name="availableroom_type_en"
                                                                    class="form-control"
                                                                    value="{{ $avilableroom->availableroom_type_en }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $avilableroom->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="availableroom_price"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.availableroom_price') }}
                                                                    :</label>
                                                                <input id="availableroom_price" type="text" name="availableroom_price"
                                                                    class="form-control"
                                                                    value="{{ $avilableroom->availableroom_price }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $avilableroom->id }}">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="hotel_id">{{ trans('Counters_trans.hotel') }}</label>
                                                            <select name="hotel_id" id="hotel_id" class="form-control form-select">
                                                                <option value="">{{ trans('Counters_trans.hotel') }}</option>
                                                                @foreach ($hotels as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        {{ old('hotel_id', $avilableroom->hotel_id ?? '') == $category->id ? 'selected' : '' }}>
                                                                        {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="div_design">
                                                            <label for="">Current Images:</label>
                                                            <div class="d-flex flex-wrap">
                                                                @foreach (json_decode($avilableroom->availableroom_image) as $image)
                                                                    <div class="m-2">
                                                                        <img src="{{ asset('availableroom_image/' . $image) }}" width="50" height="50" class="rounded">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="div_design">
                                                            <label for="">Change Images:</label>
                                                            <input type="file" name="availableroom_image[]" multiple>
                                                            <small class="text-muted">You can upload multiple images.</small>
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
                                    <div class="modal fade" id="delete{{ $avilableroom->id }}" tabindex="-1" role="dialog"
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
                                                    <form action="{{route('avilablerooms.destroy',$avilableroom->id)}}" method="post">
                                                        {{method_field('Delete')}}
                                                        @csrf
                                                        {{ trans('Counters_trans.Warning_Grade') }}
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $avilableroom->id }}">
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
                        {{ $avilablerooms->links('pagination::bootstrap-5') }}


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
                        <form action="{{ route('avilablerooms.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- <div class="row">
                                <div class="col">
                                    <label for="availableroom_type_ar" class="mr-sm-2">{{ trans('Counters_trans.availableroom_type_ar') }}
                                        :</label>
                                    <input id="availableroom_type_ar" type="text" name="availableroom_type_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="availableroom_type_en" class="mr-sm-2">{{ trans('Counters_trans.availableroom_type_en') }}
                                        :</label>
                                    <input id="availableroom_type_en" type="text" class="form-control" name="availableroom_type_en" required>
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col">
                                    <label for="availableroom_type_ar"
                                        class="mr-sm-2">{{ trans('Counters_trans.availableroom_type_ar') }}
                                        :</label>
                                    <input id="availableroom_type_ar" type="text" name="availableroom_type_ar" class="form-control"
                                        required>
                                </div>
                                <div class="col">
                                    <label for="availableroom_type_en"
                                        class="mr-sm-2">{{ trans('Counters_trans.availableroom_type_en') }}
                                        :</label>
                                    <input type="text" class="form-control" name="availableroom_type_en" required>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <label for="availableroom_price" class="mr-sm-2">{{ trans('Counters_trans.availableroom_price') }}
                                        :</label>
                                    <input id="availableroom_price" type="text" name="availableroom_price" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="hotel_id">{{ trans('Counters_trans.hotel') }}</label>
                                <select name="hotel_id" class="form-control form-select">
                                    <option value="">{{ trans('Counters_trans.hotel') }}</option>
                                    @foreach ($hotels as $category)
                                        <option value="{{ $category->id }}">
                                            {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
<br>
                            <div class="mb-3">
                                <label for="availableroom_image">Images:</label>
                                <input type="file" name="availableroom_image[]" multiple required>
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



