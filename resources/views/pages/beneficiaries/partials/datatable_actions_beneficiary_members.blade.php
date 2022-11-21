{{--    
    <a href="#" data-val='{{$id}}' class='btn-show-mdl-beneficiary-modal'>
        {!! Form::button('<i class="fa fa-eye"></i>', ['type'=>'button']) !!}
    </a>
    
    <a href="#" data-val='{{$id}}' class='btn-edit-mdl-beneficiary-modal'>
        {!! Form::button('<i class="fa fa-edit"></i>', ['type'=>'button']) !!}
    </a>
    
    <a href="#" data-val='{{$id}}' class='btn-delete-mdl-beneficiary-modal'>
        {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'button']) !!}
    </a>
--}}

<center>
    <div class='btn-group' role="group">
        <a data-toggle="tooltip" 
            title="Preview user details" 
            data-val='{{$id}}' 
            class="btn-preview-beneficiary-member" 
            href="#">
            <i class="fa fa-eye text-primary" style="opacity:80%"></i>
        </a> &nbsp; &nbsp;

        @if($email != auth()->user()->email || auth()->user()->hasRole('admin'))
            <a data-toggle="tooltip" 
                title="Edit user account" 
                data-val='{{$id}}' 
                class="btn-edit-beneficiary-member" 
                href="#">
                <i class="fa fa-pencil-square-o text-primary" style="opacity:80%"></i>
            </a> &nbsp; &nbsp;

            @php
                $title = "Enable";
                $flag = '1';
                $font_awesome = "fa fa-check text-info";
                if($is_disabled == false) {
                    $title = "Disable";
                    $flag = '0';
                    $font_awesome = "fa fa-ban text-danger";
                }
            @endphp
            <a data-toggle="tooltip" 
                title="{{$title}} user account" 
                data-val='{{$id}}{{$flag}}' 
                class="btn-enable-disable-beneficiary-member" 
                href="#">
                <i class="{{$font_awesome}}" style="opacity:80%"></i>
            </a> &nbsp; &nbsp;

            {{-- <a data-toggle="tooltip" 
                title="Delete user account" 
                data-val='{{$id}}' 
                class="btn-delete-beneficiary-member" 
                href="#">
                <i class="fa fa-trash text-danger" style="opacity:80%"></i>
            </a> --}}
        @endif

        <a data-toggle="tooltip" 
            title="Reset user password" 
            data-val='{{$id}}' 
            class="btn-reset-password-beneficiary-member" 
            href="#">
            <i class="fa fa-key text-primary" style="opacity:80%"></i>
        </a>
    </div>
</center>