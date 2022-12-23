@extends('layouts.app')

@section('title_postfix')
Desk Officer Administration
@stop

@section('page_title')
 Settings
@stop

@section('page_title_suffix')
Beneficiary Admin Panel
@stop

@section('app_css')
@stop

@section('page_title_buttons')
@stop

@section('page_title_subtext')
@stop

@section('content')
 
  @php
    $tp_nomination_sent_to_exist = array_key_exists('tp_nomination_sent_to', $astd_settings) ? $astd_settings['tp_nomination_sent_to'] : "";
    $tp_committee_considered_sent_to_exist = array_key_exists('tp_committee_considered_sent_to', $astd_settings) ? $astd_settings['tp_committee_considered_sent_to'] : "";
    $tp_approved_nomination_sent_to_exist = array_key_exists('tp_approved_nomination_sent_to', $astd_settings) ? $astd_settings['tp_approved_nomination_sent_to'] : "";

    $ca_nomination_sent_to_exist = array_key_exists('ca_nomination_sent_to', $astd_settings) ? $astd_settings['ca_nomination_sent_to'] : "";
    $ca_committee_considered_sent_to_exist = array_key_exists('ca_committee_considered_sent_to', $astd_settings) ? $astd_settings['ca_committee_considered_sent_to'] : "";
    $ca_approved_nomination_sent_to_exist = array_key_exists('ca_approved_nomination_sent_to', $astd_settings) ? $astd_settings['ca_approved_nomination_sent_to'] : "";


    $tsas_nomination_sent_to_exist = array_key_exists('tsas_nomination_sent_to', $astd_settings) ? $astd_settings['tsas_nomination_sent_to'] : "";
    $tsas_committee_considered_sent_to_exist = array_key_exists('tsas_committee_considered_sent_to', $astd_settings) ? $astd_settings['tsas_committee_considered_sent_to'] : "";
    $tsas_approved_nomination_sent_to_exist = array_key_exists('tsas_approved_nomination_sent_to', $astd_settings) ? $astd_settings['tsas_approved_nomination_sent_to'] : "";
  @endphp

