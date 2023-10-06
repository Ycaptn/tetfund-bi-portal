
<div class="col-12 col-md-12 col-sm-12">
    <div class="card">
        @php
            $detail_page_url = route('tf-bi-portal.nomination_requests.show', $data_item->id);
        @endphp
        <div class="row g-0">
            <div class="col-xs-12 col-md-1 align-middle text-center p-2">
                <a href='{{$detail_page_url}}'>
                    @if ( $data_item->logo_image != null )
                        <img width="42" height="42" class="ms-2 img-fluid text-center rounded-circle p-1 border" src="{{ route('fc.get-dept-picture', $data_item->id) }}" />
                    @else
                        <div class="ms-2 fm-icon-box radius-10 bg-primary text-white text-center">
                            <i class="bx bx-hive"></i>
                        </div>
                    @endif
                </a>
            </div>
            <div class="col-xs-12 col-md-9">
                <div class="card-body">
                    <a href='{{$detail_page_url}}'>
                        <h3 class="h6 card-title mb-0">
                            {{ strtoupper($data_item->user->first_name) }}
                            {{ strtoupper($data_item->user->last_name) }} 
                                @if($data_item->head_of_institution_checked_status == 'approved')
                                    || <span class="text-success"> REQUEST CONSIDERED FOR SUBMISSION</span>
                                @elseif($data_item->head_of_institution_checked_status == 'declined')
                                    || <span class="text-danger"> REQUEST {{ strtoupper($data_item->head_of_institution_checked_status) }} </span>
                                @elseif($data_item->committee_head_checked_status == 'declined')
                                    || <span class="text-danger"> REQUEST {{ strtoupper($data_item->committee_head_checked_status) }} </span>
                                @else
                                    || <span class="text-danger"> REQUEST UNDER REVIEW </span>
                                @endif
                        </h3>
                    </a>
                    <p class="card-text mb-0 small">
                        <b> {{ strtoupper($data_item->type) }} Nomination Request || 
                            <small>
                                {{ $data_item->user->email }}
                            </small>
                        </b>
                    </p>
                    @if(empty($data_item->status == false && $data_item->status == 'approved'))
                        <p class="card-text mb-0 small">
                            <b>
                                {!! (isset($data_item->details_submitted) && $data_item->details_submitted == true) ? 
                                "<span class='text-success'> Nomination Details saved and submitted</span>" : 
                                "<span class='text-danger'> Nomination Details has not been submitted</span>" !!}
                            </b>
                        </p>
                    @endif
                    
                    <p class="card-text text-muted small">
                        Created: {{ \Carbon\Carbon::parse($data_item->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($data_item->created_at)->diffForHumans() !!}
                    </p>
                </div>
            </div>

            <div class="col-sm-12 col-md-2 align-items-center">
                @if(isset($data_item) && ($data_item->status == 'not-submitted' || empty($data_item->bi_submission_request_id)))
                    <div class="ms-auto"> 
                        {{-- <a data-toggle="tooltip" 
                            title="Edit"
                            data-val='{{$data_item->id}}'
                            href="{{route('tf-bi-portal.nomination_requests.edit', $data_item->id)}}" 
                            class="btn-edit-mdl-nomination_request-modal me-1" href="#">
                            <i class="bx bxs-edit"></i>
                        </a> --}}
                        <small>
                            <a data-toggle="tooltip" 
                                title="Delete" 
                                data-val='{{$data_item->id}}' 
                                class="btn-delete-mdl-nomination_request-modal m-2 btn btn-sm btn-danger float-end" href="#">
                                <i class="bx bxs-trash-alt"></i> Delete
                            </a>
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>