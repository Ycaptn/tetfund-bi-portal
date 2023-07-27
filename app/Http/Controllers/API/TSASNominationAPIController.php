<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Beneficiary;
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
    public function store(CreateTSASNominationAPIRequest $request, Organization $organization) {

        $bi_beneficiary = Beneficiary::find($request->get('beneficiary_institution_id'));
        $input = $this->set_tsas_nominee_amounts($request->all(), $bi_beneficiary);

        if($input == false){
            NominationRequest::find($request->nomination_request_id)->delete();
            return $this->sendError('Something Went wrong please try again. If the problem persists please contact the system administrator.');
        }
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
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling admission_letter upload process*/
        if($request->hasFile('admission_letter')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Admission Letter";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->admission_letter);
        } 

        /*handling health_report upload process*/
        if($request->hasFile('health_report')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Health Report";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->health_report);
        } 

        /*handling curriculum_vitae upload process*/
        if($request->hasFile('curriculum_vitae')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Curriculum Vitae";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->curriculum_vitae);
        }

        /*handling signed_bond_with_beneficiary upload process*/
        if($request->hasFile('signed_bond_with_beneficiary')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Signed Bond with Beneficiary";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->signed_bond_with_beneficiary);
        } 

        /*handling international_passport_bio_page upload process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains $label");

            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page);
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
        $tetFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/countries/".$tSASNomination->tf_iterum_portal_country_id;
        $payload = ['_method'=>'GET', 'id'=>$tSASNomination->tf_iterum_portal_country_id];
        $country = $tetFundServer->get_row_records_from_server($url_path, $payload);

        $tSASNomination->beneficiary = ($tSASNomination->beneficiary_institution_id != null) ? $tSASNomination->beneficiary : [];
        $tSASNomination->country = $country;
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

        $bi_beneficiary = Beneficiary::find($request->get('beneficiary_institution_id'));
        $input = $this->set_tsas_nominee_amounts($request->all(), $bi_beneficiary);
        if($input == false){
            return $this->sendError('Something Went wrong please try again. If the problem persists please contact the system administrator.');
        }
        $tSASNomination->fill($input);
        $tSASNomination->save();
        $nominationRequest = $tSASNomination->nomination_request;
        
        /*handling passport_photo update process*/
        if($request->hasFile('passport_photo')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Passport Photo";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->passport_photo);
        }

        /*handling admission_letter update process*/
        if($request->hasFile('admission_letter')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Admission Letter";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->admission_letter);
        }

        /*handling health_report update process*/
        if($request->hasFile('health_report')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Health Report";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->health_report);
        }

        /*handling curriculum_vitae update process*/
        if($request->hasFile('curriculum_vitae')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Curriculum Vitae ";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->curriculum_vitae);
        }

        /*handling signed_bond_with_beneficiary update process*/
        if($request->hasFile('signed_bond_with_beneficiary')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination Signed Bond with Beneficiary ";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old passport photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old passport photo
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->signed_bond_with_beneficiary);
        }

        /*handling international_passport_bio_page update process*/
        if($request->hasFile('international_passport_bio_page')) {
            $label = $tSASNomination->first_name . " " . $tSASNomination->last_name . " TSASNomination International Passport Bio Page";
            $discription = "This " . strtolower("Document contains $label");

            $attachement = $nominationRequest->get_specific_attachment($nominationRequest->id, $label); //looking for old international_passport_bio_page photo
            if ($attachement != null) {
                $nominationRequest->delete_attachment($label); // delete old international  passport bio page
            }
            $nominationRequest->attach(auth()->user(), $label, $discription, $request->international_passport_bio_page);
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

        $nominationRequest = $tSASNomination->nomination_request;
        $nominationRequest->details_submitted = false;
        $nominationRequest->save();

        $attachments = $nominationRequest->get_all_attachments($nominationRequest->id);

        if (!empty($attachments) && count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $nominationRequest->delete_attachment($attachment->label);
            }
        }

        $tSASNomination->delete();
        TSASNominationDeleted::dispatch($tSASNomination);
        return $this->sendSuccess('T S A S Nomination deleted successfully');
    }

    public function set_tsas_nominee_amounts($input, $bi_beneficiary) {
        /* server class constructor to retrieve amout settings */
        $pay_load = [ '_method' => 'GET', 'get_country_zone_through_country' => true];
        $tetFundServer = new TETFundServer();
        $tsas_amount_settings = $tetFundServer->get_all_data_list_from_server('tetfund-astd-api/tsas_country_zones/'.$input['tf_iterum_portal_country_id'], $pay_load);
        
        if(is_array($tsas_amount_settings) && count($tsas_amount_settings) == 0){
            return false;
        }
        if (!empty($tsas_amount_settings) && isset($input['degree_type']) && isset($input['country_nigeria_id'])) {
            $exchange_rate_to_naira = $tsas_amount_settings->dollar_exchange_rate_to_naira ?? optional($tsas_amount_settings->tsas_country)->exchange_rate_to_naira ?? 1;
            
            // flag to identified selected country
            $is_nigeria = $input['country_nigeria_id']==$input['tf_iterum_portal_country_id'] ? true : false;
            
            if ($input['degree_type'] == 'Ph.D') {

                // setting amount if TSAS is Ph.D request
                $input['tuition_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_tuition_amt??0) * $exchange_rate_to_naira) : ($input['is_science_program']==true ? $tsas_amount_settings->phd_local_science_tuition_amt??0 : $tsas_amount_settings->phd_local_art_tuition_amt??0);
                $input['stipend_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_stipend_amt??0) * $exchange_rate_to_naira) : 0;
                $input['passage_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_passage_amt??0) * $exchange_rate_to_naira) : 0;
                $input['medical_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_medical_health_ins_amt??0) * $exchange_rate_to_naira) : 0;
                $input['warm_clothing_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_warm_clothing_amt??0) * $exchange_rate_to_naira) : 0;
                $input['study_tours_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_study_tour_conference_amt??0) * $exchange_rate_to_naira) : 0;
                $input['education_materials_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_educational_material_amt??0) * $exchange_rate_to_naira) : 0;
                $input['thesis_research_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->phd_research_disertation_amt??0) * $exchange_rate_to_naira) : 0;
          
                $input['total_requested_amount'] = $input['tuition_amount'] + $input['stipend_amount'] + $input['passage_amount'] + $input['medical_amount'] + $input['warm_clothing_amount'] + $input['study_tours_amount'] + $input['education_materials_amount'] + $input['thesis_research_amount'];

            } elseif($input['degree_type'] == 'M.Sc') {

                // setting amount if TSAS is M.Sc request
                $input['tuition_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_tuition_amt??0) * $exchange_rate_to_naira) : ($input['is_science_program']==true ? $tsas_amount_settings->masters_local_science_tuition_amt??0 : $tsas_amount_settings->masters_local_art_tuition_amt??0);
                $input['stipend_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_stipend_amt ?? 0) * $exchange_rate_to_naira) : 0;
                $input['passage_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_passage_amt ?? 0) * $exchange_rate_to_naira) : 0;
                $input['medical_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_medical_health_ins_amt ?? 0) * $exchange_rate_to_naira) : 0;
                $input['warm_clothing_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_warm_clothing_amt ?? 0) * $exchange_rate_to_naira) : 0;
                $input['study_tours_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_study_tour_conference_amt ?? 0) * $exchange_rate_to_naira) : 0;
                $input['education_materials_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_educational_material_amt ?? 0) * $exchange_rate_to_naira) : 0;
                $input['thesis_research_amount'] = $is_nigeria!=true ? (floatval($tsas_amount_settings->masters_research_disertation_amt ?? 0) * $exchange_rate_to_naira) : 0;
          
                $input['total_requested_amount'] = $input['tuition_amount'] + $input['stipend_amount'] + $input['passage_amount'] + $input['medical_amount'] + $input['warm_clothing_amount'] + $input['study_tours_amount'] + $input['education_materials_amount'] + $input['thesis_research_amount'];

            } elseif($input['degree_type'] == 'Bench-Work') {

                // setting amount if TSAS is Bench-Work request
                $input['tuition_amount'] = floatval($tsas_amount_settings->bw_tuition_amt ?? 0) * $exchange_rate_to_naira;
                $input['stipend_amount'] = floatval($tsas_amount_settings->bw_stipend_amt ?? 0) * $exchange_rate_to_naira;
                $input['passage_amount'] = floatval($tsas_amount_settings->bw_passage_amt ?? 0) * $exchange_rate_to_naira;
                $input['medical_amount'] = floatval($tsas_amount_settings->bw_medical_health_ins_amt ?? 0) * $exchange_rate_to_naira;
                $input['warm_clothing_amount'] = floatval($tsas_amount_settings->bw_warm_clothing_amt ?? 0) * $exchange_rate_to_naira;
                $input['study_tours_amount'] = floatval($tsas_amount_settings->bw_study_tour_conference_amt ?? 0) * $exchange_rate_to_naira;
                $input['education_materials_amount'] = floatval($tsas_amount_settings->bw_educational_material_amt ?? 0) * $exchange_rate_to_naira;
                $input['thesis_research_amount'] = floatval($tsas_amount_settings->bw_research_disertation_amt ?? 0) * $exchange_rate_to_naira;
          
                $input['total_requested_amount'] = $input['tuition_amount'] + $input['stipend_amount'] + $input['passage_amount'] + $input['medical_amount'] + $input['warm_clothing_amount'] + $input['study_tours_amount'] + $input['education_materials_amount'] + $input['thesis_research_amount'];

            } elseif($input['degree_type'] == 'Post-Doc') {

                // setting amount if TSAS is Post-Doc request
                $input['tuition_amount'] = floatval($tsas_amount_settings->postdoc_tuition_amt ?? 0) * $exchange_rate_to_naira;
                $input['stipend_amount'] = floatval($tsas_amount_settings->postdoc_stipend_amt ?? 0) * $exchange_rate_to_naira;
                $input['passage_amount'] = floatval($tsas_amount_settings->postdoc_passage_amt ?? 0) * $exchange_rate_to_naira;
                $input['medical_amount'] = floatval($tsas_amount_settings->postdoc_medical_health_ins_amt ?? 0) * $exchange_rate_to_naira;
                $input['warm_clothing_amount'] = floatval($tsas_amount_settings->postdoc_warm_clothing_amt ?? 0) * $exchange_rate_to_naira;
                $input['study_tours_amount'] = floatval($tsas_amount_settings->postdoc_study_tour_conference_amt ?? 0) * $exchange_rate_to_naira;
                $input['education_materials_amount'] = floatval($tsas_amount_settings->postdoc_educational_material_amt ?? 0) * $exchange_rate_to_naira;
                $input['thesis_research_amount'] = floatval($tsas_amount_settings->postdoc_research_disertation_amt ?? 0) * $exchange_rate_to_naira;
          
                $input['total_requested_amount'] = $input['tuition_amount'] + $input['stipend_amount'] + $input['passage_amount'] + $input['medical_amount'] + $input['warm_clothing_amount'] + $input['study_tours_amount'] + $input['education_materials_amount'] + $input['thesis_research_amount'];
            }
        }

        return $input;
    }
}
