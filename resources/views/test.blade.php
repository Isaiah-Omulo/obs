<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Sidebar Test</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

	<!-- ================== BEGIN core-css ================== -->
	<link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


	 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
	<!-- ================== END core-css ================== -->


</head>
<body>
	<div>
		<!-- BEGIN #header -->
		<header id="header" class="app-header">
			<div class="d-flex align-items-center">
				<button class="btn btn-sm d-md-none" type="button" data-toggle="app-sidebar-mobile">
					<i class="fa fa-bars"></i>
				</button>
				<a href="#" class="navbar-brand">Test Page</a>
			</div>
		</header>
		<!-- END #header -->

		<!-- BEGIN #sidebar -->
		<div id="sidebar" class="app-sidebar">
			<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
				<div class="menu">
					<div class="menu-header">Navigation</div>
					<div class="menu-item active"><a href="#" class="menu-link"><div class="menu-text">Dashboard</div></a></div>
				</div>
			</div>
		</div>
		<div class="app-sidebar-bg"></div>
		<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
		<!-- END #sidebar -->

		<!-- BEGIN #content -->
		<div id="content" class="app-content">
			<h1>Testing Cord Functionality:</h1>

			<form action="http://httpbin.org/post" method="post">
			  <select id="bears" multiple class="select2" name="test[]">
			    <option value=""></option>
			    <option>American Black Bear</option>
			    <option>Asiatic Black Bear</option>
			    <option>Brown Bear</option>
			    <option>Giant Panda</option>
			    <option>Sloth Bear</option>
			    <option>Sun Bear</option>
			    <option>Polar Bear</option>
			    <option>Spectacled Bear</option>
			  </select>
			  <input type="submit">
			</form>

		</div>
		<!-- END #content -->
	</div><!-- END #app -->

	<!-- ================== BEGIN core-js ================== -->
	<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.min.js') }}"></script>
	<!-- ================== END core-js ================== -->


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
      $(".select2").select2({
        placeholder: "Begin typing a name to filter...",
        width: "400px"
      });
    });
  </script>
</body>
</html>