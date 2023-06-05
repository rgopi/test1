<!DOCTYPE html>
<html lang="en">

<head>
	<?php

	?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title><?= isset($title) ? $title : 'Page Title' ?></title>
	<link rel="icon" type="image/x-icon" href="/img/logo.png">
	<link rel="stylesheet" href="/css/ionicons.min.css">
	<link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
	<link rel="stylesheet" href="/css/adminlte.min.css">
	<link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
	<link rel="stylesheet" href="/css/fonts.css">
	<link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
	<link rel="stylesheet" href="/css/pagination.css">
	<link rel="stylesheet" href="/css/PNotify.css">
	<link rel="stylesheet" href="/css/BrightTheme.css">
	<link rel="stylesheet" href="/css/custom.css<?= '?v='.date('Hi') ?>">
	<link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
	<link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<script src="/plugins/jquery/jquery.min.js"></script>
	<link rel="stylesheet" href="/css/jquery.dataTables.min.css">
	<script src="/js/jquery.form.min.js"></script>
	<link rel="stylesheet" href="/css/sweetalert2.min.css">
	<script src="/js/sweetalert2.all.min.js"></script>
	<script>
		var dtable;

		function getAppStroe(key) {
			if (typeof(Storage) !== "undefined") {
				// Code for localStorage/sessionStorage.
				let ck = localStorage.getItem(key);
				return ck;
				let cols = JSON.parse(ck ? ck : '[]');
				// cols = JSON.parse(cols);
			} else {
			// Sorry! No Web Storage support..
			}

		}
		function setAppStroe(key, value) {
			if (typeof(Storage) !== "undefined") {
				// Code for localStorage/sessionStorage.
				return localStorage.setItem(key, value);
			} else {
			// Sorry! No Web Storage support..
			}

		}
	</script>
</head>

<body class="dark-mode hold-transition sidebar-mini layout-navbar-fixed">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-dark">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="javascript:;" role="button"><i
							class="fas fa-bars"></i></a>
				</li>
			</ul>

			<ul class="navbar-nav ml-auto">
				<?php
				/* <li class="nav-item">
					<a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true"
						href="javascript:;" role="button">
						<i class="fas fa-th-large"></i>
					</a>
				</li>
				*/
				?>
			</ul>
		</nav>
		<?php
		// @include('layouts.admin.adminLeftSidebar') sidebar-dark-primary
		?>
		<aside class="main-sidebar sidebar-dark-primary elevation-4">

			<a href="{{Route('dashboard')}}" class="brand-link">
				<img src="/img/logo.png" alt="<?= env('APP_NAME') ?>" class="brand-image img-circle elevation-3"
					style="opacity: .8">
				<span class="brand-text font-weight-light"><?= env('APP_NAME') ?></span>
			</a>

			<div class="sidebar">
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<?php
						// <img src="/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
						?>
					</div>
					<div class="info">
						<a href="{{Route('dashboard')}}" class="d-block">Name <span class="text-maroon bold pull-right">Admin <i class="fa fa-user"></i></span></a>
					</div>
				</div>

				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
						data-accordion="false">
						<li class="nav-item">
							<a href="javascript:;" class="nav-link">
								<i class="nav-icon fa fa-map-marker text-blue"></i>
								<p>Regions <i class="fas fa-angle-left right"></i></p>
							</a>
							<ul class="nav nav-treeview bg-light">
								<li class="nav-item">
									<a href="{{ Route('pincode')}}" class="nav-link">
										<i class="fa fa-map-pin nav-icon"></i>
										<p>Pincode</p>
									</a>
								</li>
								<!-- <li class="nav-item">
									<a href="{{ url(env('ADMIN_BASE_URL') . '/district') }}" class="nav-link">
										<i class="fa fa-map-marker nav-icon"></i>
										<p>Disctrict</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="{{ url(env('ADMIN_BASE_URL') . '/states') }}" class="nav-link">
										<i class="fa fa-map-marker nav-icon"></i>
										<p>State</p>
									</a>
								</li> -->
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</aside>


		<div class="content-wrapper">
			@yield('content')
		</div>
		<footer class="main-footer">
			<strong>Copyright &copy; 2019-<?= date('Y') ?> <a href="<?= env('APP_URL') ?>"><?= env('APP_NAME') ?></a></strong> All rights reserved.
			<?php
			/* <div class="float-right d-none d-sm-inline-block"> <b>Version</b> 3.2.0</div> */
			?>
		</footer>
		<?php
		/*<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>*/
		?>
	</div>

	<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<script src="/plugins/select2/js/select2.full.min.js"></script>
	<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/plugins/chart.js/Chart.min.js"></script>
	<script src="/plugins/sparklines/sparkline.js"></script>
	<script src="/plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<script src="/plugins/jquery-knob/jquery.knob.min.js"></script>
	<script src="/plugins/moment/moment.min.js"></script>
	<script src="/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<script src="/plugins/summernote/summernote-bs4.min.js"></script>
	<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<script src="/js/adminlte.js"></script>
	<script src="/js/project.js<?= '?v='.date('Hi') ?>"></script>
	<script src="/js/PNotify.js"></script>
	<script src="/js/PNotifyFontAwesome5.js"></script>
	<script src="/js/jquery.dataTables.min.js"></script>
	<script src="/js/dataTables.buttons.min.js"></script>
	<script src="/js/pdfmake.min.js"></script>
	<script src="/js/vfs_fonts.js"></script>
	<script src="/js/jszip.min.js"></script>
	<script src="/js/buttons.html5.min.js"></script>
	<div id="busy" style="display:none" class="notextsel loading"></div>
	<div id="handlers" class="hide" style="display:none"></div>
    @include('flash')
	<script>

		function checkExists(actionUrl, formId, field) {
			$.ajax({
				type: "POST",
				url: actionUrl,
				data: $("#" + formId).serialize()+'&field='+field,
				cache: false,
				beforeSend: function (xhr, settings) {
					$("#busy").show();
				},
				complete: function (event, request) {
					$("#busy").hide();
				},
				success: function (response) {
					$("#busy").hide();
					handleResponses(response, formId);
				},
				error: function (data) {
					$("#busy").hide();
					handleResponses(data, formId);
				},
			});
		}
		<?= \App\Models\Helper::dataTableJsCode(); ?>
	</script>
</body>

</html>
