<?php

namespace App\Http\Controllers\Models;

use App\Models\ASTDNomination;
use App\Models\BeneficiaryMember;
use App\Models\SubmissionRequest;

use App\Events\ASTDNominationCreated;
use App\Events\ASTDNominationUpdated;
use App\Events\ASTDNominationDeleted;

use App\Http\Requests\CreateASTDNominationRequest;
use App\Http\Requests\UpdateASTDNominationRequest;

use App\DataTables\NominationRequestDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ASTDNominationController extends BaseController
{
    /**
     * Display a listing of the ASTDNomination.
     *
     * @param NominationRequestDataTable $aSTDNominationDataTable
     * @return Response
     */
    public function index(Organization $org, NominationRequestDataTable $aSTDNominationDataTable)
    {
        $current_user = Auth()->user();
        $user_beneficiary_id = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary_id;   //BI beneficiary_id

        if (isset(request()->view_type) && (request()->view_type == 'hoi_approved' || request()->view_type == 'final_nominations') && $current_user->hasRole('bi-desk-officer')) {
            $all_existing_submissions = SubmissionRequest::where('status', 'not-submitted')
                            ->orderBy('created_at', 'DESC')
                            ->get(['id', 'title', 'intervention_year1', 'intervention_year2', 'intervention_year3', 'intervention_year4', 'created_at']);
        }
        
        return $aSTDNominationDataTable
                ->with('type', 'astd')
                ->with('user_beneficiary_id', $user_beneficiary_id)
                ->render('tf-bi-portal::pages.a_s_t_d_nominations.index', [
                    'current_user' => $current_user,
                    'all_existing_submissions' => (isset($all_existing_submissions)) ? $all_existing_submissions : []
                ]);
    }

    /**
     * Show the form for creating a new ASTDNomination.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('tf-bi-portal::pages.a_s_t_d_nominations.create');
    }

    /**
     * Store a newly created ASTDNomination in storage.
     *
     * @param CreateASTDNominationRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateASTDNominationRequest $request)
    {
        $input = $request->all();
        $input['type_of_nomination'] = 'ASTD';

        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::create($input);

        //Flash::success('A S T D Nomination saved successfully.');

        ASTDNominationCreated::dispatch($aSTDNomination);
        return redirect(route('tetfund-astd.aSTDNominations.index'));
    }

    /**
     * Display the specified ASTDNomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            //Flash::error('A S T D Nomination not found');

            return redirect(route('tetfund-astd.aSTDNominations.index'));
        }

        return view('tf-bi-portal::pages.a_s_t_d_nominations.show')->with('aSTDNomination', $aSTDNomination);
    }

    /**
     * Show the form for editing the specified ASTDNomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            //Flash::error('A S T D Nomination not found');

            return redirect(route('tetfund-astd.aSTDNominations.index'));
        }

        return view('tf-bi-portal::pages.a_s_t_d_nominations.edit')->with('aSTDNomination', $aSTDNomination);
    }

    /**
     * Update the specified ASTDNomination in storage.
     *
     * @param  int              $id
     * @param UpdateASTDNominationRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateASTDNominationRequest $request)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            //Flash::error('A S T D Nomination not found');

            return redirect(route('tetfund-astd.aSTDNominations.index'));
        }

        $aSTDNomination->fill($request->all());
        $aSTDNomination->save();

        //Flash::success('A S T D Nomination updated successfully.');
        
        ASTDNominationUpdated::dispatch($aSTDNomination);
        return redirect(route('tetfund-astd.aSTDNominations.index'));
    }

    /**
     * Remove the specified ASTDNomination from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            //Flash::error('A S T D Nomination not found');

            return redirect(route('tetfund-astd.aSTDNominations.index'));
        }

        $aSTDNomination->delete();

        //Flash::success('A S T D Nomination deleted successfully.');
        ASTDNominationDeleted::dispatch($aSTDNomination);
        return redirect(route('tetfund-astd.aSTDNominations.index'));
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
