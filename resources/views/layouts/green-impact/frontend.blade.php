@extends('layouts.green-impact.master')

@section('errors')
  @include('layouts.green-impact.errors')
@stop

@section('body')
    @yield('content')
@stop

@section('nav')
  @include('layouts.green-impact.nav')
@stop

@section('footer')
  @include('layouts.green-impact.footer')
@stop