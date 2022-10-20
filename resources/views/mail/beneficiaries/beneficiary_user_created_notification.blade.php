@component('mail::message')
Your TET-Fund Beneficiary User Account has been created.

@component('mail::panel')
Please be notified that your TET-Fund Beneficiary User Account has been successfully created <br>
Find below the login Credentials to your Account.<br>
Username: <span style="color:blue"> {{ $input['email'] }}  </span> <br>
Password: <span style="color:blue"> {{ $input['password'] }} </span>
@endcomponent

Thanks,<br>
TETfund Administrative Desk
@endcomponent