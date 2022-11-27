<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ASTDNomination;
use App\Models\NominationRequest;

use App\Events\ASTDNominationCreated;
use App\Events\ASTDNominationUpdated;
use App\Events\ASTDNominationDeleted;

use App\Http\Requests\API\CreateASTDNominationAPIRequest;
use App\Http\Requests\API\UpdateASTDNominationAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;
use App\Managers\TETFundServer;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class ASTDNominationController
 * @package App\Http\Controllers\API
 */

class ASTDNominationAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the ASTDNomination.
     * GET|HEAD /aSTDNominations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $org)
    {
        $query = ASTDNomination::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id, 'type_of_nomination', 'ASTD');
        }

        $tPNominations = $this->showAll($query->get());

        return $this->sendResponse($tPNominations->toArray(), 'A S T D Nominations retrieved successfully');
    }

    /**
     * Store a newly created ASTDNomination in storage.
     * POST /aSTDNominations
     *
     * @param CreateASTDNominationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateASTDNominationAPIRequest $request, Organization $organization)
    {
        $input = $request->all();
        $input['type_of_nomination'] = 'ASTD';

        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::create($input);
        
        $nominationRequest = NominationRequest::find($request->nomination_request_id);
        $nominationRequest->details_submitted = 1;
        $nominationRequest->save();

       //handling attachments
        $attachement_and_final_response = self::handle_attachments($request, $aSTDNomination, $nominationRequest);
        if ($attachement_and_final_response) {
            return $attachement_and_final_response;
        }

        return $this->sendError('Error encountered while processing attachments');
    }

    //handling attachement 
    public function handle_attachments($request, $aSTDNomination, $nominationRequest) {
         /*handling passport_photo upload process*/
        if($request->hasFile('passport_photo')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Passport Photo";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling admission_letter upload process*/
        if($request->hasFile('admission_letter')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Admission Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->admission_letter);
        } 

        /*handling health_report upload process*/
        if($request->hasFile('health_report')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Health Report";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->health_report);
        } 

        /*handling international_passport_bio_page upload process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page);
        } 

        /*handling conference_attendence_letter upload process*/
        if($request->hasFile('conference_attendence_letter')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Conference Attendence Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->conference_attendence_letter);
        }

        ASTDNominationCreated::dispatch($aSTDNomination);
        return $this->sendResponse($aSTDNomination->toArray(), 'A S T D Nomination saved successfully');
    }

    /**
     * Display the specified ASTDNomination.
     * GET|HEAD /aSTDNominations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization) {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            $nominationRequest = NominationRequest::find($id);
            if (empty($nominationRequest)) {
                return $this->sendError('A S T D Nomination not found');
            }

            $aSTDNomination = $nominationRequest->astd_submission;
        }

        /*class constructor to fetch institution*/
        $tETFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/institutions/".$aSTDNomination->tf_iterum_portal_institution_id;
        $payload = ['_method'=>'GET', 'id'=>$aSTDNomination->tf_iterum_portal_institution_id];
        $institution = $tETFundServer->get_row_records_from_server($url_path, $payload);

        /*class constructor to fetch country*/
        $tETFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/countries/".$aSTDNomination->tf_iterum_portal_country_id;
        $payload = ['_method'=>'GET', 'id'=>$aSTDNomination->tf_iterum_portal_country_id];
        $country = $tETFundServer->get_row_records_from_server($url_path, $payload);

        $aSTDNomination->beneficiary = ($aSTDNomination->beneficiary_institution_id != null) ? $aSTDNomination->beneficiary : [];
        $aSTDNomination->institution = ($institution != null) ? $institution : null;
        $aSTDNomination->country = ($country != null) ? $country : null;
        $aSTDNomination->user = ($aSTDNomination->user_id != null) ? $aSTDNomination->user : [];

        return $this->sendResponse($aSTDNomination->toArray(), 'A S T D Nomination retrieved successfully');
    }

    /**
     * Update the specified ASTDNomination in storage.
     * PUT/PATCH /aSTDNominations/{id}
     *
     * @param int $id
     * @param UpdateASTDNominationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateASTDNominationAPIRequest $request, Organization $organization)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            return $this->sendError('A S T D Nomination not found');
        }

        $aSTDNomination->fill($request->all());
        $aSTDNomination->save();
        $nominationRequest = $aSTDNomination->nomination_request;
        
        /*handling passport_photo update process*/
        if($request->hasFile('passport_photo')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Passport Photo";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachement($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling admission_letter update process*/
        if($request->hasFile('admission_letter')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Admission Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachement($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->admission_letter);
        }

        /*handling health_report update process*/
        if($request->hasFile('health_report')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Health Report";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachement($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->health_report);
        }

        /*handling international_passport_bio_page update process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachement($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page);
        }

        /*handling conference_attendence_letter update process*/
        if($request->hasFile('conference_attendence_letter')) {
            $label = $aSTDNomination->first_name . " " . $aSTDNomination->last_name . " ASTDNomination Conference Attendence Letter";
            $discription = "This " . strtolower("Document contains the $label");

            $attachement = $nominationRequest->get_specific_attachement($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->conference_attendence_letter);
        }

        ASTDNominationUpdated::dispatch($aSTDNomination);
        return $this->sendResponse($aSTDNomination->toArray(), 'ASTDNomination updated successfully');
    }

    /**
     * Remove the specified ASTDNomination from storage.
     * DELETE /aSTDNominations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            return $this->sendError('A S T D Nomination not found');
        }

        $aSTDNomination->delete();
        ASTDNominationDeleted::dispatch($aSTDNomination);
        return $this->sendSuccess('A S T D Nomination deleted successfully');
    }
}
