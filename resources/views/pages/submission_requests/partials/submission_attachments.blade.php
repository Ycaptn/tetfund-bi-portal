<div class="col-sm-12">
    <table class="table table-striped  well well-sm">
        <thead class="thead-dark">
             <tr>
                <th scope="col">S/N </th>
                <th scope="col">Attachment Description</th>
                <th scope="col">Document(s) </th>
             </tr>
        </thead>
        <tbody>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="{{ route('tf-bi-portal.processSubmissionRequestAttachment', ['id'=>$submissionRequest->id] ) }}">

                {{ csrf_field() }}

                @php
                    $x = 0;
                    $checklist_input_fields = "";
                @endphp
                @if (isset($checklist_items) )
                    @foreach ($checklist_items as $item)
                        @php
                            $checklist = "checklist-".$item->id;
                            $checklist_input_fields .= ($checklist_input_fields == "") ? $checklist : ','.$checklist;
                            
                            $submission_attachment = array_reduce($allSubmissionAttachments??[], function($response, $attached) use ($item) {

                                $limited_slugged_label = Str::slug($item->item_label);
                                $limited_slugged_label = Str::limit($limited_slugged_label, 495, "");
                                $attached['label'] == $limited_slugged_label || $attached['label'] ==  Str::limit($item->item_label ,495, "") ? $response = $attached : null;
                                return $response;
                             });

                            if($submission_attachment==null && $submissionRequest->status!='not-submitted') {
                                continue;
                            }
                        @endphp

                        <tr>
                            <th scope="row"> {{ $x+=1 }}</th>
                            <td>{{ $item->item_label }}</td>
                            <td width="50%">
                                <div class="input-group">
                                    @if($submission_attachment != null)
                                        @php
                                            $preview_label = str_replace('auditclearancefinalpaymentchecklist-', '', $submission_attachment->label);
                                            $preview_label = str_replace('auditclearancefinalpaymentchecklist-', '', $preview_label);
                                            $preview_label = ucwords($preview_label);
                                        @endphp
                                        <div class="col-sm-12">
                                            <a href="{{ route('fc.attachment.show', $submission_attachment->id) }}" target="__blank" title="Preview this Attachment">{{ $preview_label }}</a> &nbsp; &nbsp;
                                            @if($submissionRequest->status == 'not-submitted')
                                                <a data-toggle="tooltip" 
                                                    title="Delete this Attachment"
                                                    data-val='{{$submission_attachment->label}}'
                                                    class="pull-right text-danger btn-delete-mdl-submissionRequest-attachement"
                                                    href="#">
                                                    <span class="fa fa-trash"></span>
                                                </a>
                                            @endif
                                            <br>
                                            <small><i>{{ ucwords($submission_attachment->description) }}</i></small>
                                        </div>
                                    @else
                                        <div class="{{ $errors->has($checklist) ? ' has-error' : '' }} col-sm-12" >
                                            <input type='file' class="form-control" name="{{$checklist}}" />
                                        </div>
                                    @endif
                                </div>
                                <em><small class="" style="color: red;"> Max file Size 100M (Required)</small></em>
                            </td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <th>{{ $x+=1 }}</th>
                    <td>
                        @php                            
                            $submission_attachment_addition = array_reduce($allSubmissionAttachments??[], function($response, $attached){
                                str_contains($attached['label'], 'Additional Attachment') ? $response = $attached : null;
                                return $response;
                            });
                        @endphp
                        <div class="{{ $errors->has('additional_attachment_name') ? 'has-error' : '' }}" >
                                <input 
                                    type='text' 
                                    class="form-control" 
                                    name="additional_attachment_name"
                                    {{ $submissionRequest->status!='not-submitted' && $submission_attachment_addition==null ? "disabled='disabled'" : '' }}
                                    placeholder="Enter name for Additional Attachment" 
                                    value="{{ ($submission_attachment_addition != null) ? $submission_attachment_addition->label : '' }}" 
                                    {{ ($submission_attachment_addition != null) ? "disabled='disabled'" : '' }}
                                />
                        </div>
                    </td>
                    <td width="50%">
                        <div class="input-group">
                            @if($submission_attachment_addition != null)
                                <div class="col-sm-12">
                                    <a href="{{ route('fc.attachment.show', $submission_attachment_addition->id) }}"
                                        target="__blank"
                                        title="Preview this Attachment">
                                        {{ ucwords($submission_attachment_addition->label) }}
                                    </a> &nbsp; &nbsp;

                                    @if($submissionRequest->status == 'not-submitted')
                                        <a data-toggle="tooltip" 
                                            title="Delete this Attachment"
                                            data-val='{{$submission_attachment_addition->label}}'
                                            class="pull-right text-danger btn-delete-mdl-submissionRequest-attachement"
                                            href="#">
                                            <span class="fa fa-trash"></span>
                                        </a>
                                    @endif
                                    <br>
                                    <small><i>{{ ucwords($submission_attachment_addition->description) }}</i></small>
                                </div>
                            @else
                                <div class="{{ $errors->has('additional_attachment') ? ' has-error' : '' }} col-sm-12" >
                                    <input type='file' class="form-control" name="additional_attachment" id="additional_attachment"
                                    {{ $submissionRequest->status!='not-submitted' && 
                                    $submission_attachment_addition==null ? 
                                    "disabled='disabled'" : '' }}
                                     />
                                </div>
                            @endif
                        </div>
                        <em><small class="" style="color: red;"> Max file Size 100M (Optional) </small></em>
                    </td>
                </tr>


                <tr>
                    @if($submissionRequest->status == 'not-submitted')
                        <td></td>
                        <th>
                            <input type='hidden' class="form-control" name="intervention_line" value="{{$intervention->id}}" />
                            <input type='hidden' class="form-control" name="intervention_line_name" value="{{$intervention->name}}" />
                            <input type='hidden' class="form-control" name="submission_request_id" value="{{$submissionRequest->id}}" />
                            <input type='hidden' class="form-control" name="organization_id" value="{{ auth()->user()->organization_id }}" />
                            <input type='hidden' class="form-control" name="intervention_request_tranche" value="{{optional($submissionRequest)->tranche}}" />
                            <input type='hidden' class="form-control" name="checklist_input_fields" value="{{$checklist_input_fields}}" />
                
                            <button type="submit" class="btn btn-sm btn-primary"> <span class="glyphicon glyphicon-ok"></span> &nbsp; Save Attachments </button>
                
                            <a href="{{ route('tf-bi-portal.submissionRequests.show',$submissionRequest->id) }}">
                                <button type="button" class="btn btn-sm btn-warning"> <span class="glyphicon glyphicon-remove"></span> Cancel </button>
                            </a>
                        </th>
                    @endif
                </tr>
            </form>
        </tbody>
    </table>
