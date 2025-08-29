@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.sliders') }}</h3>
                <div class="text-right mb-3">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addSliderForm" aria-expanded="false" aria-controls="addSliderForm">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add') }}
                    </button>
                </div>
            </div>

            <div class="card-body">

                {{-- أخطاء الإدخال --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
 {{-- فورم الإضافة تحت الجدول --}}
                <div class="collapse" id="addSliderForm">
                    <div class="card card-body">
                        <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Title --}}
                            <div class="row mb-3">
                                <div class="col">
                                    <label>{{ trans('main_trans.title_en') }}</label>
                                    <input type="text" name="title_en" class="form-control">
                                </div>
                                <div class="col">
                                    <label>{{ trans('main_trans.title_ar') }}</label>
                                    <input type="text" name="title_ar" class="form-control">
                                </div>
                            </div>

                            {{-- Project Logo --}}
                            <div class="mb-3">
                                <label>{{ trans('main_trans.project_logo') }}</label>
                                <input type="file" name="project_logo" class="form-control">
                            </div>

                            {{-- Buttons --}}
                            <div class="row mb-3">
                                <div class="col">
                                    <label>{{ trans('main_trans.button1_text_en') }}</label>
                                    <input type="text" name="button1_text_en" class="form-control">
                                </div>
                                <div class="col">
                                    <label>{{ trans('main_trans.button1_text_ar') }}</label>
                                    <input type="text" name="button1_text_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label>{{ trans('main_trans.button1_link') }}</label>
                                    <input type="text" name="button1_link" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label>{{ trans('main_trans.button2_text_en') }}</label>
                                    <input type="text" name="button2_text_en" class="form-control">
                                </div>
                                <div class="col">
                                    <label>{{ trans('main_trans.button2_text_ar') }}</label>
                                    <input type="text" name="button2_text_ar" class="form-control">
                                </div>
                                <div class="col">
                                    <label>{{ trans('main_trans.button2_link') }}</label>
                                    <input type="text" name="button2_link" class="form-control">
                                </div>
                            </div>

                            {{-- Features (comma-separated) --}}
                            <div class="row mb-3">
                                <div class="col">
                                    <label>{{ trans('main_trans.features_en') }}</label>
                                    <textarea name="features_en" class="form-control maintrains" rows="3" placeholder="feature1, feature2, feature3"></textarea>
                                </div>
                                <div class="col">
                                    <label>{{ trans('main_trans.features_ar') }}</label>
                                    <textarea name="features_ar" class="form-control maintrains" rows="3" placeholder="الميزة1, الميزة2, الميزة3"></textarea>
                                </div>
                            </div>

                            {{-- Brochure --}}
                            <div class="mb-3">
                                <label>{{ trans('main_trans.brochure_link') }}</label>
                                <input type="file" name="brochure_link" class="form-control" accept=".pdf,.doc,.docx">
                            </div>



                            {{-- Status --}}
                            <div class="mb-3">
                                <label>{{ trans('main_trans.status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="1">{{ trans('main_trans.active') }}</option>
                                    <option value="0">{{ trans('main_trans.inactive') }}</option>
                                </select>
                            </div>

                            {{-- Order --}}
                            <div class="mb-3">
                                <label>{{ trans('main_trans.order') }}</label>
                                <input type="number" name="order" class="form-control" value="0">
                            </div>

                            {{-- Background Image --}}
                            <div class="mb-3">
                                <label>{{ trans('main_trans.background_image') }}</label>
                                <input type="file" name="background_image" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success">{{ trans('main_trans.submit') }}</button>
                        </form>
                    </div>
                </div>
                {{-- جدول السلايدرز --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.id') }}</th>
                                <th>{{ trans('main_trans.title') }}</th>
                                <th>{{ trans('main_trans.project_logo') }}</th>
                                <th>{{ trans('main_trans.background_image') }}</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $slider)
                            <tr>
                                <td>{{ $slider->id }}</td>
                                <td>{{ $slider->title_en }}</td>
                                <td>
                                    @if($slider->project_logo)
                                    <img src="{{ asset('sliders/' . $slider->project_logo) }}" width="50" height="50">
                                    @endif
                                </td>
                                <td>
                                    <img src="{{ asset('sliders/' . $slider->background_image) }}" width="80" height="50">
                                </td>
                                <td>
                                    <a href="{{ route('sliders.edit', $slider->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $sliders->links('pagination::bootstrap-5') }}
                </div>

               

            </div>
        </div>
    </div>
</div>

@endsection