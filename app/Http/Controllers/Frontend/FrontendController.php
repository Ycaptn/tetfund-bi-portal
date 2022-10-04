<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\Workflow\Models\WorkItem;
use Hasob\Workflow\Models\WorkItemAssignment;

use App\Models\PortalRequest;
use App\Models\PaymentAttempt;
use App\Models\VerificationRequest;
use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;

use App\Events\VerificationCreatedEvent;
use App\Events\PaymentConfirmationEvent;
use App\Http\Requests\CreateVerificationRequest;

use App\Http\Controllers\Controller;

class FrontendController extends \Hasob\FoundationCore\Controllers\BaseController
{    


    public function displayHome(Organization $org, Request $request){

        $current_user = Auth()->user();
        // return redirect('/login');

        return view('frontend.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user)
                    ->with('states_list', $this->statesList());
    }

    public function processRegistration(Organization $org, Request $request){
        
    }


}
