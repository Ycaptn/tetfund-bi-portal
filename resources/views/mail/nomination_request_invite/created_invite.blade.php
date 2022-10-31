@component('mail::message')
<strong>{{ strtoupper($request_data['nomination_type']) }}</strong> TET-Fund Nomination Invitation.

@component('mail::panel')
Hello <strong>{{ ucwords($request_data['bi_staff_fname']) }} {{ ucwords($request_data['bi_staff_lname']) }}, </strong>
Please be notified that your Desk-Officer have invited you to complete and submit your details for TETFund {{ strtoupper($request_data['nomination_type']) }} Nomination Request. <br>
Login to your account, complete and submit the form<br>
@endcomponent

Thanks,<br>
TETfund Administrative Desk
@endcomponent