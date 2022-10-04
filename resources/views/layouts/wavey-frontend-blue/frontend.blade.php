@extends('layouts.wavey-frontend-blue.page-master')

@section('body')
    @yield('content')
@stop

@section('nav')
  @include('layouts.wavey-frontend-blue.nav')
@stop

@section('footer')
  @include('layouts.wavey-frontend-blue.footer')
@stop