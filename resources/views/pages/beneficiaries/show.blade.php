@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Beneficiary
@stop

@section('page_title')
Beneficiary
@stop

@section('page_title_suffix')
{{$beneficiary->title}}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.beneficiaries.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to Beneficiary List
</a>
@stop

@section('page_title_buttons')

    {{-- <a data-toggle="tooltip" 
        title="New" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-beneficiary-modal" href="#">
        <i class="fa fa-eye"></i> New
    </a>

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-beneficiary-modal" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.beneficiaries.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="row col-sm-12">
                @include('tf-bi-portal::pages.beneficiaries.modal') 
                @include('tf-bi-portal::pages.beneficiaries.show_fields')
            </div>
            <div class="col-lg-12">
                <br><br><h5 class="text-center pt-2" style="border-top: 1px solid lightgray;"> Beneficiary Members </h5><hr>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    S/N
                                </th>
                                <th>
                                    Full Name
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Phone
                                </th>
                                <th>
                                    Roles
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($beneficiary_members) && count($beneficiary_members) > 0)
                                @php
                                    $counter = 0;
                                @endphp
                                @foreach($beneficiary_members as $beneficiary_member)
                                    <tr>
                                        <td>
                                            <b>{{ $counter += 1 }}). </b>  
                                        </td>
                                        <td>
                                            {{ $beneficiary_member->user->fullname }}
                                        </td>
                                        <td>
                                            {{ $beneficiary_member->user->email }}
                                        </td>
                                        <td>
                                            {{ $beneficiary_member->user->telephone }}
                                        </td>
                                        <td>
                                            @php
                                                $user_roles = $beneficiary_member->user->roles()->pluck('name')->toArray();
                                            @endphp
                                            @if(count($user_roles) > 0)
                                                @foreach($user_roles as $user_role)
                                                    <b>||</b>
                                                    {{ucwords($user_role)}}
                                                    <b>||</b>
                                                    <br>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr> 
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            This is the help message.
            This is the help message.
            This is the help message.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush