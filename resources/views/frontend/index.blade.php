@extends('layouts.frontend')

@section('content')

    <div class="flex justify-center">      
        <div class=" bg-white shadow-md border border-blue-300 rounded-md w-full md:w-4/5 z-10 px-8 pt-6 pb-8 mb-4" >

            {{-- <h3 class="mb-0 pb-0 text-3xl font-semibold leading-tight md:text-5xl text-center" style="color:green;">
                TERAS
            </h3> --}}

            <div class="flex justify-center items-center">
                <img src="{{asset("imgs/teras-logo.png")}}" class="" style="max-width:50%;" alt="Logo" />
            </div>

            <p class="text-center px-5 mb-5 text-sm text-gray-900">
                <b>Tertiary Education, Research, Applications, and Services</b>
            </p>

            <p class="px-5 mb-5 text-md text-gray-900">
                {{-- TERAS is TETFund's unified platform for delivery of services to beneficiary institutions, staff, and students. --}}
                Welcome to <b>TERAS</b>, a unified application and service platform for tertiary education institutions, staff, students, researchers, and the entire education ecosystem.
                {{-- Welcome to the upgraded TETFund Beneficiary Submission Portal for all our Beneficiary Institutions. 
                Please login to process submissions for <b>Physical Infrastructure, Library, ASTD, Academic Manuscripts, ICT Support, and Special Interventions</b>. --}}
            </p>
            
            <ul class="flex flex-wrap justify-center mt-5">
                <li><a class="mx-3 main-btn gradient-green-btn" href="http://www.tetfund.gov.ng" target="_blank">Login with BIMS</a></li>
                <li><a class="mx-3 main-btn gradient-green-btn" href="{{ route('login') }}">Login directly</a></li>
            </ul>

            <p class="px-5 mb-5 pt-2 text-sm" style="color:green;">
                Beneficiary Institutions can login to access Interventions for Library, Physical Infrastructure, ICT, Academic Staff Training & Development, Conference Attendance, Academic Manuscripts into Book, Enterprenurship, Teaching Practice, etc. 
            </p>

            <p class="px-5 text-md text-center" style="color:green;">
                <b>TERAS Service Platforms</b>
            </p>
            <hr class="mb-2" />
            <ul class="flex flex-wrap justify-center py-2">
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">BIMS</a></li>
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">EagleScan</a></li>
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">EBSCO eJournals</a></li>
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">Digital Skills - ICDL</a></li>
            </ul>
            <ul class="flex flex-wrap justify-center py-2">
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">Thesis Repository</a></li>
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">CLAP - Mobile Data</a></li>
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">Online Blackboard</a></li>
                <li><a class="mb-2 ml-1 bg-blue-600 hover text-sm text-white font-bold py-2 px-2 rounded-md" href="#">Communication Skills</a></li>
            </ul>
            <hr class="mt-2"/>
            <p class="px-5 mt-2 pt-2 text-sm text-center" style="color:red;">
            If you experience any difficulty accessing your TERAS, 
            please kindly contact <b>TETFund ICT support department on 0803-777-6194 or 0814-014-8722 or 0805-198-4832</b> or by email at <b>portal.support@tetfund.gov.ng</b> and <b>admin@teras.ng</b>.
            </p>

        </div>
    </div>

@stop