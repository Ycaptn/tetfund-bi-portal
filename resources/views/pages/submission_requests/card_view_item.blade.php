
<div class="col-12 col-md-12 col-sm-12">
    <div class="card">
        @php
            $detail_page_url = route('tf-bi-portal.submissionRequests.show', $data_item->id);
        @endphp
        <div class="row g-0">
            <div class="col-xs-12 col-md-1 align-middle text-center p-2">
                <a href='{{$detail_page_url}}'>
                    <div class="ms-2 fm-icon-box radius-10 bg-primary text-white text-center">
                        <i class="bx bx-layer"></i>
                    </div>
                </a>

                @if(isset($data_item) && $data_item->status == 'not-submitted')
                    <div class="ms-auto"> 
                        @if($data_item->is_aip_request==true || ($data_item->is_first_tranche_request==true && $data_item->is_start_up_first_tranche_intervention($data_collection[$data_item->tf_iterum_intervention_line_key_id]??'')))

                            <a data-toggle="tooltip" 
                                title="Edit"
                                data-val='{{$data_item->id}}'
                                href="{{route('tf-bi-portal.submissionRequests.edit', $data_item->id)}}" 
                                class="ms-2 btn btn-sm btn-primary p-0 btn-edit-mdl-submissionRequest-modal m-1" href="#" style="font-size:80%;">
                                <i class="bx bxs-edit m-1"></i> 
                            </a>
                        @endif
                        <a data-toggle="tooltip" 
                            title="Delete" 
                            data-val='{{$data_item->id}}' 
                            class="btn btn-sm btn-danger p-0 btn-delete-mdl-submissionRequest-modal m-1" href="#" style="font-size:80%;">
                            <i class="bx bxs-trash-alt m-1"></i>
                        </a>
                    </div>
                @endif

            </div>
            <div class="col-xs-12 col-md-10">
                <div class="card-body">
                    <a href='{{$detail_page_url}}'>
                        <h3 class="h6 card-title mb-0">
                            {{ $data_item->title }}
                            @if(empty($data_item->status)==false)  
                                @if($data_item->status=='not-submitted')
                                    <span class="text-danger">
                                        ({!! strtoupper($data_item->status) !!})
                                    </span>
                                @elseif($data_item->status=='submitted' || $data_item->status=='approved')
                                    <span class="text-success">
                                        ({!! strtoupper($data_item->status) !!})
                                    </span>
                                @else
                                    <span class="">
                                        ({!! strtoupper($data_item->status) !!})
                                    </span>
                                @endif
                            @endif
                        </h3>
                    </a>
                    <p class="card-text mb-0 text-danger">
                        @php
                            $years_requested = [];
                            for($yr=1;$yr<5;$yr++){
                                $property = "intervention_year{$yr}";
                                if (!empty($data_item->$property)){ $years_requested []= $data_item->$property; }
                            }                            
                        @endphp
                        
                        {{ $data_collection[$data_item->tf_iterum_intervention_line_key_id]??'' }} - {{ $data_item->type }} - {{ implode(", ", $years_requested) }}
                    </p>
                    @if (!empty($data_item->amount_requested))
                        <p class="card-text mb-0 small">
                            <b>Amount Requested:</b>
                            &#8358;{{ number_format($data_item->amount_requested, 2, '.', ',') }}
                        </p>
                    @endif
                    
                    <p class="card-text text-muted small">
                        Created: {{ \Carbon\Carbon::parse($data_item->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($data_item->created_at)->diffForHumans() !!}
                    </p>
                </div>
            </div>
            <div class="col-xs-12 col-md-1 mt-2">

            </div>
        </div>
    </div>
</div>