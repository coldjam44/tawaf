<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

<div class="app-brand demo">
    <a href="https://realestate.azsystems.tech/" class="app-brand-link">
    <span class="app-brand-logo demo">
               <img src="{{ asset('admin/assets/img/new-logo.jpg') }}" alt="Logo">


    </span>
        @php
    // جلب اسم الدومين بدون www وبدون tld
    $host = parse_url(request()->getHost(), PHP_URL_HOST) ?: request()->getHost();
    $parts = explode('.', $host);
    $siteName = $parts[0]; // أول جزء من الدومين
@endphp

 

    <span class="app-brand-text demo menu-text fw-bold">Aura Home</span>
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
    <a href="https://aurahome.ae/" target="_blank" class="menu-link">
        <i class="menu-icon tf-icons ti ti-external-link"></i>
        <div data-i18n="visit_website">Aura Home</div>
    </a>
    </li>

    {{-- <li class="menu-item active">
    <a href="{{ route('home') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">Home</div>
    </a>
    </li> --}}
    <li class="menu-item active">
    <a href="{{ route('sliders.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-layout-grid"></i>
        <div data-i18n="Slider">{{ trans('main_trans.slider') }}</div>
    </a>
</li>
    <li class="menu-item active">
    <a href="{{ route('projects.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-archive"></i>
        <div data-i18n="Projects">{{ trans('main_trans.section_project') }}</div>
    </a>
</li>

    {{-- Properties Menu Item --}}
    <li class="menu-item active">
        <a href="{{ route('properties.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-home"></i>
            <div data-i18n="Properties">{{ trans('main_trans.property') }}</div>
        </a>
    </li>



    {{-- Search Projects --}}
    <li class="menu-item active">
        <div class="menu-link">
            <div class="d-flex align-items-center">
                <i class="menu-icon tf-icons ti ti-search me-2"></i>
                <div class="flex-grow-1">
                    <input type="text" id="projectSearch" class="form-control form-control-sm" 
                           placeholder="{{ trans('main_trans.search_projects') }}" 
                           style="border: none; background: transparent; color: inherit; font-size: 0.875rem;">
                </div>
            </div>
        </div>
    </li>

    {{-- Project Details Dropdown --}}
    <li class="menu-item active dropdown {{ request()->routeIs('project-details.*') ? 'active' : '' }}">
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
    <li class="menu-item active dropdown {{ request()->routeIs('project-images.*') ? 'active' : '' }}">
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
    <li class="menu-item active {{ request()->routeIs('areas.*') ? 'active' : '' }}">
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

    <li class="menu-item active">
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

      {{-- <li class="menu-item active">
      <a href="{{ route('awards.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-trophy"></i>
          <div data-i18n="Awards">{{ trans('main_trans.awards') }}</div>
      </a>
      </li> --}}

      <li class="menu-item active">
      <a href="{{ route('contact-us.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-phone"></i>
          <div data-i18n="Contact Us">{{ trans('main_trans.contact_us') }}</div>
      </a>
      </li>

      <li class="menu-item active">
      <a href="{{ route('newsletter.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-mail"></i>
          <div data-i18n="Newsletter">{{ trans('main_trans.newsletter') }}</div>
      </a>
      </li>

      <li class="menu-item active">
      <a href="{{ route('bot-offers.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-robot"></i>
          <div data-i18n="Bot Offers">{{ trans('main_trans.bot_offers_link') }}</div>
      </a>
      </li>
      
      <br>
    


    <li class="menu-item active">
    <a href="javascript::void(0);" onclick="$('#logout_form').submit();" class="menu-link">
        <i class="menu-icon tf-icons ti ti-app-window"></i>
        <div data-i18n="Page 2">Logout</div>
    </a>
    </li>
</ul>
</aside>
