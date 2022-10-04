    <!-- Start Header Area -->
    <header class="header navbar-area shadow-sm border-top-2 border-success" style="border-top-style: solid;">
        <!-- Toolbar Start -->
        {{-- <div class="toolbar-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="toolbar-social">
                            <ul>
                                <li>
                                    <span class="title">
                                        <i class="lni lni-pin"></i>
                                    </span>
                                </li>
                                <li>
                                    <span class="title">
                                        <i class="lni lni-phone"></i>
                                    </span>
                                </li>
                                <li>
                                    <span class="title">
                                        <i class="lni lni-envelope">
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="toolbar-login">
                            <div class="button">
                                <a href="{{ route("login") }}" class="btn">Log In</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Toolbar End -->
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="nav-inner">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand ms-auto" href="{{ route("home") }}">
                                {{-- <img src="assets/images/logo/logo.svg" alt="Logo"> --}}
                                {{-- <img src="{{ asset('imgs/logo.png') }}" style="display:inline" /> --}}
                                <h3 style="color:black;display:inline;margin-top:3px;">
                                    Impact<span style="color:green;">@</span>TETFund
                                </h3>
                            </a>
                            <button class="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ms-auto">
                                    <li class="nav-item"><a class="active" href="{{ route("home") }}">Home</a></li>
                                    <li class="nav-item"><a target="_blank" href="https://tetfund.gov.ng">TETFund</a></li>
                                    <li class="nav-item"><a target="_blank" href="https://tetfund.gov.ng/index.php/mandate-objectives/">About TETFund</a></li>
                                    <li class="nav-item"><a  target="_blank" href="https://tetfund.gov.ng/index.php/special-intervention/physical-infrastructure-program/">Interventions</a></li>

                                    @if (\Auth()->user() == null)
                                    <li class="nav-item"><a href="{{ route("login") }}">Login</a></li>
                                    @else
                                    <li class="nav-item">
                                        <a href="{{ route("dashboard") }}">{{\Auth()->user()->full_name}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="lni lni-power-switch text-danger"></i> &nbsp; Logout</a>
                                    </li>
                                    @endif
                                </ul>
                                <form id="logout-form" action="{{route('logout')}}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div> <!-- navbar collapse -->
                        </nav> <!-- navbar -->
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    <!-- End Header Area -->