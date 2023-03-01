@extends('layouts.app')

@section('app_css')
    <style type="text/css">
        /* Style the tab */
        .tab {
          overflow: hidden;
          border: 1px solid #ccc;
          background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
          background-color: inherit;
          float: left;
          border: none;
          outline: none;
          cursor: pointer;
          padding: 14px 16px;
          transition: 0.3s;
          font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
          background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
          background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
          display: none;
          padding: 6px 12px;
          border: 1px solid #ccc;
          border-top: none;
        }
    </style>
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
        class="btn btn-sm btn-primary btn-new-mdl-beneficiary-member-modal" href="#">
        <i class="fa fa-eye"></i> New
    </a>

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-beneficiary-member-modal" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.beneficiaries.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body">
            <div class="row col-sm-12">
                @include('tf-bi-portal::pages.beneficiaries.modal') 
                @include('tf-bi-portal::pages.beneficiaries.show_fields')
            </div>
            <div class="col-lg-12">
                <div class="tab pb-2 mt-3" style="border-top: thin solid lightgray; border-bottom: thin solid lightgray;">
                    <ul class="nav">
                        <li class="mt-3" style="margin-right: 3px;">
                            <a href="#?beneficiary_members=true" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event,'beneficiary_details')" id="defaultOpen">
                                Members
                            </a>                        
                        </li>
                        <li class="mt-3" style="margin-right: 3px;">                            
                            <a href="#?submissions=submissions" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event, 'submissions')">
                                Submissions
                            </a>
                        </li>
                        <li class="mt-3" style="margin-right: 3px;">                                
                            <a href="#?nominations=nominations" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event, 'nominations')">
                                Nominations
                            </a>
                        </li>
                    </ul>
                </div>


            {{-- sub menu contents details--}}
                <div id="beneficiary_details" class="tabcontent">
                    <div class="col-sm-12 panel panel-default card-view">
                        <h5 class="pt-2"> 
                            <strong>
                                Beneficiary User 
                            </strong>
                             <a title="Create New Beneficiary Member" class="btn btn-primary btn-sm pull-right btn-new-beneficiary-member" href="#">
                                <span class="fa fa-plus"></span> <small>Add User</small>
                            </a>
                        </h5>
                        @include('tf-bi-portal::pages.beneficiaries.table')
                        @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_member_modal')
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
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
    <script type="text/javascript">
        
        // function handling menu toggle items
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }


        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
        
    </script>
@endpush