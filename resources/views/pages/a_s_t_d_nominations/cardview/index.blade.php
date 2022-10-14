<div class="row mb-15">
    <div class="col-xs-12 col-md-7 mb-1">
        @if (isset($action_buttons_list) && $action_buttons_list != null && count($action_buttons_list) > 0)
            @foreach ($action_buttons_list as $key => $account_button)
                @php
                    $button_icon = $account_button[0];
                    $button_href = $account_button[1];
                    $button_class = $account_button[2];
                    $button_data_map = $account_button[3];
                    $button_data_str = '';
                    if ($button_data_map != null && empty($button_data_map) == false && count($button_data_map) > 0) {
                        foreach ($button_data_map as $dkey => $dvalue) {
                            $button_data_str += " {$dkey}='{$dvalue}' ";
                        }
                    }
                @endphp
                <a href="{{ $button_href }}"
                    class="{{ $control_id }}-btn btn btn-sm btn-primary btn-outline faded me-1 {{ $button_class }}"
                    {{ $button_data_str }}>
                    @if ($button_icon != null && empty($button_icon) == false)
                        <i class="{{ $button_icon }}"></i>
                    @endif
                    {{ $key }}
                </a>
            @endforeach
        @endif
        @if ($data_set_group_list != null && count($data_set_group_list) > 0)
            @foreach ($data_set_group_list as $key => $group)
                <button data-val="{{ $key }}"
                    class="{{ $control_id }}-grp btn btn-sm btn-primary btn-outline faded me-1">{{ $key }}</button>
            @endforeach
        @endif
    </div>
    <div class="col-xs-12 col-md-5 mb-1">
        @if ($data_set_enable_search == true)
            <div class="input-group mb-3">
                <input type="text" id="{{ $control_id }}-txt-search" name="{{ $control_id }}-txt-search"
                    class="form-control form-control-sm" placeholder="{{ $search_placeholder_text }}">
                <span class="input-group-btn">
                    <button id="{{ $control_id }}-btn-search" name="{{ $control_id }}-btn-search" type="button"
                        class="h-100 btn btn-xs btn-primary btn-outline faded"><i class="fa fa-search d-inline"></i></button>
                </span>
            </div>
        @endif
    </div>
</div>
<div class="offline-flag text-center ma-20 pa-20"><span class="offline">You are currently offline</span></div>
<div id="{{$div_id_name}}" class="row"></div>

@if ($data_set_enable_pagination == true)
    <div class="row">
        <div class="col-xs-12">
            <ul id="{{ $control_id }}-pagination" class="pagination"></ul>
        </div>
    </div>
@endif
