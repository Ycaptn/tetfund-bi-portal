<?php

namespace App\Http\Controllers\Models;

use App\Models\CANomination;
use App\Models\BeneficiaryMember;
use App\Models\SubmissionRequest;

use App\Events\CANominationCreated;
use App\Events\CANominationUpdated;
use App\Events\CANominationDeleted;

use App\Http\Requests\CreateCANominationRequest;
use App\Http\Requests\UpdateCANominationRequest;

use App\DataTables\NominationRequestDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CANominationController extends BaseController
{
    /**
     * Display a listing of the CANomination.
     *
     * @param NominationRequestDataTable $cANominationDataTable
     * @return Response
     */
    public function index(Organization $org, NominationRequestDataTable $cANominationDataTable)
    {
        $current_user = Auth()->user();
        $user_beneficiary_id = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary_id;   //BI beneficiary_id

        if (isset(request()->view_type) && (request()->view_type == 'hoi_approved' || request()->view_type == 'final_nominations') && $current_user->hasRole('BI-desk-officer')) {
            $all_existing_submissions = SubmissionRequest::where('status', 'not-submitted')
                            ->orderBy('created_at', 'DESC')
                            ->get(['id', 'title', 'intervention_year1', 'intervention_year2', 'intervention_year3', 'intervention_year4', 'created_at']);
        }
        
        return $cANominationDataTable
                ->with('type', 'ca')
                ->with('user_beneficiary_id', $user_beneficiary_id)
                ->render('tf-bi-portal::pages.c_a_nominations.index', [
                    'current_user' => $current_user,
                    'all_existing_submissions' => (isset($all_existing_submissions)) ? $all_existing_submissions : []
                ]);
    }

    /**
     * Show the form for creating a new CANomination.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('tf-bi-portal::pages.c_a_nominations.create');
    }

    /**
     * Store a newly created CANomination in storage.
     *
     * @param CreateCANominationRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateCANominationRequest $request)
    {
        $input = $request->all();

        /** @var CANomination $tSASNomination */
        $tSASNomination = CANomination::create($input);

        //Flash::success('C A Nomination saved successfully.');

        CANominationCreated::dispatch($tSASNomination);
        return redirect(route('tetfund-ca.cANominations.index'));
    }

    /**
     * Display the specified CANomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        /** @var CANomination $tSASNomination */
        $tSASNomination = CANomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('C A Nomination not found');

            return redirect(route('tetfund-ca.cANominations.index'));
        }

        return view('tf-bi-portal::pages.c_a_nominations.show')->with('tSASNomination', $tSASNomination);
    }

    /**
     * Show the form for editing the specified CANomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        /** @var CANomination $tSASNomination */
        $tSASNomination = CANomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('C A Nomination not found');

            return redirect(route('tetfund-ca.cANominations.index'));
        }

        return view('tf-bi-portal::pages.c_a_nominations.edit')->with('tSASNomination', $tSASNomination);
    }

    /**
     * Update the specified CANomination in storage.
     *
     * @param  int              $id
     * @param UpdateCANominationRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateCANominationRequest $request)
    {
        /** @var CANomination $tSASNomination */
        $tSASNomination = CANomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('C A Nomination not found');

            return redirect(route('tetfund-ca.cANominations.index'));
        }

        $tSASNomination->fill($request->all());
        $tSASNomination->save();

        //Flash::success('C A Nomination updated successfully.');
        
        CANominationUpdated::dispatch($tSASNomination);
        return redirect(route('tetfund-ca.cANominations.index'));
    }

    /**
     * Remove the specified CANomination from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var CANomination $tSASNomination */
        $tSASNomination = CANomination::find($id);

        if (empty($tSASNomination)) {
            //Flash::error('C A Nomination not found');

            return redirect(route('tetfund-ca.cANominations.index'));
        }

        $tSASNomination->delete();

        //Flash::success('C A Nomination deleted successfully.');
        CANominationDeleted::dispatch($tSASNomination);
        return redirect(route('tetfund-ca.cANominations.index'));
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
