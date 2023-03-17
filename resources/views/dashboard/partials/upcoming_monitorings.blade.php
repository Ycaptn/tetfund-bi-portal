    <table class="col-sm-12 table table-bordered">
        <thead>
            <tr style="width:100%;">
                <th style="width:5%;" class="text-center">
                    S/N
                </th>
                <th style="width:55%;" class="">
                    Project Title
                </th>
                <th style="width:15%;" class="text-center">
                    Status
                </th>
                <th style="width:25%;" class="text-center">
                    Proposed Date
                </th>                                    
            </tr>
        </thead>
        <tbody>
            @if(isset($upcoming_monitorings) && !empty($upcoming_monitorings))
                @foreach($upcoming_monitorings as $monitoring)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <a href="{{ route('tf-bi-portal.showMonitoring', $monitoring->id) }}" class="text-decoration-underline text-primary"> 
                                {{ $monitoring->title }}
                            </a><br>
                            <small>
                                <i>- {{ $monitoring->monitoring_type }} -</i>
                            </small>
                        </td>
                        <td class="text-center {{ $monitoring->status=='approved' ? 'text-success' :'text-primary' }}">
                            {{ ucfirst($monitoring->status) }}
                        </td>
                        <td class="text-center">
                            {{ date('jS M, Y', strtotime($monitoring->proposed_request_date)) }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center text-danger" colspan="4">
                        <i>No Upcoming Monitoring Request at the Moment</i>
                    </td>
                </tr>
            @endif  
        </tbody>
    </table>