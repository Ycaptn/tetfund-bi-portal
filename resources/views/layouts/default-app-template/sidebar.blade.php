


	@php
		$current_user = Auth()->user();
	@endphp


	<!--sidebar wrapper -->
	<div class="sidebar-wrapper" data-simplebar="true">

		<div class="sidebar-header">
			<div>
				@if (isset($app_settings) && isset($app_settings['portal_file_icon_picture']))
					<img class="logo-icon" src="{{ route('fc.attachment.show',$app_settings['portal_file_icon_picture']) }}" alt="brand"/>
				@else
					<img src="{{ asset('imgs/logo.png') }}" class="logo-icon" alt="brand" />
				@endif
			</div>
			<div>
				<h4 class="logo-text">
					@if (isset($app_settings) && isset($app_settings['portal_short_name']))
						{{ $app_settings['portal_short_name'] }}
					@else
						TETFund
					@endif
				</h4>
			</div>
			<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
		</div>

		<!--navigation-->
		<ul class="metismenu" id="menu">

			<li>
				<a href="{{ route('dashboard') }}" class="">
					<div class="parent-icon"><i class='bx bx-home-circle'></i>
					</div>
					<div class="menu-title">Dashboard</div>
				</a>
			</li>

			@php
				$menu_dm = \EDMSEngine::get_menu_map();
				$menu_wf = \WorkflowEngine::get_menu_map();
				$menu_fc = \FoundationCore::get_menu_map();
				$menu_tf_fa = \FinanceAudit::get_menu_map();
				$menu_tf_bip = \Intervention::get_menu_map();
				$menu_tf_bm = \BeneficiaryMgt::get_menu_map();
				$menu_tf_me = \MonitoringEvaluation::get_menu_map();
			@endphp
		
			@each('layouts.default-app-template.menu-group', $menu_dm, 'children')
			@each('layouts.default-app-template.menu-group', $menu_wf, 'children')
			@each('layouts.default-app-template.menu-group', $menu_tf_fa, 'children')
			@each('layouts.default-app-template.menu-group', $menu_tf_bip, 'children')
			@each('layouts.default-app-template.menu-group', $menu_tf_bm, 'children')
			@each('layouts.default-app-template.menu-group', $menu_tf_me, 'children')
			@each('layouts.default-app-template.menu-group', $menu_fc, 'children')

		</ul>
		<!--end navigation-->

	</div>
	<!--end sidebar wrapper -->


	<!--start header -->
	<header>
		<div class="topbar d-flex align-items-center">
			<nav class="navbar navbar-expand">
				<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
				</div>
				@if (FoundationCore::has_feature('edms', $organization))
				<div class="search-bar flex-grow-1">
					<div class="position-relative search-bar-box">
						<input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
						<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
					</div>
				</div>
				@endif
				<div class="top-menu ms-auto">
					<ul class="navbar-nav align-items-center">
						<li class="nav-item mobile-search-icon">
							<a class="nav-link" href="#">	<i class='bx bx-search'></i>
							</a>
						</li>
					</ul>
				</div>
				<div class="user-box dropdown">
					<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						

						@php
							$user_img_path = asset('imgs/bare-profile.png');
							if (Auth::user() != null && Auth::user()->profile_image != null){
								$user_img_path = route('fc.get-profile-picture', Auth::id());
							}
						@endphp
						<img src="{{ $user_img_path }}" class="user-img" alt="user" />

						<div class="user-info ps-3">
							@if ($current_user != null)
							<p class="user-name mb-0">{{ $current_user->full_name }}</p>
							<p class="designattion mb-0">{{ $current_user->job_title }}</p>
							@endif
						</div>
					</a>
					<ul class="dropdown-menu dropdown-menu-end">
						<li><a class="dropdown-item" href="{{route('fc.users.profile')}}"><i class="bx bx-user"></i><span>Profile</span></a></li>
						<li><div class="dropdown-divider mb-0"></div></li>
						<li>
							<a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								<i class='bx bx-log-out-circle'></i><span>Logout</span></a>
							</a>
						</li>
						<form id="logout-form" action="{{route('logout')}}" method="POST" class="d-none">
							@csrf
						</form>
					</ul>
				</div>
			</nav>
		</div>
	</header>
	<!--end header -->
