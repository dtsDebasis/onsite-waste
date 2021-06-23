<div class="table-responsive">
    <table class="table table-centered table-nowrap mb-0">
        <thead class="thead-light">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            @if(count($contacts))
                @foreach($contacts as $contact )
                    @if($contact->user)
                        <tr class="">
                            <td>{{ $contact->user->first_name ?? '' }}</td>
                            <td>{{ $contact->user->last_name ?? '' }}</td>
                            <td>{{ $contact->user->designation ?? '' }}</td>
                            <td>{{ $contact->user->email ?? '' }}</td>
                            <td>{{ $contact->user->phone ?? '' }}</td>
                        </tr>
                    @endif
                @endforeach
            @else
            <tr class="">
                <td colspan="5">No record found</td>
            @endif
        </tbody>
    </table>
</div>