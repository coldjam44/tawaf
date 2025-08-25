<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
<div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
    <span class="app-brand-logo demo">
                <img src="{{ asset('admin/assets/img/logo.png') }}" alt="Logo">

    </span>
    <span class="app-brand-text demo menu-text fw-bold">Tawaf</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
    <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
    <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
</div>

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
    <!-- Page -->
    <li class="menu-item active">
    <a href="{{ route('home') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">Home</div>
    </a>
    </li>
    <br>
    <li class="menu-item active">
    <a href="{{ route('places.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.place') }}</div>
    </a>
    </li>
    <br>
    
    <br>
    {{-- <li class="menu-item active">
    <a href="{{ route('hotels.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.hotels') }}</div>
    </a>
    </li> --}}

    <li class="menu-item dropdown {{ request()->routeIs('hotels.*') || request()->routeIs('avilablerooms.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="menu-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="menu-icon tf-icons ti ti-smart-home"></i>
            <div data-i18n="Home">{{ trans('main_trans.hotels') }}</div>
        </a>
        <ul class="dropdown-menu">
            <!-- رابط الفنادق -->
            <li class="dropdown-item {{ request()->routeIs('hotels.index') ? 'active' : '' }}">
                <a href="{{ route('hotels.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-home"></i>
                    {{ trans('main_trans.hotels') }}
                </a>
            </li>

            <!-- رابط الغرف المتاحة -->
            <li class="dropdown-item {{ request()->routeIs('avilablerooms.index') ? 'active' : '' }}">
                <a href="{{ route('avilablerooms.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-door"></i>
                    {{ trans('main_trans.avilablerooms') }}
                </a>
            </li>

            <li class="dropdown-item {{ request()->routeIs('featurea.index') ? 'active' : '' }}">
                <a href="{{ route('features.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-door"></i>
                    {{ trans('main_trans.features') }}
                </a>
            </li>
        </ul>
    </li>


    <br>
    <li class="menu-item active">
    <a href="{{ route('customerreviews.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.customerreview') }}</div>
    </a>
    </li>
    <br>
    <li class="menu-item active">
    <a href="{{ route('ramadanoffers.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.ramadanoffers') }}</div>
    </a>
    </li>
    <br>
    <li class="menu-item active">
    <a href="{{ route('amenitys.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.amenitys') }}</div>
    </a>
    </li>
    <br>
    <li class="menu-item active">
    <a href="{{ route('promocodes.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.promocodes') }}</div>
    </a>
    </li>

    <br>
    <li class="menu-item active">
    <a href="{{ route('reviews.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.reviews') }}</div>
    </a>
    </li>
  
   <br>
    <li class="menu-item active">
    <a href="{{ route('booknows.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.booknow') }}</div>
    </a>
    </li>
  <br>
   
    <li class="menu-item active">
    <a href="{{ route('hotelinmakkahs.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.hotelinmakkahs') }}</div>
    </a>
    </li>
    <br>
    <li class="menu-item active">
    <a href="{{ route('hotelinmadinas.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.hotelinmadinas') }}</div>
    </a>
    </li>
  
  <br>
    <li class="menu-item active">
    <a href="{{ route('terms.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.terms') }}</div>
    </a>
    </li>

    <br>
    <li class="menu-item">
    <a href="javascript::void(0);" onclick="$('#logout_form').submit();" class="menu-link">
        <i class="menu-icon tf-icons ti ti-app-window"></i>
        <div data-i18n="Page 2">Logout</div>
    </a>
    </li>
</ul>
</aside>
