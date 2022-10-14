<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SubmissionRequest;

use App\Events\SubmissionRequestCreated;
use App\Events\SubmissionRequestUpdated;
use App\Events\SubmissionRequestDeleted;

use App\Http\Requests\API\CreateSubmissionRequestAPIRequest;
use App\Http\Requests\API\UpdateSubmissionRequestAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class SubmissionRequestController
 * @package App\Http\Controllers\API
 */

class SubmissionRequestAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the SubmissionRequest.
     * GET|HEAD /submissionRequests
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = SubmissionRequest::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $submissionRequests = $this->showAll($query->get());

        return $this->sendResponse($submissionRequests->toArray(), 'Submission Requests retrieved successfully');
    }

    /**
     * Store a newly created SubmissionRequest in storage.
     * POST /submissionRequests
     *
     * @param CreateSubmissionRequestAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSubmissionRequestAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::create($input);
        
        SubmissionRequestCreated::dispatch($submissionRequest);
        return $this->sendResponse($submissionRequest->toArray(), 'Submission Request saved successfully');
    }

    /**
     * Display the specified SubmissionRequest.
     * GET|HEAD /submissionRequests/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        return $this->sendResponse($submissionRequest->toArray(), 'Submission Request retrieved successfully');
    }

    /**
     * Update the specified SubmissionRequest in storage.
     * PUT/PATCH /submissionRequests/{id}
     *
     * @param int $id
     * @param UpdateSubmissionRequestAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubmissionRequestAPIRequest $request, Organization $organization)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        $submissionRequest->fill($request->all());
        $submissionRequest->save();
        
        SubmissionRequestUpdated::dispatch($submissionRequest);
        return $this->sendResponse($submissionRequest->toArray(), 'SubmissionRequest updated successfully');
    }

    /**
     * Remove the specified SubmissionRequest from storage.
     * DELETE /submissionRequests/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        $submissionRequest->delete();
        SubmissionRequestDeleted::dispatch($submissionRequest);
        return $this->sendSuccess('Submission Request deleted successfully');
    }
}