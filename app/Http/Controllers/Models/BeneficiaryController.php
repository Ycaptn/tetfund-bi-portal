<?php

namespace App\Http\Controllers\Models;

use App\Models\Beneficiary;

use App\Events\BeneficiaryCreated;
use App\Events\BeneficiaryUpdated;
use App\Events\BeneficiaryDeleted;

use App\Http\Requests\CreateBeneficiaryRequest;
use App\Http\Requests\UpdateBeneficiaryRequest;

use App\DataTables\BeneficiaryDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class BeneficiaryController extends BaseController
{
    /**
     * Display a listing of the Beneficiary.
     *
     * @param BeneficiaryDataTable $beneficiaryDataTable
     * @return Response
     */
    public function index(Organization $org, BeneficiaryDataTable $beneficiaryDataTable)
    {
        $current_user = Auth()->user();

        $cdv_beneficiaries = new \Hasob\FoundationCore\View\Components\CardDataView(Beneficiary::class, "pages.beneficiaries.card_view_item");
        $cdv_beneficiaries->addDataGroup('All','deleted_at',null)
                        //->setDataQuery(['organization_id'=>$org->id])
                        ->addDataGroup('University','type','university')
                        ->addDataGroup('Polytechnic','type','polytechnic')
                        ->addDataGroup('College','type','college')
                        ->setSearchFields(['full_name','short_name','official_email'])
                        ->addDataOrder('full_name','ASC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(10)
                        ->setSearchPlaceholder('Search Beneficiaries');

        if (request()->expectsJson()){
            return $cdv_beneficiaries->render();
        }

        return view('pages.beneficiaries.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_beneficiaries', $cdv_beneficiaries);
    }

    /**
     * Show the form for creating a new Beneficiary.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('xyz::pages.beneficiaries.create');
    }

    /**
     * Store a newly created Beneficiary in storage.
     *
     * @param CreateBeneficiaryRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateBeneficiaryRequest $request)
    {
        $input = $request->all();

        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::create($input);

        //Flash::success('Beneficiary saved successfully.');

        BeneficiaryCreated::dispatch($beneficiary);
        return redirect(route('xyz.beneficiaries.index'));
    }

    /**
     * Display the specified Beneficiary.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            //Flash::error('Beneficiary not found');

            return redirect(route('tf-bi-portal.beneficiaries.index'));
        }

        return view('tf-bi-portal::pages.beneficiaries.show')->with('beneficiary', $beneficiary);
    }

    /**
     * Show the form for editing the specified Beneficiary.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            //Flash::error('Beneficiary not found');

            return redirect(route('xyz.beneficiaries.index'));
        }

        return view('xyz::pages.beneficiaries.edit')->with('beneficiary', $beneficiary);
    }

    /**
     * Update the specified Beneficiary in storage.
     *
     * @param  int              $id
     * @param UpdateBeneficiaryRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateBeneficiaryRequest $request)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            //Flash::error('Beneficiary not found');

            return redirect(route('xyz.beneficiaries.index'));
        }

        $beneficiary->fill($request->all());
        $beneficiary->save();

        //Flash::success('Beneficiary updated successfully.');
        
        BeneficiaryUpdated::dispatch($beneficiary);
        return redirect(route('xyz.beneficiaries.index'));
    }

    /**
     * Remove the specified Beneficiary from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            //Flash::error('Beneficiary not found');

            return redirect(route('xyz.beneficiaries.index'));
        }

        $beneficiary->delete();

        //Flash::success('Beneficiary deleted successfully.');
        BeneficiaryDeleted::dispatch($beneficiary);
        return redirect(route('xyz.beneficiaries.index'));
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
