@extends('layouts.frontend-login')

@section('content')
    <div class="">

        <div class="flex justify-center" style="margin-top: 15px;">
            <div class=" bg-white shadow-md border border-blue-300 rounded-md w-full md:w-3/4 z-10 px-8 pt-6 pb-8 mb-4">

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4 ">
                            <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="email">
                                {{ __('E-Mail Address') }}
                            </label>


                            <input id="email" type="email"
                                name="email"
                                class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('email') is-invalid @enderror"
                                value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>


                        <div class="mb-4 ">
                            <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="password">
                                {{ __('Password') }}
                            </label>


                            <input id="password" type="password"
                                class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('email') is-invalid @enderror"
                                name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="mb-4 ">
                                <label for="password-confirm"
                                    class="block text-gray-500 text-sm font-bold p-2 mb-2 ">{{ __('Confirm Password') }}</label>


                                <input id="password-confirm" type="password"
                                    class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('email') is-invalid @enderror"
                                    name="password_confirmation" required autocomplete="new-password">

                            </div>

                            <div class=" pt-3 md:w-2/4">
                                <button type="submit"
                                    class="bg-green hover:bg-limegreen-500 text-white font-bold py-2 px-5 rounded">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
@endsection
