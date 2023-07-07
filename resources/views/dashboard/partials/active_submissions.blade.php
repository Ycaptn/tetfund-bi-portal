    <table class="col-sm-12 table table-bordered">
        <thead>
            <tr style="width:100%;">
                <th style="width:5%;" class="text-center">
                    S/N
                </th>
                <th style="width:55%;" class="">
                    Title
                </th>
                <th style="width:15%;" class="text-center">
                    Status
                </th>
                <th style="width:25%;" class="text-center">
                    Date Submitted
                </th>                                        
            </tr>
        </thead>
        <tbody>
            @if(isset($active_submissions) && !empty($active_submissions))
                @foreach($active_submissions as $submissions)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <a href="{{ route('tf-bi-portal.submissionRequests.show', $submissions->id) }}" class="text-decoration-underline text-primary"> 
                                {{ $submissions->title }}
                            </a>
                            @php
                                $server_data =json_decode($submissions->tf_iterum_portal_response_meta_data);
                                $file_no = $server_data->file_number??'';
                            @endphp
                            <br><small><i>{{ $submissions->type }} {{ $file_no ? ' - '. $file_no : '' }}</i></small>
                        </td>
                        <td class="text-center {{ $submissions->status=='not-submitted' ? 'text-danger' : ($submissions->status=='submitted' ? 'text-success' : 'text-primary') }}">
                            {{ ucfirst($submissions->status) }}
                        </td>
                        <td class="text-center">
                            {{ date('jS M, Y', strtotime($submissions->created_at)) }} <br>
                            <a href="{{ route('tf-bi-portal.submissionRequests.show', $submissions->id) }}" class="btn btn-sm btn-primary"> 
                                view
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center text-danger" colspan="4">
                        <i>No Approved Submissions at the Moment</i>
                    </td>
                </tr>
            @endif  
        </tbody>
    </table>