</div>

@push('page_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        
        //Delete action attachement
        $(document).on('click', ".btn-delete-mdl-submissionRequest-attachement", function(e) {
            e.preventDefault();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            //check for internet status 
            if (!window.navigator.onLine) {
                $('.offline-submission_requests').fadeIn(300);
                return;
            }else{
                $('.offline-submission_requests').fadeOut(300);
            }

            let submissionRequestId = "{{ isset($submissionRequest->id) ? $submissionRequest->id : '' }}";
            let attachment_label = $(this).attr('data-val');
            swal({
                title: "Are you sure you want to delete this SubmissionRequest Attachment?",
                text: "You will not be able to recover this Attachment if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Deleting...',
                        text: 'Please wait while SubmissionRequest Attachment is being deleted <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

                    let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.destroy','') }}/"+submissionRequestId;

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'DELETE');
                    formData.append('submissionRequestId', submissionRequestId);
                    formData.append('attachment_label', attachment_label);
                    
                    $.ajax({
                        url:endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result){
                            if(result.errors){
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                    title: "Deleted",
                                    text: "SubmissionRequest Attachment deleted successfully",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false
                                });
                                location.reload(true);
                            }
                        }, error: function(data){
                            console.log(data);
                            swal("Error", "Oops an error occurred. Please try again.", "error");

                            $(".offline-submission_requests").hide();
                        }
                    });
                }
            });
        });
    });
</script>
@endpush