
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
            </div>
            <div class="col-xs-12 col-md-10">
                <div class="card-body">
                    <a href='{{$detail_page_url}}'>
                        <h3 class="h6 card-title mb-0">
                            {{ $data_item->title }}
                            @if(empty($data_item->status)==false)  
                                @if($data_item->status == 'not-submitted')
                                    <span class="text-danger">
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
                            $intervention_line = "";
                            if (!empty($data_item->tf_iterum_portal_response_meta_data)){
                                $meta_data = json_decode($data_item->tf_iterum_portal_response_meta_data);
                                $intervention_line = $meta_data->name;
                            }
                        @endphp
                        {{ $intervention_line }} - {{ $data_item->type }} - {{ implode(", ", $years_requested) }}
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
                    @if(isset($data_item) && $data_item->status == 'not-submitted')
                        <div class="ms-auto"> 
                            @if($data_item->is_aip_request==true)
                                <a data-toggle="tooltip" 
                                    title="Edit"
                                    data-val='{{$data_item->id}}'
                                    href="{{route('tf-bi-portal.submissionRequests.edit', $data_item->id)}}" 
                                    class="btn-edit-mdl-submissionRequest-modal me-1" href="#">
                                    <i class="bx bxs-edit"></i>
                                </a>
                            @endif
                            <a data-toggle="tooltip" 
                                title="Delete" 
                                data-val='{{$data_item->id}}' 
                                class="btn-delete-mdl-submissionRequest-modal me-1" href="#">
                                <i class="bx bxs-trash-alt text-danger"></i>
                            </a>
                        </div>
                    @endif
                </div>
        </div>
    </div>
</div>