<div class="card radius-5 border-top border-0 border-3 border-success">
    <div class="card-body" style="">
        <div class="float-start">
            <h6 class="card-title txt-light-dark-blue">
                <a href="{{ route('wf.requests') }}">
                    <i class="zmdi zmdi-assignment mr-5"></i>
                    Tasks
                </a>
            </h6>
        </div>
        <div class="clearfix"></div>
        @if (isset($assignments) && $assignments!=null && count($assignments)>0)
            <table class="table table-hover mb-0 small">
                <tbody>
                    @foreach ($assignments as $idx => $item)
                        <tr>
                            <td><a href="{{ route('wf.request-display',$item->id) }}">{!! $item->work_item->title !!}</td>
                            <td>{!! $item->getAssignmentDateDurationString() !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h6 class="text-center">No tasks assigned to you</h6>
        @endif
    </div>
</div>