<?php

namespace App\Http\Controllers\Models;

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

    public function index(Organization $org, Request $request) {
        $current_user = Auth()->user();

        $pay_load = array();
        $pay_load['api_detail_page_url'] = url("tf-bi-portal/a_s_t_d_nominations/");
        $pay_load['_method'] = 'GET';

        /*class constructor*/
        $tETFundServer = new TETFundServer();
        $cdv_a_s_t_d_nominations = $tETFundServer->getASTDNominationList($pay_load);
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
        return redirect(route('tf-bi-portal.a_s_t_d_nominations.index'));
    }

    /**
     * Display the specified ASTDNomination.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id) {
        $pay_load = ['id' => $id, '_method' => 'GET'];

        $tETFundServer = new TETFundServer();   /*class constructor*/
        $aSTDNomination = $tETFundServer->get_row_records_from_server("tetfund-astd-api/a_s_t_d_nominations/$id", $pay_load);

        if (empty($aSTDNomination)) {
            //Flash::error('A S T D Nomination not found');
            return redirect(route('tf-bi-portal.a_s_t_d_nominations.index'));
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

            return redirect(route('tf-bi-portal.a_s_t_d_nominations.index'));
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

            return redirect(route('tf-bi-portal.a_s_t_d_nominations.index'));
        }

        $aSTDNomination->fill($request->all());
        $aSTDNomination->save();

        //Flash::success('A S T D Nomination updated successfully.');
        
        ASTDNominationUpdated::dispatch($aSTDNomination);
        return redirect(route('tf-bi-portal.a_s_t_d_nominations.index'));
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

            return redirect(route('tf-bi-portal.a_s_t_d_nominations.index'));
        }

        $aSTDNomination->delete();

        //Flash::success('A S T D Nomination deleted successfully.');
        ASTDNominationDeleted::dispatch($aSTDNomination);
        return redirect(route('tf-bi-portal.a_s_t_d_nominations.index'));
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
