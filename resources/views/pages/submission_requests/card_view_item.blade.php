
<div class="col-12 col-md-12 col-sm-12">
    <div class="card">
        @php
            $detail_page_url = route('tf-bi-portal.submissionRequests.show', $data_item->id);
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
                <div class="d-flex align-items-center">
                    {{-- <div><h4 class="card-title"><a href='{{$detail_page_url}}'>{{$data_item->id}}</a></h4></div> --}}
                    @if(isset($data_item) && $data_item->status == 'not-submitted')
                        <div class="ms-auto"> 
                            <a data-toggle="tooltip" 
                                title="Edit"
                                data-val='{{$data_item->id}}'
                                href="{{route('tf-bi-portal.submissionRequests.edit', $data_item->id)}}" 
                                class="btn-edit-mdl-submissionRequest-modal me-1" href="#">
                                <i class="bx bxs-edit"></i>
                            </a>
                            <a data-toggle="tooltip" 
                                title="Delete" 
                                data-val='{{$data_item->id}}' 
                                class="btn-delete-mdl-submissionRequest-modal me-1" href="#">
                                <i class="bx bxs-trash-alt" style="color: red;"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-md-11">
                <div class="card-body">
                    <a href='{{$detail_page_url}}'>
                        <h3 class="h6 card-title mb-0">
                            {{ $data_item->title }} @if(empty($data_item->status)==false) || {!! strtoupper($data_item->status) !!}@endif
                        </h3>
                    </a>
                    @if (!empty($data_item->amount_requested))
                        <p class="card-text mb-0 small">
                            <b>Amount Requested: &nbsp; &#8358;</b>{{ number_format($data_item->amount_requested, 2, '.', ',') }}
                        </p>
                    @endif
                    
                    <p class="card-text text-muted small">
                        Created: {{ \Carbon\Carbon::parse($data_item->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($data_item->created_at)->diffForHumans() !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>