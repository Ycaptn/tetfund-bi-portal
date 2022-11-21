<?php

namespace App\Http\Controllers\Models;

use App\Models\ASTDNomination as TPNomination;

use App\Events\TPNominationCreated;
use App\Events\TPNominationUpdated;
use App\Events\TPNominationDeleted;

use App\Http\Requests\CreateTPNominationRequest;
use App\Http\Requests\UpdateTPNominationRequest;

use App\DataTables\TPNominationDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TPNominationController extends BaseController
{
    /**
     * Display a listing of the TPNomination.
     *
     * @param TPNominationDataTable $tPNominationDataTable
     * @return Response
     */
    public function index(Organization $org, TPNominationDataTable $tPNominationDataTable)
    {
        $current_user = Auth()->user();

        $cdv_t_p_nominations = new CardDataView(TPNomination::class, "tf-bi-portal::pages.t_p_nominations.card_view_item");
        $cdv_t_p_nominations->setDataQuery(['organization_id'=>$org->id, 'type_of_nomination'=>'TP'])
                        //->addDataGroup('label','field','value')
                        //->addDataOrder('id','DESC')
                        ->setSearchFields(['first_name','last_name'])
                        ->addDataOrder('created_at','DESC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search TPNomination By First or Last Name');

        if (request()->expectsJson()){
            return $cdv_t_p_nominations->render();
        }

        return view('tf-bi-portal::pages.t_p_nominations.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_t_p_nominations', $cdv_t_p_nominations);

        /*
        return $tPNominationDataTable->render('tf-bi-portal::pages.t_p_nominations.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

    /**
     * Show the form for creating a new TPNomination.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('tf-bi-portal::pages.t_p_nominations.create');
    }

    /**
     * Store a newly created TPNomination in storage.
     *
     * @param CreateTPNominationRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateTPNominationRequest $request)
    {
        $input = $request->all();
        $input['type_of_nomination'] = 'TP';

        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::create($input);

        //Flash::success('T P Nomination saved successfully.');

        TPNominationCreated::dispatch($tPNomination);
        return redirect(route('tetfund-astd.tPNominations.index'));
    }

    /**
     * Display the specified TPNomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::find($id);

        if (empty($tPNomination)) {
            //Flash::error('T P Nomination not found');

            return redirect(route('tetfund-astd.tPNominations.index'));
        }

        return view('tf-bi-portal::pages.t_p_nominations.show')->with('tPNomination', $tPNomination);
    }

    /**
     * Show the form for editing the specified TPNomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::find($id);

        if (empty($tPNomination)) {
            //Flash::error('T P Nomination not found');

            return redirect(route('tetfund-astd.tPNominations.index'));
        }

        return view('tf-bi-portal::pages.t_p_nominations.edit')->with('tPNomination', $tPNomination);
    }

    /**
     * Update the specified TPNomination in storage.
     *
     * @param  int              $id
     * @param UpdateTPNominationRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateTPNominationRequest $request)
    {
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::find($id);

        if (empty($tPNomination)) {
            //Flash::error('T P Nomination not found');

            return redirect(route('tetfund-astd.tPNominations.index'));
        }

        $tPNomination->fill($request->all());
        $tPNomination->save();

        //Flash::success('T P Nomination updated successfully.');
        
        TPNominationUpdated::dispatch($tPNomination);
        return redirect(route('tetfund-astd.tPNominations.index'));
    }

    /**
     * Remove the specified TPNomination from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var TPNomination $tPNomination */
        $tPNomination = TPNomination::find($id);

        if (empty($tPNomination)) {
            //Flash::error('T P Nomination not found');

            return redirect(route('tetfund-astd.tPNominations.index'));
        }

        $tPNomination->delete();

        //Flash::success('T P Nomination deleted successfully.');
        TPNominationDeleted::dispatch($tPNomination);
        return redirect(route('tetfund-astd.tPNominations.index'));
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