{{-- {{ dd($tp_nomination_sent_to_exist) }} --}}
    <div class="card radius-5 border-top border-0 border-3 border-success">

        <div class="d-flex align-items-start mt-4">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <button class="nav-link active" id="v-pills-teaching-practice-tab" data-bs-toggle="pill" data-bs-target="#v-pills-teaching-practice" type="button" role="tab" aria-controls="v-pills-teaching-practice" aria-selected="true">Teaching Practice</button>
              <button class="nav-link" id="v-pills-conference-attendance-tab" data-bs-toggle="pill" data-bs-target="#v-pills-conference-attendance" type="button" role="tab" aria-controls="v-pills-conference-attendance" aria-selected="false">Conference Attendance</button>
              <button class="nav-link" id="v-pills-tsas-tab" data-bs-toggle="pill" data-bs-target="#v-pills-tsas" type="button" role="tab" aria-controls="v-pills-tsas" aria-selected="false">TSAS</button>
            </div>
            <div class="tab-content" id="v-pills-tabContent">
              <div class="tab-pane fade show active" id="v-pills-teaching-practice" role="tabpanel" aria-labelledby="v-pills-teaching-practice-tab">
                <div class="row">
                    <div class="col-12  m-2"> TP Nomination Requests Sent To: 

                      @if (($tp_nomination_sent_to_exist))
                         <span> <strong> {{ $tp_nomination_sent_to_exist->value }} </strong> </span>
                      @else
                         <span class="text-danger"> <strong>  No Value </strong> </span>
                      @endif
                     
                      
                      <button class="btn btn-sm astd-settings" data-edit-key="tp_nomination_sent_to"
                        data-astd-setting="{{ ($tp_nomination_sent_to_exist) ? $tp_nomination_sent_to_exist->value : "" }}"  
                        data-key-status="{{ ($tp_nomination_sent_to_exist) ? "1" : "0" }}" 
                        data-key-id="{{ ($tp_nomination_sent_to_exist) ? $tp_nomination_sent_to_exist->id : "0" }}" data-edit-label="TP Nomination Requests Sent To"> <i class="fas fa-edit"></i>  
                      </button> 
                     
                    </div>
                    <div class="col-12 m-2"> TP Committee Considered Requests Sent To: 

                      @if (($tp_committee_considered_sent_to_exist))
                         <span> <strong> {{ $tp_committee_considered_sent_to_exist->value }} </strong> </span>
                      @else
                         <span class="text-danger"> <strong>  No Value </strong> </span>
                      @endif
                     
                      
                      <button class="btn btn-sm astd-settings" data-edit-key="tp_committee_considered_sent_to"
                        data-astd-setting="{{ ($tp_committee_considered_sent_to_exist) ? $tp_committee_considered_sent_to_exist->value : "" }}"  
                        data-key-status="{{ ($tp_committee_considered_sent_to_exist) ? "1" : "0" }}" 
                        data-key-id="{{ ($tp_committee_considered_sent_to_exist) ? $tp_committee_considered_sent_to_exist->id : "0" }}" data-edit-label="TP Committee Considered Requests Sent To"> <i class="fas fa-edit"></i>  
                      </button> 

                    </div>
                    <div class="col-12 m-2"> TP Approved Nomination Requests Sent To: 

                      @if (($tp_approved_nomination_sent_to_exist))
                         <span> <strong> {{ $tp_approved_nomination_sent_to_exist->value }} </strong> </span>
                      @else
                         <span class="text-danger"> <strong>  No Value </strong> </span>
                      @endif
                      
                      <button class="btn btn-sm astd-settings" data-edit-key="tp_approved_nomination_sent_to"
                        data-astd-setting="{{ ($tp_approved_nomination_sent_to_exist) ? $tp_approved_nomination_sent_to_exist->value : "" }}"  
                        data-key-status="{{ ($tp_approved_nomination_sent_to_exist) ? "1" : "0" }}" 
                        data-key-id="{{ ($tp_approved_nomination_sent_to_exist) ? $tp_approved_nomination_sent_to_exist->id : "0" }}" data-edit-label="TP Approved Nomination Requests Sent To"> <i class="fas fa-edit"></i>  
                      </button> 

                    </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-pills-conference-attendance" role="tabpanel" aria-labelledby="v-pills-conference-attendance-tab">
                <div class="row">
                  <div class="col-12  m-2"> Conference Attendance Nomination Requests Sent To: 

                    @if (($ca_nomination_sent_to_exist))
                       <span> <strong> {{ $ca_nomination_sent_to_exist->value }} </strong> </span>
                    @else
                       <span class="text-danger"> <strong>  No Value </strong> </span>
                    @endif
                   
                    
                    <button class="btn btn-sm astd-settings" data-edit-key="ca_nomination_sent_to"
                      data-astd-setting="{{ ($ca_nomination_sent_to_exist) ? $ca_nomination_sent_to_exist->value : "" }}"  
                      data-key-status="{{ ($ca_nomination_sent_to_exist) ? "1" : "0" }}" 
                      data-key-id="{{ ($ca_nomination_sent_to_exist) ? $ca_nomination_sent_to_exist->id : "0" }}" data-edit-label="CA Nomination Requests Sent To"> <i class="fas fa-edit"></i>  
                    </button> 
                   
                  </div>
                  <div class="col-12 m-2"> Conference Attendance Committee Considered Requests Sent To: 

                    @if (($ca_committee_considered_sent_to_exist))
                       <span> <strong> {{ $ca_committee_considered_sent_to_exist->value }} </strong> </span>
                    @else
                       <span class="text-danger"> <strong>  No Value </strong> </span>
                    @endif
                   
                    
                    <button class="btn btn-sm astd-settings" data-edit-key="ca_committee_considered_sent_to"
                      data-astd-setting="{{ ($ca_committee_considered_sent_to_exist) ? $ca_committee_considered_sent_to_exist->value : "" }}"  
                      data-key-status="{{ ($ca_committee_considered_sent_to_exist) ? "1" : "0" }}" 
                      data-key-id="{{ ($ca_committee_considered_sent_to_exist) ? $ca_committee_considered_sent_to_exist->id : "0" }}" data-edit-label="CA Committee Considered Requests Sent To"> <i class="fas fa-edit"></i>  
                    </button> 

                  </div>
                  <div class="col-12 m-2"> Conference Attendance Approved Nomination Requests Sent To: 

                    @if (($ca_approved_nomination_sent_to_exist))
                       <span> <strong> {{ $ca_approved_nomination_sent_to_exist->value }} </strong> </span>
                    @else
                       <span class="text-danger"> <strong>  No Value </strong> </span>
                    @endif
                    
                    <button class="btn btn-sm astd-settings" data-edit-key="ca_approved_nomination_sent_to"
                      data-astd-setting="{{ ($ca_approved_nomination_sent_to_exist) ? $ca_approved_nomination_sent_to_exist->value : "" }}"  
                      data-key-status="{{ ($ca_approved_nomination_sent_to_exist) ? "1" : "0" }}" 
                      data-key-id="{{ ($ca_approved_nomination_sent_to_exist) ? $ca_approved_nomination_sent_to_exist->id : "0" }}" data-edit-label="CA Approved Nomination Requests Sent To"> <i class="fas fa-edit"></i>  
                    </button> 

                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-pills-tsas" role="tabpanel" aria-labelledby="v-pills-tsas-tab">

                <div class="row">
                  <div class="col-12  m-2"> TSAS Nomination Requests Sent To: 

                    @if (($tsas_nomination_sent_to_exist))
                       <span> <strong> {{ $tsas_nomination_sent_to_exist->value }} </strong> </span>
                    @else
                       <span class="text-danger"> <strong>  No Value </strong> </span>
                    @endif
                   
                    
                    <button class="btn btn-sm astd-settings" data-edit-key="tsas_nomination_sent_to"
                      data-astd-setting="{{ ($tsas_nomination_sent_to_exist) ? $tsas_nomination_sent_to_exist->value : "" }}"  
                      data-key-status="{{ ($tsas_nomination_sent_to_exist) ? "1" : "0" }}" 
                      data-key-id="{{ ($tsas_nomination_sent_to_exist) ? $tsas_nomination_sent_to_exist->id : "0" }}" data-edit-label="TSAS Nomination Requests Sent To"> <i class="fas fa-edit"></i>  
                    </button> 
                   
                  </div>
                  <div class="col-12 m-2"> TSAS Attendance Committee Considered Requests Sent To: 

                    @if (($tsas_committee_considered_sent_to_exist))
                       <span> <strong> {{ $tsas_committee_considered_sent_to_exist->value }} </strong> </span>
                    @else
                       <span class="text-danger"> <strong>  No Value </strong> </span>
                    @endif
                   
                    
                    <button class="btn btn-sm astd-settings" data-edit-key="tsas_committee_considered_sent_to"
                      data-astd-setting="{{ ($tsas_committee_considered_sent_to_exist) ? $tsas_committee_considered_sent_to_exist->value : "" }}"  
                      data-key-status="{{ ($tsas_committee_considered_sent_to_exist) ? "1" : "0" }}" 
                      data-key-id="{{ ($tsas_committee_considered_sent_to_exist) ? $tsas_committee_considered_sent_to_exist->id : "0" }}" data-edit-label="TSAS Committee Considered Requests Sent To"> <i class="fas fa-edit"></i>  
                    </button> 

                  </div>
                  <div class="col-12 m-2"> TSAS Approved Nomination Requests Sent To: 

                    @if (($tsas_approved_nomination_sent_to_exist))
                       <span> <strong> {{ $tsas_approved_nomination_sent_to_exist->value }} </strong> </span>
                    @else
                       <span class="text-danger"> <strong>  No Value </strong> </span>
                    @endif
                    
                    <button class="btn btn-sm astd-settings" data-edit-key="tsas_approved_nomination_sent_to"
                      data-astd-setting="{{ ($tsas_approved_nomination_sent_to_exist) ? $tsas_approved_nomination_sent_to_exist->value : "" }}"  
                      data-key-status="{{ ($tsas_approved_nomination_sent_to_exist) ? "1" : "0" }}" 
                      data-key-id="{{ ($tsas_approved_nomination_sent_to_exist) ? $tsas_approved_nomination_sent_to_exist->id : "0" }}" data-edit-label="TSAS Approved Nomination Requests Sent To"> <i class="fas fa-edit"></i>  
                    </button> 

                  </div>
                </div>



              </div>
            </div>
          </div>
        
    </div>

    @include('tf-bi-portal::pages.nomination_settings.modals.setting')

