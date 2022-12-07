<?php

namespace App\Http\Controllers\Models;

use App\Models\TSASNomination;
use App\Models\BeneficiaryMember;
use App\Models\SubmissionRequest;

use App\Events\TSASNominationCreated;
use App\Events\TSASNominationUpdated;
use App\Events\TSASNominationDeleted;

use App\Http\Requests\CreateTSASNominationRequest;
use App\Http\Requests\UpdateTSASNominationRequest;

use App\DataTables\NominationRequestDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TSASNominationController extends BaseController
{
    /**
     * Display a listing of the TSASNomination.
     *
     * @param NominationRequestDataTable $tSASNominationDataTable
     * @return Response
     */
    public function index(Organization $org, NominationRequestDataTable $tSASNominationDataTable)
    {
        $current_user = Auth()->user();
        $user_beneficiary_id = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary_id;   //BI beneficiary_id

        if (isset(request()->view_type) && (request()->view_type == 'hoi_approved' || request()->view_type == 'final_nominations') && $current_user->hasRole('BI-desk-officer')) {
            $all_existing_submissions = SubmissionRequest::where('status', 'not-submitted')
                            ->orderBy('created_at', 'DESC')
                            ->get(['id', 'title', 'intervention_year1', 'intervention_year2', 'intervention_year3', 'intervention_year4', 'created_at']);
        }
        
        return $tSASNominationDataTable
                ->with('type', 'tsas')
                ->with('user_beneficiary_id', $user_beneficiary_id)
                ->render('tf-bi-portal::pages.t_s_a_s_nominations.index', [
                    'current_user' => $current_user,
                    'all_existing_submissions' => (isset($all_existing_submissions)) ? $all_existing_submissions : []
                ]);
    }

    /**
     * Show the form for creating a new TSASNomination.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('tf-bi-portal::pages.t_s_a_s_nominations.create');
    }

    /**
     * Store a newly created TSASNomination in storage.
     *
     * @param CreateTSASNominationRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateTSASNominationRequest $request)
    {
        $input = $request->all();

        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::create($input);

        //Flash::success('T S A S Nomination saved successfully.');

        TSASNominationCreated::dispatch($tSASNomination);
        return redirect(route('tetfund-tsas.tSASNominations.index'));
    }

    /**
     * Display the specified TSASNomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('T S A S Nomination not found');

            return redirect(route('tetfund-tsas.tSASNominations.index'));
        }

        return view('tf-bi-portal::pages.t_s_a_s_nominations.show')->with('tSASNomination', $tSASNomination);
    }

    /**
     * Show the form for editing the specified TSASNomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('T S A S Nomination not found');

            return redirect(route('tetfund-tsas.tSASNominations.index'));
        }

        return view('tf-bi-portal::pages.t_s_a_s_nominations.edit')->with('tSASNomination', $tSASNomination);
    }

    /**
     * Update the specified TSASNomination in storage.
     *
     * @param  int              $id
     * @param UpdateTSASNominationRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateTSASNominationRequest $request)
    {
        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('T S A S Nomination not found');

            return redirect(route('tetfund-tsas.tSASNominations.index'));
        }

        $tSASNomination->fill($request->all());
        $tSASNomination->save();

        //Flash::success('T S A S Nomination updated successfully.');
        
        TSASNominationUpdated::dispatch($tSASNomination);
        return redirect(route('tetfund-tsas.tSASNominations.index'));
    }

    /**
     * Remove the specified TSASNomination from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var TSASNomination $tSASNomination */
        $tSASNomination = TSASNomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('T S A S Nomination not found');

            return redirect(route('tetfund-tsas.tSASNominations.index'));
        }

        $tSASNomination->delete();

        //Flash::success('T S A S Nomination deleted successfully.');
        TSASNominationDeleted::dispatch($tSASNomination);
        return redirect(route('tetfund-tsas.tSASNominations.index'));
    }

        
    public function processBulkUpload(Organization $org, Request $request){

        $attachedFileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'), $attachedFileName);
        $path_to_file = public_path('uploads').'/'.$attachedFileName;

        //Process each line
        $loop = 1;
        $errors = [];
        $lines = file($path_to_file);

        if (count($lines) > 1) {
            foreach ($lines as $line) {
                
                if ($loop > 1) {
                    $data = explode(',', $line);
                    // if (count($invalids) > 0) {
                    //     array_push($errors, $invalids);
                    //     continue;
                    // }else{
                    //     //Check if line is valid
                    //     if (!$valid) {
                    //         $errors[] = $msg;
                    //     }
                    // }
                }
                $loop++;
            }
        }else{
            $errors[] = 'The uploaded csv file is empty';
        }
        
        if (count($errors) > 0) {
            return $this->sendError($this->array_flatten($errors), 'Errors processing file');
        }
        return $this->sendResponse($subject->toArray(), 'Bulk upload completed successfully');
    }
}
