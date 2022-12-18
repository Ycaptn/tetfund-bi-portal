<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CANomination;
use App\Models\NominationRequest;

use App\Events\CANominationCreated;
use App\Events\CANominationUpdated;
use App\Events\CANominationDeleted;

use App\Http\Requests\API\CreateCANominationAPIRequest;
use App\Http\Requests\API\UpdateCANominationAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;
use App\Managers\TETFundServer;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class CANominationController
 * @package App\Http\Controllers\API
 */

class CANominationAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the CANomination.
     * GET|HEAD /cANominations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $org)
    {
        $query = CANomination::query();

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

        return $this->sendResponse($tPNominations->toArray(), 'C A Nominations retrieved successfully');
    }

    /**
     * Store a newly created CANomination in storage.
     * POST /cANominations
     *
     * @param CreateCANominationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCANominationAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var CANomination $cANomination */
        $cANomination = CANomination::create($input);
        
        $nominationRequest = NominationRequest::find($request->nomination_request_id);
        $nominationRequest->details_submitted = 1;
        $nominationRequest->save();

       //handling attachments
        $attachement_and_final_response = self::handle_attachments($request, $cANomination, $nominationRequest);
        if ($attachement_and_final_response) {
            return $attachement_and_final_response;
        }

        return $this->sendError('Error encountered while processing attachments');
    }

    //handling attachement 
    public function handle_attachments($request, $cANomination, $nominationRequest) {
         /*handling passport_photo upload process*/
        if($request->hasFile('passport_photo')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination Passport Photo";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling conference_attendance_letter upload process*/
        if($request->hasFile('conference_attendance_letter')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination Admission Letter";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->conference_attendance_letter);
        } 

        /*handling paper_presentation upload process*/
        if($request->hasFile('paper_presentation')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination Presentation Paper ";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->paper_presentation);
        } 

        /*handling international_passport_bio_page upload process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page);
        } 

        CANominationCreated::dispatch($cANomination);
        return $this->sendResponse($cANomination->toArray(), 'C A Nomination saved successfully');
    }

    /**
     * Display the specified CANomination.
     * GET|HEAD /cANominations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization) {
        /** @var CANomination $cANomination */
        $cANomination = CANomination::find($id);

        if (empty($cANomination)) {
            $nominationRequest = NominationRequest::find($id);
            if (empty($nominationRequest)) {
                return $this->sendError('C A Nomination not found');
            }

            $cANomination = $nominationRequest->tsas_submission;
        }

        /*class constructor to fetch conference*/
        $tETFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/conferences/".$cANomination->tf_iterum_portal_conference_id;
        $payload = ['_method'=>'GET', 'id'=>$cANomination->tf_iterum_portal_conference_id];
        $conference = $tETFundServer->get_row_records_from_server($url_path, $payload);

        $cANomination->beneficiary = ($cANomination->beneficiary_conference_id != null) ? $cANomination->beneficiary : [];
        $cANomination->conference = ($conference != null) ? $conference : null;
        $cANomination->country = optional($conference)->country;
        $cANomination->user = ($cANomination->user_id != null) ? $cANomination->user : [];

        return $this->sendResponse($cANomination->toArray(), 'C A Nomination retrieved successfully');
    }

    /**
     * Update the specified CANomination in storage.
     * PUT/PATCH /cANominations/{id}
     *
     * @param int $id
     * @param UpdateCANominationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCANominationAPIRequest $request, Organization $organization)
    {
        /** @var CANomination $cANomination */
        $cANomination = CANomination::find($id);

        if (empty($cANomination)) {
            return $this->sendError('C A Nomination not found');
        }

        $cANomination->fill($request->all());
        $cANomination->save();
        $nominationRequest = $cANomination->nomination_request;
        
        /*handling passport_photo update process*/
        if($request->hasFile('passport_photo')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination Passport Photo";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling conference_attendance_letter update process*/
        if($request->hasFile('conference_attendance_letter')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination Admission Letter";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->conference_attendance_letter);
        }

        /*handling paper_presentation update process*/
        if($request->hasFile('paper_presentation')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination Presentation Paper ";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->paper_presentation);
        }

        /*handling international_passport_bio_page update process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $cANomination->first_name . " " . $cANomination->last_name . " CANomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old international_passport_bio_page photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old international  passport bio page
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page);
        }

        CANominationUpdated::dispatch($cANomination);
        return $this->sendResponse($cANomination->toArray(), 'CANomination updated successfully');
    }

    /**
     * Remove the specified CANomination from storage.
     * DELETE /cANominations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var CANomination $cANomination */
        $cANomination = CANomination::find($id);

        if (empty($cANomination)) {
            return $this->sendError('C A Nomination not found');
        }

        $nominationRequest = $cANomination->nomination_request;
        $nominationRequest->details_submitted = false;
        $nominationRequest->save();

        $attachments = $nominationRequest->get_all_attachments($nominationRequest->id);

        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $nominationRequest->delete_attachment($attachment->label);
            }
        }

        $cANomination->delete();
        CANominationDeleted::dispatch($cANomination);
        return $this->sendSuccess('C A Nomination deleted successfully');
    }
}
