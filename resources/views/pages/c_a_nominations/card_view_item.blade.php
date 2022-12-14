
<div class="col-12 col-md-12 col-sm-12">
    <div class="card">
        @php
            $detail_page_url = route('tetfund-astd.c_a_nominations.show', $data_item->id);
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
                    <div class="ms-auto"> 
                        <a data-toggle="tooltip" 
                            title="Edit" 
                            data-val='{{$data_item->id}}' 
                            class="btn-edit-mdl-cANomination-modal me-1" href="#">
                            <i class="bx bxs-edit"></i>
                        </a>
                        <a data-toggle="tooltip" 
                            title="Delete" 
                            data-val='{{$data_item->id}}' 
                            class="btn-delete-mdl-cANomination-modal me-1" href="#" style="color: red;">
                            <i class="bx bxs-trash-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-11">
                <div class="card-body">
                    <a href='{{$detail_page_url}}'>
                        <h3 class="h6 card-title mb-0">
                            {{ strtoupper($data_item->first_name) }} {{ strtoupper($data_item->middle_name) }} {{ strtoupper($data_item->last_name) }} &nbsp; <small>{{ $data_item->beneficiary->short_name ? ' ( BENEFICIARY: '. $data_item->beneficiary->full_name .' )' : ''}}
                        </h3>
                    </a>
                    
                    <p class="card-text text-muted small">
                        Created: {{ \Carbon\Carbon::parse($data_item->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($data_item->created_at)->diffForHumans() !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>