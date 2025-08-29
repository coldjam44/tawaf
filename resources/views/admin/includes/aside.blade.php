<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

<div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
    <span class="app-brand-logo demo">
               <img src="{{ asset('admin/assets/img/White-logo-700x700.webp') }}" alt="Logo" style="filter: invert(100%);">


    </span>
        @php
    // جلب اسم الدومين بدون www وبدون tld
    $host = parse_url(request()->getHost(), PHP_URL_HOST) ?: request()->getHost();
    $parts = explode('.', $host);
    $siteName = $parts[0]; // أول جزء من الدومين
@endphp

 

    <span class="app-brand-text demo menu-text fw-bold">{{ ucfirst($siteName) }}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
    <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
    <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
</div>

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
    <!-- Page -->

    <li class="menu-item">
    <a href="https://royallp.com" target="_blank" rel="noopener" class="menu-link">
        <i class="menu-icon tf-icons ti ti-external-link"></i>
        <div data-i18n="visit_website">{{ trans('main_trans.visit_website') }}</div>
    </a>
    </li>

    <li class="menu-item active">
    <a href="{{ route('home') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">Home</div>
    </a>
    </li>
    <li class="menu-item active">
    <a href="{{ route('sliders.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-layout-grid"></i>
        <div data-i18n="Slider">{{ trans('main_trans.slider') }}</div>
    </a>
</li>
    <li class="menu-item">
    <a href="{{ route('projects.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-archive"></i>
        <div data-i18n="Projects">{{ trans('main_trans.section_project') }}</div>
    </a>
</li>

    {{-- Project Details Dropdown --}}
    <li class="menu-item dropdown {{ request()->routeIs('project-details.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="menu-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="menu-icon tf-icons ti ti-list-details"></i>
            <div data-i18n="Project Details">{{ trans('main_trans.project_details_management') }}</div>
        </a>
        <ul class="dropdown-menu">
            @php
                $projects = \App\Models\Project::with('developer', 'area')->orderBy('id', 'desc')->limit(10)->get();
            @endphp
            @forelse($projects as $project)
            <li class="dropdown-item">
                <a href="{{ route('project-details.index', $project->id) }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-chevron-right"></i>
                    <span class="text-truncate" style="max-width: 200px;">
                        {{ $project->getTitle() }}
                    </span>
                </a>
            </li>
            @empty
            <li class="dropdown-item">
                <span class="text-muted">{{ trans('main_trans.no_projects_found') }}</span>
            </li>
            @endforelse
            <li class="dropdown-divider"></li>
            <li class="dropdown-item">
                <a href="{{ route('projects.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-plus"></i>
                    {{ trans('main_trans.view_all_projects') }}
                </a>
            </li>
        </ul>
    </li>

    {{-- Project Images Dropdown --}}
    <li class="menu-item dropdown {{ request()->routeIs('project-images.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="menu-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="menu-icon tf-icons ti ti-photo"></i>
            <div data-i18n="Project Images">{{ trans('main_trans.project_images_management') }}</div>
        </a>
        <ul class="dropdown-menu">
            @php
                $projects = \App\Models\Project::with('developer', 'area')->orderBy('id', 'desc')->limit(10)->get();
            @endphp
            @forelse($projects as $project)
            <li class="dropdown-item">
                <a href="{{ route('project-images.index', $project->id) }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-chevron-right"></i>
                    <span class="text-truncate" style="max-width: 200px;">
                        {{ $project->getTitle() }}
                    </span>
                </a>
            </li>
            @empty
            <li class="dropdown-item">
                <span class="text-muted">{{ trans('main_trans.no_projects_found') }}</span>
            </li>
            @endforelse
            <li class="dropdown-divider"></li>
            <li class="dropdown-item">
                <a href="{{ route('projects.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-plus"></i>
                    {{ trans('main_trans.view_all_projects') }}
                </a>
            </li>
        </ul>
    </li>

    {{-- Areas Menu Item --}}
    <li class="menu-item {{ request()->routeIs('areas.*') ? 'active' : '' }}">
        <a href="{{ route('areas.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-map-pin"></i>
            <div data-i18n="Areas">{{ trans('main_trans.areas') }}</div>
        </a>
    </li>

    <li class="menu-item active">
    <a href="{{ route('developers.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">{{ trans('main_trans.section_developer') }}</div>
    </a>
    </li>

    <li class="menu-item">
    <a href="{{ route('real-estate-company.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-building"></i>
        <div data-i18n="real_estate_company">{{ trans('main_trans.real_estate_company') }}</div>
    </a>
    </li>

          <li class="menu-item active">
      <a href="{{ route('about-us.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-building"></i>
          <div data-i18n="About Us">{{ trans('main_trans.about_us') }}</div>
      </a>
      </li>

      <li class="menu-item active">
      <a href="{{ route('blogs.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-article"></i>
          <div data-i18n="Blog">{{ trans('main_trans.blog') }}</div>
      </a>
      </li>

      <li class="menu-item active">
      <a href="{{ route('awards.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-trophy"></i>
          <div data-i18n="Awards">{{ trans('main_trans.awards') }}</div>
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
