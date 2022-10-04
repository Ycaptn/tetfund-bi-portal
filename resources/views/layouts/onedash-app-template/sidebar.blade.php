
	@php
		$current_user = Auth()->user();
	@endphp

	
	<header class="top-header">
		<nav class="navbar navbar-expand gap-3">
			<div class="mobile-toggle-icon fs-3">
				<i class="bi bi-list"></i>
			</div>
			@if (FoundationCore::has_feature('edms', $organization))
			<form class="searchbar">
				<div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
				<input class="form-control" type="text" placeholder="Type here to search">
				<div class="position-absolute top-50 translate-middle-y search-close-icon"><i class="bi bi-x-lg"></i></div>
			</form>
			@endif
			<div class="top-navbar-right ms-auto">
				<ul class="navbar-nav align-items-center">

					@if (FoundationCore::has_feature('edms', $organization))
					<li class="nav-item search-toggle-icon">
						<a class="nav-link" href="#">
							<div class="">
							<i class="bi bi-search"></i>
							</div>
						</a>
					</li>
					@endif

					<li class="nav-item dropdown dropdown-user-setting">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
							<div class="user-setting d-flex align-items-center">
								@php
									$user_img_path = asset('imgs/user.png');
									if (Auth::user() != null && Auth::user()->profile_image != null){
										$user_img_path = route('fc.get-profile-picture', Auth::id());
									}
								@endphp
								<img src="{{ $user_img_path }}" class="user-img" alt="user" />
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<a class="dropdown-item" href="#">
									<div class="d-flex align-items-center">
										@php
											$user_img_path = asset('imgs/user.png');
											if (Auth::user() != null && Auth::user()->profile_image != null){
												$user_img_path = route('fc.get-profile-picture', Auth::id());
											}
										@endphp
										<img src="{{ $user_img_path }}" class="rounded-circle" width="54" height="54" alt="user" />
										<div class="ms-3">
											@if ($current_user != null)
											<h6 class="mb-0 dropdown-user-name">{{ $current_user->full_name }}</h6>
											<small class="mb-0 dropdown-user-designation text-secondary">{{ $current_user->job_title }}</small>
											@endif
										</div>
									</div>
								</a>
							</li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<a class="dropdown-item" href="{{route('fc.users.profile')}}">
									<div class="d-flex align-items-center">
									<div class=""><i class="bi bi-person-fill"></i></div>
									<div class="ms-3"><span>Profile</span></div>
									</div>
								</a>
								</li>
								<li><hr class="dropdown-divider"></li>
								<li>
								<a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									<div class="d-flex align-items-center">
									<div class=""><i class="bi bi-lock-fill"></i></div>
									<div class="ms-3"><span>Logout</span></div>
									</div>
								</a>
								<form id="logout-form" action="{{route('logout')}}" method="POST" class="d-none">
									@csrf
								</form>
							</li>
						</ul>
					</li>

				</ul>
			</div>
		</nav>
		<marquee width="100%" direction="right" style="margin-top:61px;color:white;font-weight:bold;padding:5px 5px 5px 5px;background-color:#02b863c9;">
			The Intranet Notice Board is now online. Internal Announcements will be placed on this banner.
		</marquee>
  	</header>


	<aside class="sidebar-wrapper" data-simplebar="true">

	  	<div class="sidebar-header">
			<div>
				@if (isset($app_settings) && isset($app_settings['portal_file_icon_picture']))
					<img class="logo-icon" src="{{ route('fc.attachment.show',$app_settings['portal_file_icon_picture']) }}" style="filter: invert(0);" alt="brand"/>
				@else
					<img src="{{ asset('imgs/logo.png') }}" class="logo-icon" alt="brand" style="filter: invert(0);" />
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
			<div class="toggle-icon ms-auto"> 
				<i class="bi bi-list"></i>
			</div>
	  	</div>

		<ul class="metismenu" id="menu">

			<li>
				<a href="{{ route('dashboard') }}" class="">
					<div class="parent-icon"><i class='bx bx-home-circle'></i>
					</div>
					<div class="menu-title">Dashboard</div>
				</a>
			</li>

			@php
				$menu_tf_bis = \BISubmission::get_menu_map();
				$menu_tf_imp = \Impact::get_menu_map();
				$menu_tf_astd = \ASTD::get_menu_map();

				$menu_dm = \EDMSEngine::get_menu_map();
				$menu_wf = \WorkflowEngine::get_menu_map();
				$menu_fc = \FoundationCore::get_menu_map();
				$menu_tf_fa = \FinanceAudit::get_menu_map();
				$menu_tf_bip = \Intervention::get_menu_map();
				$menu_tf_bm = \BeneficiaryMgt::get_menu_map();
				$menu_tf_me = \MonitoringEvaluation::get_menu_map();
			@endphp
		
			@each('layouts.onedash-app-template.menu-group', $menu_dm, 'children')
			@each('layouts.onedash-app-template.menu-group', $menu_wf, 'children')
			@each('layouts.onedash-app-template.menu-group', $menu_tf_fa, 'children')
			@each('layouts.onedash-app-template.menu-group', $menu_tf_bip, 'children')
			@each('layouts.onedash-app-template.menu-group', $menu_tf_bm, 'children')
			@each('layouts.onedash-app-template.menu-group', $menu_tf_me, 'children')

			@each('layouts.onedash-app-template.menu-group', $menu_tf_bis, 'children')
			@each('layouts.onedash-app-template.menu-group', $menu_tf_imp, 'children')
			@each('layouts.onedash-app-template.menu-group', $menu_tf_astd, 'children')

			@each('layouts.onedash-app-template.menu-group', $menu_fc, 'children')

		</ul>

   	</aside>
