@extends('layouts.frontend')

@section('content')

    <div class="flex justify-center">      
        <div class=" bg-white shadow-md border border-blue-300 rounded-md w-full md:w-3/4 z-10 px-8 pt-6 pb-8 mb-4" >

            <h3 class="mb-5 text-3xl font-semibold leading-tight md:text-5xl text-center" style="color:green;">
                Beneficiary Submission Portal
            </h3>
            <p class="px-5 mb-5 text-sm text-gray-900">
                Welcome to the upgraded TETFund Beneficiary Submission Portal for all our Beneficiary Institutions. 
                Please login to process submissions for <b>Physical Infrastructure, Library, ASTD, Academic Manuscripts, ICT Support, and Special Interventions</b>.
            </p>
            
            <ul class="flex flex-wrap justify-center mt-5">
                <li><a class="mx-3 main-btn gradient-green-btn" href="http://www.tetfund.gov.ng" target="_blank">TETFund</a></li>
                <li><a class="mx-3 main-btn gradient-green-btn" href="{{ route('login') }}">Login</a></li>
            </ul>

            <p class="px-5 mb-5 pt-6 text-sm" style="color:green;">
                For <b>National Research Fund (NRF)</b> submissions, please use the <a href="https://nrf.tetfund.gov.ng" style="color:blue;" target="_blank">NRF Portal</a> to process your submissions.
            </p>

            <p class="px-5 mb-5 pt-2 text-sm" style="color:red;">
            If you experience any difficulty accessing your TETFund Beneficiary Account, 
            please kindly contact <b>TETFund ICT support department on 0803-777-6194 or 0814-014-8722 or 0805-198-4832</b> or by email at portal.support@tetfund.gov.ng
            </p>

        </div>
    </div>

@stop