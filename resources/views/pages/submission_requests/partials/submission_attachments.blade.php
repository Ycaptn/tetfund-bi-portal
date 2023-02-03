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
            {{-- route('beneficiary-checklist-submit',$submissionRequest->id) --}}
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="{{ route('tf-bi-portal.processSubmissionRequestAttachment', ['id'=>$submissionRequest->id] ) }}" >

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
                        @endphp

                        <tr>
                            <th scope="row"> {{ $x+=1 }}</th>
                            <td>{{ $item->item_label }}</td>
                            <td width="50%">
                                <div class="input-group">
                                    @php
                                        $submission_attachment = $submissionRequest->get_specific_attachment($submissionRequest->id, $item->item_label);
                                    @endphp
                                    @if($submission_attachment != null)
                                        <div class="col-sm-12">
                                            <a href="{{ route('fc.attachment.show', $submission_attachment->id) }}" target="__blank" title="Preview this Attachment">{{ ucwords($submission_attachment->label) }}</a> &nbsp; &nbsp;
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
                            $submission_attachment_addition = $submissionRequest->get_specific_attachment($submissionRequest->id, 'Additional Attachment');
                        @endphp
                        <div class="{{ $errors->has('additional_attachment_name') ? ' has-error' : '' }}" >
                                <input 
                                    type='text' 
                                    class="form-control" 
                                    name="additional_attachment_name"
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
                                    <input type='file' class="form-control" name="additional_attachment" id="additional_attachment" />
                                </div>
                            @endif
                        </div>
                        <em><small class="" style="color: red;"> Max file Size 100M (Optional) </small></em>
                    </td>
                </tr>


                <tr>
                    <td></td>
                    <th>
                        <input type='hidden' class="form-control" name="intervention_line" value="{{$intervention->id}}" />
                        <input type='hidden' class="form-control" name="intervention_line_name" value="{{$intervention->name}}" />
                        <input type='hidden' class="form-control" name="submission_request_id" value="{{$submissionRequest->id}}" />
                        <input type='hidden' class="form-control" name="organization_id" value="{{ auth()->user()->organization_id }}" />
                        <input type='hidden' class="form-control" name="intervention_request_tranche" value="{{optional($submissionRequest)->tranche}}" />
                        <input type='hidden' class="form-control" name="checklist_input_fields" value="{{$checklist_input_fields}}" />
                        @if($submissionRequest->status == 'not-submitted')
                            <button type="submit" class="btn btn-sm btn-primary"> <span class="glyphicon glyphicon-ok"></span> &nbsp; Save Attachments </button>
                            <a href="{{ route('tf-bi-portal.submissionRequests.show',$submissionRequest->id) }}">
                                <button type="button" class="btn btn-sm btn-warning"> <span class="glyphicon glyphicon-remove"></span> Cancel </button>
                            </a>
                        @endif
                    </th>
                </tr>
            </form>
        </tbody>
    </table>
</div>