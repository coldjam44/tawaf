<!DOCTYPE html>
<html
  lang="{{ config()->get('app.locale') }}"
  class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="{{ config()->get('app.locale') == 'ar' ? 'rtl' : 'ltr' }}"
  data-theme="theme-default"
  data-assets-path="{{ asset('admin/assets') }}/"
  data-template="vertical-menu-template-starter">
  <head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MQ6DdL+gkOIvyg4ezOJS+r6g7g6UpCq5siCw

    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Aura Home</title>

    <meta name="description" content="" />

    @include('admin.includes.styles')

  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        @include('admin.includes.aside')

        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

            @include('admin.includes.header')

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
                @yield('content')
            </div>
            <!-- / Content -->


            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <form action="{{ route('admin.logout') }}" method="post" id="logout_form">
        @csrf
    </form>
    @include('admin.includes.scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-c1kX9RXD4y5Rbj6SUgo5xB9dzGCuI9QqSnCtkcu0F0lF5CqtK4Vb9mT6AwDgk6p3" crossorigin="anonymous"></script>

    <style>
        /* Search Projects Styling */
        #projectSearch {
            background: transparent !important;
            border: none !important;
            color: inherit !important;
            font-size: 0.875rem !important;
            padding: 0.25rem 0 !important;
            width: 100% !important;
        }
        
        #projectSearch:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        
        #projectSearch::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .menu-item .menu-link:hover #projectSearch::placeholder {
            color: rgba(255, 255, 255, 0.9) !important;
        }
    </style>

    <script>
        // Project Search Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('projectSearch');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.trim();
                    
                    if (searchTerm.length >= 2) {
                        // Search in current page projects
                        searchProjectsInPage(searchTerm);
                    } else {
                        // Show all projects if search term is too short
                        showAllProjects();
                    }
                });

                // Add enter key functionality to redirect to projects page with search
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const searchTerm = this.value.trim();
                        if (searchTerm) {
                            window.location.href = '{{ route("projects.index") }}?search=' + encodeURIComponent(searchTerm);
                        }
                    }
                });
            }

            function searchProjectsInPage(searchTerm) {
                // This function will search in the current page if we're on projects page
                const projectRows = document.querySelectorAll('[data-project-name]');
                
                projectRows.forEach(row => {
                    const projectName = row.getAttribute('data-project-name').toLowerCase();
                    const projectDescription = row.getAttribute('data-project-description')?.toLowerCase() || '';
                    const projectNumber = row.getAttribute('data-project-number')?.toLowerCase() || '';
                    const projectPermit = row.getAttribute('data-project-permit')?.toLowerCase() || '';
                    
                    if (projectName.includes(searchTerm.toLowerCase()) || 
                        projectDescription.includes(searchTerm.toLowerCase()) ||
                        projectNumber.includes(searchTerm.toLowerCase()) ||
                        projectPermit.includes(searchTerm.toLowerCase())) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            function showAllProjects() {
                const projectRows = document.querySelectorAll('[data-project-name]');
                projectRows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    </script>

  </body>
</html>
