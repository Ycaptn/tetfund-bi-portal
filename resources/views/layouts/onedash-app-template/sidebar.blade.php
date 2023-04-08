
	@php
		$current_user = Auth()->user();
	@endphp

	
	<header class="top-header">
		<nav class="navbar navbar-expand gap-3">
			<div class="mobile-toggle-icon fs-3">
				<i class="bi bi-list"></i>
			</div>

			<form class="searchbar">
				<div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
				<input class="form-control" type="text" placeholder="Search for your Submissions">
				<div class="position-absolute top-50 translate-middle-y search-close-icon"><i class="bi bi-x-lg"></i></div>
			</form>
			
			<div class="top-navbar-right ms-auto">
				<ul class="navbar-nav align-items-center">

					<li class="nav-item search-toggle-icon">
						<a class="nav-link" href="#">
							<div class=""> <i class="bi bi-search"></i>
							</div>
						</a>
					</li>

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
		<marquee width="100%" direction="left" style="margin-top:61px;color:white;padding:5px 5px 5px 5px;background-color:#02b863c9;">
			Welcome to the upgraded TETFund Beneficiary Submission Portal for all our Beneficiary Institutions. 
			Use this portal to process submissions for Physical Infrastructure, Library, ASTD, Academic Manuscripts, ICT Support, and Special Interventions.
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
			
			@if (Auth()->user()->hasAnyRole(['BI-desk-officer','BI-head-of-institution']))
			<li>
				<a href="{{ route('tf-bi-portal.fund-availability') }}" class="">
					<div class="parent-icon"><i class='bx bx-wallet'></i>
					</div>
					<div class="menu-title">Fund Availability</div>
				</a>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-desk-officer','BI-head-of-institution']))
			<li>
				<a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="">
					<div class="parent-icon"><i class='bx bx-layer-plus'></i>
					</div>
					<div class="menu-title">INTERV. Submissions</div>
				</a>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-staff']))
				<li>
					<a href="{{ route('tf-bi-portal.nomination_requests.index') }}" class="">
						<div class="parent-icon"><i class='fa fa-check-square'></i>
						</div>
						<div class="menu-title">Nomination Requests</div>
					</a>
				</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-desk-officer', 'BI-head-of-institution', 'BI-CA-committee-head', 'BI-TP-committee-head', 'BI-TSAS-committee-head', 'BI-CA-committee-member', 'BI-TP-committee-member', 'BI-TSAS-committee-member']))

			<li>
			    <a href="javascript:void(0);" data-toggle="collapse" data-target="#access_dr" class="collapsed" aria-expanded="false">
			        <div class="parent-icon pull-left">
			        	<i class='bx bx-paper-plane'></i>
					</div>
			        <div class="menu-title col-sm-12">
			        	ASTD Nomns. & Sett.
			        	&nbsp; <i class="fa fa-caret-down"></i>
			        </div>
			    </a>

			    {{-- collapse-level-1 collapse --}}
			    <ul id="access_dr" class="" aria-expanded="false" style="">
			        @if (Auth()->user()->hasAnyRole(['BI-desk-officer', 'BI-head-of-institution', 'BI-TP-committee-head', 'BI-TP-committee-member']))
						<li>
							<a href="{{ route('tf-bi-portal.t_p_nominations.index') }}" class="">
								<div class="menu-title"> <span class="fa fa-pencil-square-o"></span> TP Nomination </div>
							</a>
						</li>
					@endif

					@if (Auth()->user()->hasAnyRole(['BI-desk-officer', 'BI-head-of-institution', 'BI-CA-committee-head', 'BI-CA-committee-member']))
						<li>
							<a href="{{ route('tf-bi-portal.c_a_nominations.index') }}" class="">
								<div class="menu-title"> <span class="fa fa-pencil-square-o"></span> CA Nomination </div>
							</a>
						</li>
					@endif

					@if (Auth()->user()->hasAnyRole(['BI-desk-officer', 'BI-head-of-institution', 'BI-TSAS-committee-head', 'BI-TSAS-committee-member']))
						<li>
							<a href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}" class="">
								<div class="menu-title"> <span class="fa fa-pencil-square-o"></span> TSAS Nomination </div>
							</a>
						</li>
					@endif

					@if (Auth()->user()->hasAnyRole(['BI-desk-officer']))
						<li>
							<a href="{{ route('tf-bi-portal.nomination_settings.index') }}" class="">
								<div class="menu-title"> <span class="fa fa-tools"></span> ASTD Settings </div>
							</a>
						</li>
					@endif
					
			    </ul>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-desk-officer','BI-head-of-institution']))
			<li>
				<a href="{{ route('tf-bi-portal.monitoring') }}" class="">
					<div class="parent-icon"><i class='bx bx-location-plus'></i>
					</div>
					<div class="menu-title">Monitoring</div>
				</a>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-desk-officer']))
			<li>
				<a href="{{ route('tf-bi-portal.desk-officer') }}" class="">
					<div class="parent-icon"><i class='bx bx-devices'></i>
					</div>
					<div class="menu-title">Desk Officer Mgt</div>
				</a>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-librarian']))
			<li>
				<a href="{{ route('tf-bi-portal.librarian') }}" class="">
					<div class="parent-icon"><i class='bx bx-book-reader'></i>
					</div>
					<div class="menu-title">Librarian</div>
				</a>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-ict']))
			<li>
				<a href="{{ route('tf-bi-portal.dict') }}" class="">
					<div class="parent-icon"><i class='bx bx-dish'></i>
					</div>
					<div class="menu-title">Director ICT</div>
				</a>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-works']))
			<li>
				<a href="{{ route('tf-bi-portal.dworks') }}" class="">
					<div class="parent-icon"><i class='bx bx-layer'></i>
					</div>
					<div class="menu-title">Director PI & Works</div>
				</a>
			</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['BI-mgt','admin']))
				<li>
					<a href="{{ route('tf-bi-portal.beneficiaries.index') }}" class="">
						<div class="parent-icon"><i class='bx bx-intersect'></i>
						</div>
						<div class="menu-title">Beneficiary Mgt</div>
					</a>
				</li>
			@endif

			@if (Auth()->user()->hasAnyRole(['admin']))
				@php
					$menu_fc = \FoundationCore::get_menu_map();
				@endphp
			
				@each('layouts.default-app-template.menu-group', $menu_fc, 'children')
			@endif

		</ul>

   	</aside>
