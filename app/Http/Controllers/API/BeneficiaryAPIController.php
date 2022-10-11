<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Beneficiary;

use App\Events\BeneficiaryCreated;
use App\Events\BeneficiaryUpdated;
use App\Events\BeneficiaryDeleted;

use App\Http\Requests\API\CreateBeneficiaryAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class BeneficiaryController
 * @package App\Http\Controllers\API
 */

class BeneficiaryAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the Beneficiary.
     * GET|HEAD /beneficiaries
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = Beneficiary::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $beneficiaries = $this->showAll($query->get());

        return $this->sendResponse($beneficiaries->toArray(), 'Beneficiaries retrieved successfully');
    }

    /**
     * Store a newly created Beneficiary in storage.
     * POST /beneficiaries
     *
     * @param CreateBeneficiaryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBeneficiaryAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::create($input);
        
        BeneficiaryCreated::dispatch($beneficiary);
        return $this->sendResponse($beneficiary->toArray(), 'Beneficiary saved successfully');
    }

    /**
     * Display the specified Beneficiary.
     * GET|HEAD /beneficiaries/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            return $this->sendError('Beneficiary not found');
        }

        return $this->sendResponse($beneficiary->toArray(), 'Beneficiary retrieved successfully');
    }

    /**
     * Update the specified Beneficiary in storage.
     * PUT/PATCH /beneficiaries/{id}
     *
     * @param int $id
     * @param UpdateBeneficiaryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBeneficiaryAPIRequest $request, Organization $organization)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            return $this->sendError('Beneficiary not found');
        }

        $beneficiary->fill($request->all());
        $beneficiary->save();
        
        BeneficiaryUpdated::dispatch($beneficiary);
        return $this->sendResponse($beneficiary->toArray(), 'Beneficiary updated successfully');
    }

    /**
     * Remove the specified Beneficiary from storage.
     * DELETE /beneficiaries/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            return $this->sendError('Beneficiary not found');
        }

        $beneficiary->delete();
        BeneficiaryDeleted::dispatch($beneficiary);
        return $this->sendSuccess('Beneficiary deleted successfully');
    }
}
