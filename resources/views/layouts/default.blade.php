<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@include('includes.head')
	<link href="{{ asset('images/kufavicon.ico') }}" rel="icon">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Full height page */
  }

  main {
    flex: 1; /* Push footer to bottom */
  }

  footer {
    font-size: 0.85rem; /* smaller text */
    padding: 0.5rem 0;  /* slim padding */
    background-color: #f8f9fa; /* Bootstrap light */
    border-top: 1px solid #dee2e6;
  }

  footer a {
    color: #6c757d; /* muted text */
    text-decoration: none;
  }

  footer a:hover {
    text-decoration: underline;
  }



.app-footer {
  font-size: 0.8rem;
  padding: 0.5rem 0;
  background: #996515;  /* dark gray */
  color: #ccc;
}
.app-footer a { color: #999; }
.app-footer a:hover { color: #fff; text-decoration: underline; }


</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

</head>
@php
	$bodyClass = (!empty($appBoxedLayout)) ? 'boxed-layout ' : '';
	$bodyClass .= (!empty($paceTop)) ? 'pace-top ' : $bodyClass;
	$bodyClass .= (!empty($bodyClass)) ? $bodyClass . ' ' : $bodyClass;
	$appSidebarHide = (!empty($appSidebarHide)) ? $appSidebarHide : '';
	$appHeaderHide = (!empty($appHeaderHide)) ? $appHeaderHide : '';
	///$appSidebarTwo = (!empty($appSidebarTwo)) ? $appSidebarTwo : '';
	$appSidebarSearch = (!empty($appSidebarSearch)) ? $appSidebarSearch : '';
	$appTopMenu = (!empty($appTopMenu)) ? $appTopMenu : '';
	
	$appClass = (!empty($appTopMenu)) ? 'app-with-top-menu ' : '';
	$appClass .= (!empty($appHeaderHide)) ? 'app-without-header ' : ' app-header-fixed ';
	$appClass .= (!empty($appSidebarEnd)) ? 'app-with-end-sidebar ' : '';
	$appClass .= (!empty($appSidebarWide)) ? 'app-with-wide-sidebar ' : '';
	$appClass .= (!empty($appSidebarHide)) ? 'app-without-sidebar ' : '';
	$appClass .= (!empty($appSidebarMinified)) ? 'app-sidebar-minified ' : '';
	//$appClass .= (!empty($appSidebarTwo)) ? 'app-with-two-sidebar app-sidebar-end-toggled ' : '';
	$appClass .= (!empty($appSidebarHover)) ? 'app-with-hover-sidebar ' : '';
	$appClass .= (!empty($appContentFullHeight)) ? 'app-content-full-height ' : '';
	
	
	$appContentClass = (!empty($appContentClass)) ? $appContentClass : '';
@endphp

<body class="{{ $bodyClass }}">
  @include('includes.component.page-loader')

  <div id="app" class="app d-flex flex-column min-vh-100 {{ $appClass }}">
    
    <!-- HEADER / SIDEBAR -->
    @includeWhen(!$appHeaderHide, 'includes.header')
    @includeWhen($appTopMenu, 'includes.top-menu')
    @includeWhen(!$appSidebarHide, 'includes.sidebar')
   

    <!-- CONTENT AREA -->
    <div id="content" class="app-content flex-grow-1 {{ $appContentClass }}">
      @yield('content')
    </div>

    <!-- FOOTER -->
     <footer id="footer" class="app-footer text-center small">
      &copy; <span id="footer-year">{{ date('Y') }}</span> Kenyatta University. 
      All rights reserved.
      <span class="mx-2">|</span>
      <a href="https://accommodation.ku.ac.ke/" class="text-muted text-decoration-none">Accommodation</a>
      <span class="mx-2">|</span>
      <a href="https://accommodation.ku.ac.ke/about-us" class="text-muted text-decoration-none">About Us</a>
      <span class="mx-2">|</span>
      <a href="https://accommodation.ku.ac.ke/contact-us" class="text-muted text-decoration-none">Contact</a>
    </footer>

  </div><!-- END #app -->

  @yield('outside-content')

  @include('includes.page-js')

 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 
<!-- In your master layout head -->



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>



<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector("[data-toggle='app-sidebar-mobile']");
    const sidebar = document.getElementById("sidebar");
    const backdrop = document.getElementById("sidebar-backdrop");

    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("active");
        backdrop.classList.toggle("active");
    });

    backdrop.addEventListener("click", function () {
        sidebar.classList.remove("active");
        backdrop.classList.remove("active");
    });

    // Auto-collapse submenus on mobile
    document.querySelectorAll("#sidebar .has-sub > .menu-link").forEach(link => {
        link.addEventListener("click", function (e) {
            if (window.innerWidth <= 991) {
                e.preventDefault();
                const parent = link.parentElement;
                parent.classList.toggle("open");
            }
        });
    });
});
</script>



</body>

</html>