<div class="col-sm-12">
    <table class="table table-striped  well well-sm">
        <thead class="thead-dark">
             <tr>
                <th scope="col">S/N </th>
                <th scope="col">Nominee Name</th>
                <th scope="col">Date Completed </th>
             </tr>
        </thead>
        <tbody>
            {{-- route('beneficiary-checklist-submit',$submissionRequest->id) --}}
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="{{ route('tf-bi-portal.processSubmissionRequestAttachement', ['id'=>$submissionRequest->id] ) }}" >

                {{ csrf_field() }}

                @php
                    $x = 0;
                    $checklist_input_fields = "";
                @endphp
                @if (isset($aSTDNominations) && $aSTDNominations != null)
                    @foreach ($aSTDNominations as $aSTDNomination)
                        <tr>
                            <th scope="row"> {{ $x+=1 }}</th>
                            <td>
                                <strong>
                                    {{ ucfirst($aSTDNomination->first_name) }} 
                                    {{ ucfirst($aSTDNomination->middle_name ?? '') }} 
                                    {{ ucfirst($aSTDNomination->last_name) }}
                                     || 
                                    {{ strtoupper($aSTDNomination->type_of_nomination ) }}
                                </strong>
                                <br>

                                <em>
                                    <small>
                                        {{ strtolower($aSTDNomination->email) }}
                                         || 
                                        {{ strtolower($aSTDNomination->telephone) }}
                                    </small>
                                </em>
                            </td>

                            <td width="50%">
                                {{ \Carbon\Carbon::parse($submissionRequest->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($submissionRequest->created_at)->diffForHumans() !!}
                            </td>
                        </tr>
                    @endforeach
                @endif

            </form>
        </tbody>
    </table>
</div>