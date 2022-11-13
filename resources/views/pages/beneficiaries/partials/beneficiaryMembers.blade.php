<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    S/N
                </th>
                <th>
                    Full Name
                </th>
                <th>
                    Email
                </th>
                <th>
                    Phone
                </th>
                <th>
                    Roles
                </th>
            </tr>
        </thead>
        <tbody>
            @if(isset($beneficiary_members) && count($beneficiary_members) > 0)
                @php
                    $counter = 0;
                @endphp
                @foreach($beneficiary_members as $beneficiary_member)
                    <tr>
                        <td>
                            <b>{{ $counter += 1 }}). </b>  
                        </td>
                        <td>
                            {{ $beneficiary_member->user->fullname }}
                        </td>
                        <td>
                            {{ $beneficiary_member->user->email }}
                        </td>
                        <td>
                            {{ $beneficiary_member->user->telephone }}
                        </td>
                        <td>
                            @php
                                $user_roles = $beneficiary_member->user->roles()->pluck('name')->toArray();
                            @endphp
                            @if(count($user_roles) > 0)
                                @foreach($user_roles as $user_role)
                                    <b>||</b>
                                    {{ucwords($user_role)}}
                                    <b>||</b>
                                    <br>
                                @endforeach
                            @endif
                        </td>
                    </tr> 
                @endforeach
            @endif
        </tbody>
    </table>
</div>