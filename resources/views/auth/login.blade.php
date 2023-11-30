@php
    //source Bims access credentials

    $clientId = (env('BIMS_CLIENT_ID')) ? env('BIMS_CLIENT_ID') : '';

    $redirectUrl = (env('BIMS_REDIRECT_URI')) ? env('BIMS_REDIRECT_URI') : '';
    
    $state = '';
    if(Session::has('state')) {
        $state = Session::get('state');
    } else {
        $length = 32;
        $state = Session::put('state', bin2hex(random_bytes($length/2)));
        $state = Session::get('state');
    }
@endphp

@extends('layouts.frontend-login')

@section('content')


    <div >
        <div class="flex justify-center" role="alert">
            @if ($errors->any())
                    <div class="text-center bg-red-100 border border-red-400 text-red-700 px-4 pb-3 w-full md:w-1/2 m-4 rounded relative">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <h4 class="block"><i class="icon fa fa-warning"></i> Errors!</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="mb-2">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
            
            
                @if ($message = Session::get('warning'))
                    <div class="alert alert-warning alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
            
            
                @if ($message = Session::get('info'))
                    <div class="alert alert-info alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
            
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
        </div>

        <div class="flex justify-center" style="margin-top: 15px;" >      
            <div class=" bg-white shadow-md border border-blue-300 rounded-md w-full md:w-3/4 z-10 px-8 pt-6 pb-8 mb-4" >
                
                <div class="mb-4 flex justify-center md:hidden">
                    <img src="{{ asset('imgs/tetfundlogo1.png') }}" width="110vw" alt="TETFund Logo" />
                </div>
                
                @if(env('BIMS_CLIENT_ID') && env('BIMS_IS_ENABLED') == true && env('BIMS_REDIRECT_URI') != null)
                    <div class="mb-8" style="width: 100%;">
                        <div class="flex flex-row justify-center mb-4">
                            <a href="https://bims.tetfund.gov.ng/oauth/authorize?response_type=code&client_id={{$clientId}}&redirect_uri={{$redirectUrl}}&state={{$state}}" role="button" style="border-radius:10px;">
                                <button class="bg-limegreen hover:bg-green-500 rounded text-white font-bold py-2 px-5">
                                    <div class="">
                                        <img src="{{asset('imgs/bims.png')}}" style="width: 80px; height: 30px;" alt="">
                                    </div>
                                    <div class="" >
                                        <span><small>Continue with BIMS</small></span>
                                    </div>
                                </button>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- form to be toggled -->
                <div class=""> 
                    <form method="post" action="{{ url('/login') }}">
                            @csrf
                        <div class="mb-4 ">
                            <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="username">
                                {{ __('E-Mail Address') }}
                            </label>
                            <input 
                                    type="email" 
                                    id="email" 
                                    class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('email') is-invalid @enderror" 
                                    name="email" 
                                    value= "{{ old('email') }}" 
                                    required 
                                    autocomplete="email" 
                                    autofocus 
                                    placeholder="example@mail.com"
                                >
                                <!-- @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror -->
                        </div>
                        <div class="mb-6">
                            <label class="block text-grey-700 text-sm font-bold mb-2" for="password">
                                Password
                            </label>
                            <div class="input-group flex">
                               <input 
                                    type="password" 
                                    id="password" 
                                    class="form-control shadow appearance-none border border-red rounded py-2 px-2 w-full md:w-full text-gray-700 mb-3 @error('password') is-invalid @enderror" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"   
                                    placeholder="******************"
                                >
                                <div>
                                    <span class="input-group-text">
                                        <a href="#" class="toggle_hide_password">
                                            <i 
                                                class="fas fa-eye" 
                                                aria-hidden="true" 
                                                style="margin-left: -30px; cursor: pointer; margin-top:13px;" 
                                                >
                                            </i>
                                        </a>
                                    </span>
                                </div>
                                {{-- <span class="toggle_hide_password">
                                    <span class="show-password">Show</span>
                                <span class="hide-password">hide</span></span> --}}
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  
                            </div>
                           
                        </div>
                        <div class="md:flex md:items-center justify-between">
                            <div>
                                <input
                                    type="checkbox" 
                                    name="remember" 
                                    id="remember" 
                                    {{ old('remember') ? 'checked' : '' }} 
                                />
                                <label class="ml-1 md:inline-block">Keep me logged in</label>  
                            </div>
                            <div>
                                <a 
                                    class="md:inline-block block align-baseline font-bold text-sm text-green hover:text-blue-darker ml-6"
                                    href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                        <div class=" pt-3 md:w-2/4">
                            <button class="bg-green hover:bg-limegreen-500 text-white font-bold py-2 px-5 rounded" type="submit">
                                Log in
                            </button>
                        </div>
                    </form>
                </div>
                <!-- end of login for to be toggled -->


                <p class="pt-6 text-sm" style="color:green; font-size:80%">
                    For <b>National Research Fund (NRF)</b> submissions, please use the <a href="https://nrf.tetfund.gov.ng" style="color:green;" target="_blank"><b><u>NRF Portal</u></b></a> to process your submissions. 
                    <span style="color:red;display:inline;">
                    For Technical usage support: <br> ICT Support Intervention (08037776194, 08140148722, 08051984832), TSAS ,CA, TP (07064559694), Library Development (07030086349), AMB Intervention (07062539752) </b>.
                    </span>
                </p>
    

        </div>
    <div>

@endsection

@push('page_scripts')

<script >
    $(document).ready(function() {
        
        if ( window.location.pathname == '/login' ) {
             $('#login').addClass('active');
            $('#login').siblings().removeClass('active');
        }
          $(".toggle_hide_password").on('click', function(e) {
            e.preventDefault()

            // get input group of clicked link
            const input_group = $(this).closest('.input-group')

            // find the input, within the input group
            const input = input_group.find('input.form-control')

            // find the icon, within the input group
            const icon = input_group.find('i')

            // toggle field type
            input.attr('type', input.attr("type") === "text" ? 'password' : 'text')

            // toggle icon class
            icon.toggleClass('fa-eye fa-eye-slash')
        })

    });


        
</script>
    
@endpush



