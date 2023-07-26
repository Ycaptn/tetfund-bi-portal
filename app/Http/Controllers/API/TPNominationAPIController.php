<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Beneficiary;
use App\Models\TPNomination;
use App\Models\NominationRequest;

use App\Events\TPNominationCreated;
use App\Events\TPNominationUpdated;
use App\Events\TPNominationDeleted;

use App\Http\Requests\API\CreateTPNominationAPIRequest;
use App\Http\Requests\API\UpdateTPNominationAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;
use App\Managers\TETFundServer;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class TPNominationController
 * @package App\Http\Controllers\API
 */

class TPNominationAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the TPNomination.
     * GET|HEAD /tPNominations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $org)
    {
        $query = TPNomination::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $tPNominations = $this->showAll($query->get());

        return $this->sendResponse($tPNominations->toArray(), 'T P Nominations retrieved successfully');
    }

    /**
     * Store a newly created TPNomination in storage.
     * POST /tPNominations
     *
     * @param CreateTPNominationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTPNominationAPIRequest $request, Organization $organization)
    {
        $bi_beneficiary = Beneficiary::find($request->get('beneficiary_institution_id'));
        $input = $this->set_tp_nominee_amounts($request->all(), $bi_beneficiary);
        if($input == false){
            return $this->sendError('Something Went wrong please try again. If the problem persists please contact the system administrator.');
        }
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::create($input);
        
        $nominationRequest = NominationRequest::find($request->nomination_request_id);
        $nominationRequest->details_submitted = 1;
        $nominationRequest->save();

       //handling attachments
        $attachement_and_final_response = self::handle_attachments($request, $tPNomination, $nominationRequest);
        if ($attachement_and_final_response) {
            return $attachement_and_final_response;
        }

        return $this->sendError('Error encountered while processing attachments');
    }

    //handling attachement 
    public function handle_attachments($request, $tPNomination, $nominationRequest) {
         /*handling passport_photo upload process*/
        if($request->hasFile('passport_photo')) {
            $label = $tPNomination->first_name . " " . $tPNomination->last_name . " TPNomination Passport Photo";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling invitation_letter upload process*/
        if($request->hasFile('invitation_letter')) {
            $label = $tPNomination->first_name . " " . $tPNomination->last_name . " TPNomination Invitation Letter";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->invitation_letter);
        }

        TPNominationCreated::dispatch($tPNomination);
        return $this->sendResponse($tPNomination->toArray(), 'T P Nomination saved successfully');
    }

    /**
     * Display the specified TPNomination.
     * GET|HEAD /tPNominations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization) {
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::find($id);

        if (empty($tPNomination)) {
            $nominationRequest = NominationRequest::find($id);
            if (empty($nominationRequest)) {
                return $this->sendError('T P Nomination not found');
            }

            $tPNomination = $nominationRequest->tp_submission;
        }

        /*class constructor to fetch institution*/
        $tetFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/institutions/".$tPNomination->tf_iterum_portal_institution_id;
        $payload = ['_method'=>'GET', 'id'=>$tPNomination->tf_iterum_portal_institution_id];
        $institution = $tetFundServer->get_row_records_from_server($url_path, $payload);

        $tPNomination->beneficiary = ($tPNomination->beneficiary_institution_id != null) ? $tPNomination->beneficiary : [];
        $tPNomination->institution = ($institution != null) ? $institution : null;
        $tPNomination->country = optional($institution)->country;
        $tPNomination->user = ($tPNomination->user_id != null) ? $tPNomination->user : [];

        return $this->sendResponse($tPNomination->toArray(), 'T P Nomination retrieved successfully');
    }

    /**
     * Update the specified TPNomination in storage.
     * PUT/PATCH /tPNominations/{id}
     *
     * @param int $id
     * @param UpdateTPNominationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTPNominationAPIRequest $request, Organization $organization)
    {
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::find($id);

        if (empty($tPNomination)) {
            return $this->sendError('T P Nomination not found');
        }

        $bi_beneficiary = Beneficiary::find($request->get('beneficiary_institution_id'));
        $input = $this->set_tp_nominee_amounts($request->all(), $bi_beneficiary);
        if($input == false){
            return $this->sendError('Something Went wrong please try again. If the problem persists please contact the system administrator.');
        }
        $tPNomination->fill($input);
        $tPNomination->save();
        $nominationRequest = $tPNomination->nomination_request;
        
        /*handling passport_photo update process*/
        if($request->hasFile('passport_photo')) {
            $label = $tPNomination->first_name . " " . $tPNomination->last_name . " TPNomination Passport Photo";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling invitation_letter update process*/
        if($request->hasFile('invitation_letter')) {
            $label = $tPNomination->first_name . " " . $tPNomination->last_name . " TPNomination Invitation Letter";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old invitation_letter
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old invitation_letter
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->invitation_letter);
        }

        TPNominationUpdated::dispatch($tPNomination);
        return $this->sendResponse($tPNomination->toArray(), 'TPNomination updated successfully');
    }

    /**
     * Remove the specified TPNomination from storage.
     * DELETE /tPNominations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::find($id);

        if (empty($tPNomination)) {
            return $this->sendError('T P Nomination not found');
        }

        $nominationRequest = $tPNomination->nomination_request;
        $nominationRequest->details_submitted = false;
        $nominationRequest->save();

        $attachments = $nominationRequest->get_all_attachments($nominationRequest->id);

        if (!empty($attachments) && count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $nominationRequest->delete_attachment($attachment->label);
            }
        }

        $tPNomination->delete();
        TPNominationDeleted::dispatch($tPNomination);
        return $this->sendSuccess('T P Nomination deleted successfully');
    }

    public function set_tp_nominee_amounts($input, $bi_beneficiary) {
        /* server class constructor to retrieve amout settings */
        $pay_load = [ '_method' => 'GET'];
        $tetFundServer = new TETFundServer();
        $tp_amount_settings = $tetFundServer->get_all_data_list_from_server('tetfund-astd-api/tp_cost_settings/'.$input['rank_gl_equivalent'], $pay_load);
        if(is_array($tp_amount_settings) && count($tp_amount_settings) == 0){
            return false;
        }
        if (!empty($tp_amount_settings)) {
            // no. of days
            $date_diff = strtotime($input['program_end_date']) - strtotime($input['program_start_date']);
            $no_days = round($date_diff / (60 * 60 * 24)) + 1;

            // setting amount colunm
            $input['dta_amount_requested'] = floatval($tp_amount_settings->dta_amount ?? 0);
            $input['dta_nights_amount_requested'] = $input['dta_amount_requested'] * $no_days;
            $input['local_runs_amount_requested'] = (floatval($tp_amount_settings->dta_local_runs_percentage??0) * $input['dta_nights_amount_requested']) / 100;
            $input['taxi_fare_amount_requested'] = floatval($tp_amount_settings->taxi_fare_amt ?? 0);
            $input['total_requested_amount'] = $input['dta_nights_amount_requested'] + $input['local_runs_amount_requested'] + $input['taxi_fare_amount_requested'];
        }

        return $input;
    }
}
