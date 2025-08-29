@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-center my-4">{{ trans('main_trans.edit_slider') }}</h3>
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

                <form action="{{ route('sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col">
                            <label>{{ trans('main_trans.title_en') }}</label>
                            <input type="text" name="title_en" class="form-control" value="{{ $slider->title_en }}">
                        </div>
                        <div class="col">
                            <label>{{ trans('main_trans.title_ar') }}</label>
                            <input type="text" name="title_ar" class="form-control" value="{{ $slider->title_ar }}">
                        </div>
                    </div>

                    {{-- Project Logo --}}
                    <div class="mb-3">
                        <label>{{ trans('main_trans.project_logo') }}</label>
                        <input type="file" name="project_logo" class="form-control">
                        @if($slider->project_logo)
                            <img src="{{ asset('sliders/' . $slider->project_logo) }}" width="50" height="50" class="mt-2">
                        @endif
                    </div>

                    {{-- Buttons --}}
                    <div class="row mb-3">
                        <div class="col">
                            <label>{{ trans('main_trans.button1_text_en') }}</label>
                            <input type="text" name="button1_text_en" class="form-control" value="{{ $slider->button1_text_en }}">
                        </div>
                        <div class="col">
                            <label>{{ trans('main_trans.button1_text_ar') }}</label>
                            <input type="text" name="button1_text_ar" class="form-control" value="{{ $slider->button1_text_ar }}">
                        </div>
                        <div class="col">
                            <label>{{ trans('main_trans.button1_link') }}</label>
                            <input type="text" name="button1_link" class="form-control" value="{{ $slider->button1_link }}">
                        </div>
                    </div>

                    {{-- Features --}}
                    <div class="row mb-3">
                        <div class="col">
                            <label>{{ trans('main_trans.features_en') }}</label>
                            <textarea name="features_en" class="form-control" rows="3">{{ $slider->features_en ? implode(',', json_decode($slider->features_en)) : '' }}</textarea>
                        </div>
                        <div class="col">
                            <label>{{ trans('main_trans.features_ar') }}</label>
                            <textarea name="features_ar" class="form-control" rows="3">{{ $slider->features_ar ? implode(',', json_decode($slider->features_ar)) : '' }}</textarea>
                        </div>
                    </div>

                    {{-- Brochure --}}
                    <div class="mb-3">
                        <label>{{ trans('main_trans.brochure_link') }}</label>
                        <input type="file" name="brochure_link" class="form-control" accept=".pdf,.doc,.docx">
                        @if($slider->brochure_link)
                            <a href="{{ asset('sliders/' . $slider->brochure_link) }}" target="_blank" class="d-block mt-2">Current file</a>
                        @endif
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label>{{ trans('main_trans.status') }}</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $slider->status ? 'selected' : '' }}>{{ trans('main_trans.active') }}</option>
                            <option value="0" {{ !$slider->status ? 'selected' : '' }}>{{ trans('main_trans.inactive') }}</option>
                        </select>
                    </div>

                    {{-- Order --}}
                    <div class="mb-3">
                        <label>{{ trans('main_trans.order') }}</label>
                        <input type="number" name="order" class="form-control" value="{{ $slider->order }}">
                    </div>

                    {{-- Background Image --}}
                    <div class="mb-3">
                        <label>{{ trans('main_trans.background_image') }}</label>
                        <input type="file" name="background_image" class="form-control">
                        @if($slider->background_image)
                            <img src="{{ asset('sliders/' . $slider->background_image) }}" width="80" height="50" class="mt-2">
                        @endif
                    </div>

                    <button type="submit" class="btn btn-success">{{ trans('main_trans.submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
