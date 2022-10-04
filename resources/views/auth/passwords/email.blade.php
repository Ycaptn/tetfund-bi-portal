

@extends('layouts.frontend-login')

@section('content')
    <div class="flex justify-center h-2/3">      
        <div class=" bg-white shadow-md border border-blue-300 rounded-md w-full md:w-1/2 z-10 px-8 pt-6 pb-8 mb-4" >
            <div> <h3 class="block text-base text-gray-500 font-bold">{{ __('Reset Password') }}</h3></div>
                <hr class="mb-2">
                <div class="">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="">
                            <label for="email" class="block text-gray-500 text-sm font-normal p-2 mb-2">{{ __('E-Mail Address') }}</label>

                            <div class="">
                                <input id="email" type="email" class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="example@mail.com">

                                @error('email')
                                    <span class="text-red-700 px-4 py-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <div class="mt-3 ">
                                <button type="submit" class="bg-blue hover:bg-blue-500 text-white font-bold py-2 px-5 rounded">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('page_scripts')

    <script >
        $(document).ready(function() {
            
            if ( window.location.pathname == '/password/reset' ) {
                $('#home').removeClass('active');
            }
        });

            
    </script>
    
@endpush