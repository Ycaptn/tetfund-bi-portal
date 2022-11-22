<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ASTDNomination as TPNomination;
use App\Models\NominationRequest;

use App\Events\TPNominationCreated;
use App\Events\TPNominationUpdated;
use App\Events\TPNominationDeleted;

use App\Http\Requests\API\CreateTPNominationAPIRequest;
use App\Http\Requests\API\UpdateTPNominationAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use App\Managers\TETFundServer;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class TPNominationController
 * @package TETFund\ASTD\Controllers\API
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
    public function index(Request $request, Organization $organization)
    {
        $query = TPNomination::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id, 'type_of_nomination', 'TP');
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
        $input = $request->all();
        $input['type_of_nomination'] = 'TP';

        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::create($input);

        $nominationRequest = NominationRequest::find($request->nomination_request_id);
        $nominationRequest->details_submitted = 1;
        $nominationRequest->save();
        
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
        $tETFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/institutions/".$tPNomination->tf_iterum_portal_institution_id;
        $payload = ['_method'=>'GET', 'id'=>$tPNomination->tf_iterum_portal_institution_id];
        $institution = $tETFundServer->get_row_records_from_server($url_path, $payload);

        /*class constructor to fetch country*/
        $tETFundServer = new TETFundServer();
        $url_path ="tetfund-astd-api/countries/".$tPNomination->tf_iterum_portal_country_id;
        $payload = ['_method'=>'GET', 'id'=>$tPNomination->tf_iterum_portal_country_id];
        $country = $tETFundServer->get_row_records_from_server($url_path, $payload);

        $tPNomination->beneficiary = ($tPNomination->beneficiary_institution_id != null) ? $tPNomination->beneficiary : [];
        $tPNomination->institution = ($institution != null) ? $institution : null;
        $tPNomination->country = ($country != null) ? $country : null;
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

        $tPNomination->fill($request->all());
        $tPNomination->save();
        
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

        $tPNomination->delete();
        TPNominationDeleted::dispatch($tPNomination);
        return $this->sendSuccess('T P Nomination deleted successfully');
    }
}
