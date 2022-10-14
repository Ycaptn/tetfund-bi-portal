<?php

namespace App\Http\Controllers\Models;

//use TETFund\ASTD\DataTables\ASTDNominationDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Managers\TETFundServer;


class ASTDNominationController extends BaseController
{
    /**
     * Display a listing of the ASTDNomination.
     *
     * @param ASTDNominationDataTable $aSTDNominationDataTable
     * @return Response
     */
    /*ASTDNominationDataTable $aSTDNominationDataTable*/
    public function index(Organization $org, Request $request)
    {
        $current_user = Auth()->user();

        /*class constructor*/
        $tETFundServer = new TETFundServer();
        $get_astd_nomination_list = $tETFundServer->getASTDNominationList();

        $cdv_a_s_t_d_nominations = new \Hasob\FoundationCore\View\Components\CardDataView(ASTDNomination::class, "tetfund-astd-module::pages.a_s_t_d_nominations.card_view_item");
        $cdv_a_s_t_d_nominations->setDataQuery(['organization_id'=>$org->id, 'type_of_nomination'=>'ASTD'])
                //->addDataGroup('label','field','value')
                //->addDataOrder('id','DESC')
                ->setSearchFields(['first_name','last_name'])
                ->addDataOrder('created_at','DESC')
                ->enableSearch(true)
                ->enablePagination(true)
                ->setPaginationLimit(20)
                ->setSearchPlaceholder('Search ASTDNomination By First or Last Name');

        return view('tf-bi-portal::pages.a_s_t_d_nominations.card_view_index')
            ->with('organization', $org)
            ->with('current_user', $current_user)
            ->with('months_list', BaseController::monthsList())
            ->with('states_list', BaseController::statesList())
            ->with('cdv_a_s_t_d_nominations', $cdv_a_s_t_d_nominations);
    }

    /**
     * Show the form for creating a new ASTDNomination.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('tetfund-astd-module::pages.a_s_t_d_nominations.create');
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

        return view('tetfund-astd-module::pages.a_s_t_d_nominations.show')->with('aSTDNomination', $aSTDNomination);
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

        return view('tetfund-astd-module::pages.a_s_t_d_nominations.edit')->with('aSTDNomination', $aSTDNomination);
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
