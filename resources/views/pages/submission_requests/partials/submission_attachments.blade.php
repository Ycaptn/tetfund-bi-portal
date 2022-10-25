<div class="col-sm-12">
    <table class="table table-striped  well well-sm">
        <thead class="thead-dark">
             <tr>
                <th scope="col">S/N </th>
                <th scope="col">Attachment Description</th>
                <th scope="col">Attachment </th>
             </tr>
        </thead>
        <tbody>
            {{-- route('beneficiary-checklist-submit',$submissionRequest->id) --}}
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="{{ route('tf-bi-portal.processSubmissionRequestAttachement', ['id'=>$submissionRequest->id] ) }}" >

                {{ csrf_field() }}

                @php
                    $x = 0;
                @endphp
                @if (isset($checklist_items) )
                    @foreach ($checklist_items as $item)
                        @php
                            $checklist = "checklist_".$item->id;
                        @endphp

                        <tr>
                            <th scope="row"> {{ $x+=1 }}</th>
                            <td>{{ $item->item_label }}</td>
                            <td width="50%">
                                <div class="input-group">
                                    <div class="{{ $errors->has($checklist) ? ' has-error' : '' }} col-sm-12" >
                                        <input type='file' class="form-control" name="{{$checklist}}" />
                                    </div>
                                </div>
                                <em><small class="" style="color: red;"> Max file Size 100M </small></em>
                            </td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <th>{{ $x+=1 }}</th>
                    <td>
                        <div class="{{ $errors->has('additional_attachment_name') ? ' has-error' : '' }}" >
                                <input type='text' class="form-control" name="additional_attachment_name"
                                placeholder="Enter name for Additional Attachment" />
                        </div>
                    </td>
                    <td width="50%">
                        <div class="input-group">
                            <div class="{{ $errors->has('additional_attachment') ? ' has-error' : '' }} col-sm-12" >
                                <input type='file' class="form-control" name="additional_attachment" id="additional_attachment" />
                            </div>
                        </div>
                        <em><small class="" style="color: red;"> Max file Size 100M </small></em>
                    </td>
                </tr>


                <tr>
                    <td></td>
                    <th>
                        <input type='hidden' class="form-control" name="intervention_line" value="{{$intervention->id}}"  />
                        <input type='hidden' class="form-control" name="submission_request_id" value="{{$submissionRequest->id}}"  />

                        <input type='hidden' class="form-control" name="intervention_request_tranche" value="{{optional($submissionRequest)->tranche}}"  />
                        <button type="submit" class="btn btn-sm btn-primary"> <span class="glyphicon glyphicon-ok"></span> &nbsp; Submit Attachment </button>
                        <a href="{{ route('tf-bi-portal.submissionRequests.show',$submissionRequest->id) }}">
                            <button type="button" class="btn btn-sm btn-warning"> <span class="glyphicon glyphicon-remove"></span> Cancel </button>
                        </a>

                    </th>
                </tr>
            </form>
        </tbody>
    </table>
</div>