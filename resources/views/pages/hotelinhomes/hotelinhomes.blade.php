@extends('admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4"> {{ trans('main_trans.hotelinhome') }}</h3>
                <div class="text-right mb-3">
                    @can('create apartments')
                    <a href="{{ route('hotelinhomes.create') }}" class="btn-bid-now" style="color:white; cursor:pointer">
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
                                <th>{{ trans('Counters_trans.Name') }}</th>
                                <th>{{ trans('Counters_trans.description') }}</th>
                                <th>{{ trans('Counters_trans.hotel') }}</th>
                                <th>{{ trans('Counters_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($hotelinhomes as $hotelinhome)

                                <tr>
                                    <td>{{ $hotelinhome->id }}</td>
                                    <td>@if(App::getLocale() == 'ar')
                                        {{ $hotelinhome->title_ar }}
                                    @else
                                        {{ $hotelinhome->title_en }}
                                    @endif</td>
                                    <td>@if(App::getLocale() == 'ar')
                                        {{ $hotelinhome->description_ar }}
                                    @else
                                        {{ $hotelinhome->description_en }}
                                    @endif</td>

                                    {{-- <td>{{ App::getLocale() == 'ar' ? $hotelinhome->hotel->name_ar : $hotelinhome->hotel->name_en }}</td> --}}

                                    <td>
                                        @if ($hotelinhome->hotels->isNotEmpty())
                                            {{ $hotelinhome->hotels->map(fn($hotel) => App::getLocale() == 'ar' ? $hotel->name_ar : $hotel->name_en)->implode(' , ') }}
                                        @else
                                            {{ __('No hotels assigned') }}
                                        @endif
                                    </td>




                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $hotelinhome->id }}"
                                            title="{{ trans('Counters_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $hotelinhome->id }}"
                                            title="{{ trans('Counters_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>



                                </tr>

                                    <!-- edit_modal_Grade -->
                                    <div class="modal fade" id="edit{{ $hotelinhome->id }}" tabindex="-1" role="dialog"
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
                                                    <form action="{{ route('hotelinhomes.update',$hotelinhome->id) }}" method="post" enctype="multipart/form-data">
                                                        {{ method_field('patch') }}
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="title_ar"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.stage_name_ar') }}
                                                                    :</label>
                                                                <input id="title_ar" type="text" name="title_ar"
                                                                    class="form-control"
                                                                    value="{{ $hotelinhome->title_ar }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $hotelinhome->id }}">
                                                            </div>
                                                            <div class="col">
                                                                <label for="title_en"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.stage_name_en') }}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $hotelinhome->title_en }}"
                                                                    name="title_en" required>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="description_ar"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.description') }}
                                                                    :</label>
                                                                <input id="description_ar" type="text" name="description_ar"
                                                                    class="form-control"
                                                                    value="{{ $hotelinhome->description_ar }}"
                                                                    required>
                                                                <input id="id" type="hidden" name="id"
                                                                    class="form-control" value="{{ $hotelinhome->id }}">
                                                            </div>
                                                            <div class="col">
                                                                <label for="description_en"
                                                                    class="mr-sm-2">{{ trans('Counters_trans.description') }}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $hotelinhome->description_en }}"
                                                                    name="description_en" required>
                                                            </div>
                                                        </div>

                                                        <br><br>

                                                        <div class="form-group dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                {{ trans('Counters_trans.hotel') }}
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                @foreach ($hotels as $category)
                                                                    <li>
                                                                        <label class="dropdown-item">
                                                                            <input
                                                                                type="checkbox"
                                                                                name="hotel_ids[]"
                                                                                value="{{ $category->id }}"
                                                                                {{ $hotelinhome->hotels->contains($category->id) ? 'checked' : '' }}>
                                                                            {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                                                        </label>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>

                                                        <script>
                                                            // منع إغلاق القائمة عند النقر داخلها
                                                            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                                                                menu.addEventListener('click', function (e) {
                                                                    e.stopPropagation();
                                                                });
                                                            });
                                                        </script>



                                                        {{-- <div class="form-group">
                                                            <label for="hotel_id[]">{{ trans('Counters_trans.hotel') }}</label>
                                                            <select name="hotel_id[]" class="form-control form-select" id="amenity-select" multiple>
                                                                @foreach ($hotels as $category)
                                                                    <option value="{{ $category->id }}"
                                                                            {{ in_array($category->id, old('hotel_id', $hotelinhome->hotel->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                                        {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <script>
                                                            $(document).ready(function() {
                                                                $('#amenity-select').select2({
                                                                    placeholder: '{{ trans('Counters_trans.hotel') }}',
                                                                    allowClear: true,
                                                                    closeOnSelect: false
                                                                });
                                                            });
                                                        </script> --}}

                                                        <br>



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
                                    <div class="modal fade" id="delete{{ $hotelinhome->id }}" tabindex="-1" role="dialog"
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
                                                    <form action="{{route('hotelinhomes.destroy',$hotelinhome->id)}}" method="post">
                                                        {{method_field('Delete')}}
                                                        @csrf
                                                        {{ trans('Counters_trans.Warning_Grade') }}
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $hotelinhome->id }}">
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
                        {{ $hotelinhomes->links('pagination::bootstrap-5') }}


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
                        <form action="{{ route('hotelinhomes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <label for="title_ar" class="mr-sm-2">{{ trans('Counters_trans.stage_name_ar') }}
                                        :</label>
                                    <input id="title_ar" type="text" name="title_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="title_en" class="mr-sm-2">{{ trans('Counters_trans.stage_name_en') }}
                                        :</label>
                                    <input id="title_en" type="text" class="form-control" name="title_en" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="description_ar" class="mr-sm-2">{{ trans('Counters_trans.description') }}
                                        :</label>
                                    <input id="description_ar" type="text" name="description_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="description_en" class="mr-sm-2">{{ trans('Counters_trans.description') }}
                                        :</label>
                                    <input id="description_en" type="text" class="form-control" name="description_en" required>
                                </div>
                            </div>

<br><br>

                            <div class="form-group dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ trans('Counters_trans.hotel') }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach ($hotels as $category)
                                        <li>
                                            <label class="dropdown-item">
                                                <input type="checkbox" name="hotel_ids[]" value="{{ $category->id }}">
                                                {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <script>
                                // منع إغلاق القائمة عند النقر داخلها
                                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                                    menu.addEventListener('click', function (e) {
                                        e.stopPropagation();
                                    });
                                });
                            </script>



                            <br>



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



