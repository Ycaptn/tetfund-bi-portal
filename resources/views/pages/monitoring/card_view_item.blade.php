
<div class="col-12 col-md-12 col-sm-12">
    <div class="card">
        @php
            $detail_page_url = route('tf-bi-portal.submissionRequests.showMonitoring', $data_item->id);
        @endphp
        <div class="row g-0">
            <div class="col-xs-12 col-md-1 align-middle text-center p-2">
                <a href='{{$detail_page_url}}'>
                    <div class="ms-2 fm-icon-box radius-10 bg-primary text-white text-center">
                        <i class="bx bx-camera"></i>
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
                        @endphp
                        
                        {{ $data_collection[$data_item->tf_iterum_intervention_line_key_id]??'' }} - {{ $data_item->type }} - {{ implode(", ", $years_requested) }}
                    </p>
                    @if (!empty($data_item->amount_requested))
                        <p class="card-text mb-0 small">
                            <b>Proposed Monitoring Date:</b>
                            {{ date('jS \of F, Y', strtotime($data_item->proposed_request_date)) }}
                        </p>
                    @endif
                    
                    <p class="card-text text-muted small">
                        Created: {{ \Carbon\Carbon::parse($data_item->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($data_item->created_at)->diffForHumans() !!}
                    </p>
                </div>
            </div>
            <div class="col-xs-12 col-md-1 mt-2">
                @if(isset($data_item) && $data_item->status == 'not-submitted')
                    <div class="ms-auto row">                                           
                        <a href="#" class="col-sm-12 col-md-6 btn btn-sm btn-default text-info btn-edit-m-r" data-val='{{$data_item->id}}' title="Edit Monitoring Request">
                            <small>
                                <span class="fa fa-edit"></span>                        
                            </small>
                        </a>
                        <a href="#" class="col-sm-12 col-md-6 btn btn-sm btn-default text-danger btn-delete-m-r" data-val='{{$data_item->id}}' title="Delete Monitoring Request">
                            <small>
                                <span class="fa fa-trash"></span>
                            </small>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>