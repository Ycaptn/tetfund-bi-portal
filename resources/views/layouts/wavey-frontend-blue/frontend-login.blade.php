@extends('layouts.wavey-frontend-blue.page-master-login')

@section('body')
    @yield('content')
@stop

@section('nav')
  @include('layouts.wavey-frontend-blue.nav')
@stop

@section('footer-login')
  @include('layouts.wavey-frontend-blue.footer-login')
@stop