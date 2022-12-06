<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\TSASNomination;
use App\Models\NominationRequest;

use App\Events\TSASNominationCreated;
use App\Events\TSASNominationUpdated;
use App\Events\TSASNominationDeleted;

use App\Http\Requests\API\CreateTSASNominationAPIRequest;
use App\Http\Requests\API\UpdateTSASNominationAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;
use App\Managers\TETFundServer;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class TSASNominationController
 * @package App\Http\Controllers\API
 */

class TSASNominationAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the TSASNomination.
     * GET|HEAD /tSASNominations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $org)
    {
        $query = TSASNomination::query();

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

        return $this->sendResponse($tPNominations->toArray(), 'T S A S Nominations retrieved successfully');
    }

    /**
     * Store a newly created TSASNomination in storage.
     * POST /tSASNominations
     *
     * @param CreateTSASNominationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTSASNominationAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::create($input);
        
        $nominationRequest = NominationRequest::find($request->nomination_request_id);
        $nominationRequest->details_submitted = 1;
        $nominationRequest->save();

       //handling attachments
        $attachement_and_final_response = self::handle_attachments($request, $tSASNomination, $nominationRequest);
        if ($attachement_and_final_response) {
            return $attachement_and_final_response;
        }

        return $this->sendError('Error encountered while processing attachments');
    }

    //handling attachement 
    public function handle_attachments($request, $tSASNomination, $nominationRequest) {
         /*handling passport_photo upload process*/
        if($request->hasFile('passport_photo')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Passport Photo";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo, 's3');
        }

        /*handling admission_letter upload process*/
        if($request->hasFile('admission_letter')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Admission Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->admission_letter, 's3');
        } 

        /*handling health_report upload process*/
        if($request->hasFile('health_report')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Health Report";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->health_report, 's3');
        } 

        /*handling international_passport_bio_page upload process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page, 's3');
        } 

        /*handling conference_attendence_letter upload process*/
        if($request->hasFile('conference_attendence_letter')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Conference Attendence Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->conference_attendence_letter, 's3');
        }

        TSASNominationCreated::dispatch($tSASNomination);
        return $this->sendResponse($tSASNomination->toArray(), 'T S A S Nomination saved successfully');
    }

    /**
     * Display the specified TSASNomination.
     * GET|HEAD /tSASNominations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization) {
        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::find($id);

        if (empty($tSASNomination)) {
            $nominationRequest = NominationRequest::find($id);
            if (empty($nominationRequest)) {
                return $this->sendError('T S A S Nomination not found');
            }

            $tSASNomination = $nominationRequest->tsas_submission;
        }

        /*class constructor to fetch institution*/
        $tETFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/institutions/".$tSASNomination->tf_iterum_portal_institution_id;
        $payload = ['_method'=>'GET', 'id'=>$tSASNomination->tf_iterum_portal_institution_id];
        $institution = $tETFundServer->get_row_records_from_server($url_path, $payload);

        /*class constructor to fetch country*/
        $tETFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/countries/".$tSASNomination->tf_iterum_portal_country_id;
        $payload = ['_method'=>'GET', 'id'=>$tSASNomination->tf_iterum_portal_country_id];
        $country = $tETFundServer->get_row_records_from_server($url_path, $payload);

        $tSASNomination->beneficiary = ($tSASNomination->beneficiary_institution_id != null) ? $tSASNomination->beneficiary : [];
        $tSASNomination->institution = ($institution != null) ? $institution : null;
        $tSASNomination->country = ($country != null) ? $country : null;
        $tSASNomination->user = ($tSASNomination->user_id != null) ? $tSASNomination->user : [];

        return $this->sendResponse($tSASNomination->toArray(), 'T S A S Nomination retrieved successfully');
    }

    /**
     * Update the specified TSASNomination in storage.
     * PUT/PATCH /tSASNominations/{id}
     *
     * @param int $id
     * @param UpdateTSASNominationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTSASNominationAPIRequest $request, Organization $organization)
    {
        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::find($id);

        if (empty($tSASNomination)) {
            return $this->sendError('T S A S Nomination not found');
        }

        $tSASNomination->fill($request->all());
        $tSASNomination->save();
        $nominationRequest = $tSASNomination->nomination_request;
        
        /*handling passport_photo update process*/
        if($request->hasFile('passport_photo')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Passport Photo";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo, 's3');
        }

        /*handling admission_letter update process*/
        if($request->hasFile('admission_letter')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Admission Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->admission_letter, 's3');
        }

        /*handling health_report update process*/
        if($request->hasFile('health_report')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Health Report";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->health_report, 's3');
        }

        /*handling international_passport_bio_page update process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page, 's3');
        }

        /*handling conference_attendence_letter update process*/
        if($request->hasFile('conference_attendence_letter')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Conference Attendence Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->conference_attendence_letter, 's3');
        }

        TSASNominationUpdated::dispatch($tSASNomination);
        return $this->sendResponse($tSASNomination->toArray(), 'TSASNomination updated successfully');
    }

    /**
     * Remove the specified TSASNomination from storage.
     * DELETE /tSASNominations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::find($id);

        if (empty($tSASNomination)) {
            return $this->sendError('T S A S Nomination not found');
        }

        $tSASNomination->delete();
        TSASNominationDeleted::dispatch($tSASNomination);
        return $this->sendSuccess('T S A S Nomination deleted successfully');
    }
}