@stop


@section('side-panel')

@stop

@push('page_scripts')
    <script type="text/javascript">
      $(document).ready(function() {

        //Show Modal for New Entry
        $(document).on('click', ".astd-settings", function(e) {
          const label = $(this).attr('data-edit-label');
          const status = $(this).attr('data-key-status');
          const key = $(this).attr('data-edit-key');
          const id = $(this).attr('data-key-id');
          const astd_setting = $(this).attr('data-astd-setting');
          $('#astd-setting-label').text(label);
          $('#setting-status').val(status);
          $('#setting-key').val(key);
          $('#setting-id').val(id);
          $('#astd-setting-role').val(astd_setting);
          $('#astd-setting-spinner').hide();
          $('#astd-setting-modal').modal('show');
        });

        //Save details
        $('#save-astd-setting').click(function(e) {
            e.preventDefault();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $('#astd-setting-spinner').show();
            $("#save-astd-setting").attr('disabled', true);

            let actionType = "POST";
            let endPointUrl = "{{ route('tf-bi-portal.nomination_settings.store') }}";
          // let primaryId = $('#txt-beneficiary-primary-id').val();
            
            let formData = new FormData();
            formData.append('_token', $('input[name="_token"]').val());
            
            formData.append('_method', actionType);
            formData.append('key',$('#setting-key').val());
            formData.append('value',$('#astd-setting-role').val());
            formData.append('status',$('#setting-status').val());
            formData.append('id',$('#setting-id').val());

            $.ajax({
                url:endPointUrl,
                type: "POST",
                data: formData,
                cache: false,
                processData:false,
                contentType: false,
                dataType: 'json',
                success: function(result){
                  console.log(result.responseJSON);
                  if(result.errors){
                    $('#div-astd-setting-modal-error').html('');
                    $('#div-astd-setting-modal-error').show();
                      $.each(result.errors, function(key, value){
                          $('#div-astd-setting-modal-error').append('<li class="text-danger">'+value+'</li>');
                      });
                  }else{
                      $('#div-astd-setting-modal-error').hide();
                      swal({
                            title: "Saved",
                            text: "Setting Record Successfully updated",
                            type: "success"
                          });
                      window.setTimeout( function(){
                        location.reload(true);
                      },20); 
                  }

                  $("#astd-setting-spinner").hide();
                  $("#save-astd-setting").attr('disabled', false);
                    
                }, error: function(data){
                    console.log(data);
                    swal("Error", "Oops an error occurred. Please try again.", "error");

                    $("#astd-setting-spinner").hide();
                    $("#save-astd-setting").attr('disabled', false);

                }
            });
        });


      });
    </script>
@endpush