
@if (isset($children['children']) && $children['children']!= null && count($children['children'])>0)

<li id="{{$children['id']}}">
    <a href="{{isset($children['path']) && !empty($children['path']) ? $children['path'] : 'javascript:;' }}" class="has-arrow">
        <div class="parent-icon"><i class="{{empty($children['icon'])?'bx bx-category':$children['icon']}}"></i></div>
        <div class="menu-title">{{ $children['label'] }}</div>
    </a>
    @if (isset($children['children']) && $children['children']!= null && count($children['children'])>0)
    <ul>
        @each('layouts.default-app-template.menu-group', $children['children'], 'children')
    </ul>
    @endif
</li>

@else

@if (isset($children['is-parent']) && $children['is-parent']!= null && $children['is-parent']==true)
    <li id="{{$children['id']}}">
        <a href="{{isset($children['path']) && !empty($children['path']) ? $children['path'] : 'javascript:;' }}">
            <div class="parent-icon"><i class="{{empty($children['icon'])?'bx bx-category':$children['icon']}}"></i></div>
            <div class="menu-title">{{ $children['label'] }}</div>
        </a>
    </li>
@else
    <li id="{{$children['id']}}"> 
        <a id="lnk_{{$children['id']}}" href="{{$children['path']}}"><i class="{{empty($children['icon'])?'bx bx-right-arrow-alt':$children['icon']}}"></i>{{$children['label']}}</a>
    </li>
@endif

@endif