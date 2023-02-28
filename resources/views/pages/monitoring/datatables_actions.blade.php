{{--    
    <a href="#" data-val='{{$id}}' class='btn-show-mdl-submissionRequest-modal'>
        {!! Form::button('<i class="fa fa-eye"></i>', ['type'=>'button']) !!}
    </a>
    
    <a href="#" data-val='{{$id}}' class='btn-edit-mdl-submissionRequest-modal'>
        {!! Form::button('<i class="fa fa-edit"></i>', ['type'=>'button']) !!}
    </a>
    
    <a href="#" data-val='{{$id}}' class='btn-delete-mdl-submissionRequest-modal'>
        {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'button']) !!}
    </a>
--}}
<div class='btn-group p-2'>
    <div class="row">
        @if ($status=='not-submitted')
            {{-- <a data-toggle="tooltip" 
                title="View" 
                data-val='{{$id}}' 
                class="btn-show-mdl-submissionRequest-modal" href="#">
                <i class="fa fa-eye text-primary" style="opacity:80%"></i>
            </a>

            <a data-toggle="tooltip" 
                title="Edit" 
                data-val='{{$id}}' 
                class="btn-edit-mdl-submissionRequest-modal" href="#">
                <i class="fa fa-pencil-square-o text-primary" style="opacity:80%"></i>
            </a>

            <a data-toggle="tooltip" 
                title="Delete" 
                data-val='{{$id}}' 
                class="btn-delete-mdl-submissionRequest-modal" href="#">
                <i class="fa fa-trash-o text-danger" style="opacity:80%"></i>
            </a> --}}

            <a href="#" class="col-sm-12 btn btn-sm btn-success btn-submit-m-r" data-val='{{$id}}' title="Submit Monitoring Request">
                <small>Submit</small>
            </a>                                            
            <a href="#" class="col-sm-12 col-md-6 btn btn-sm btn-default text-info btn-edit-m-r" data-val='{{$id}}' title="Edit Monitoring Request">
                <small>
                    <span class="fa fa-edit"></span>                        
                </small>
            </a>
            <a href="#" class="col-sm-12 col-md-6 btn btn-sm btn-default text-danger btn-delete-m-r" data-val='{{$id}}' title="Delete Monitoring Request">
                <small>
                    <span class="fa fa-trash"></span>
                </small>
            </a>
        @else
            <b class="text-center">
                - - -
            </b>
        @endif
    </div>
</div>