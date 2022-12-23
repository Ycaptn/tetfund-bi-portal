<?php

namespace App\Http\Controllers\Models;

use App\Models\NominationSetting;
use App\Http\Requests\StoreNominationSettingRequest;
use App\Http\Requests\UpdateNominationSettingRequest;
use Hasob\FoundationCore\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;
use Illuminate\Support\Facades\DB;
use Hasob\FoundationCore\Traits\ApiResponder;

class NominationSettingController extends BaseController
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        $astd_settings = [];
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $user->id)->first();
        $nomination_settings = NominationSetting::where('beneficiary_institution_id', optional($beneficiary_member)->beneficiary_id)->get();
        $beneficiary_roles = DB::table('roles')
            ->where('name', 'like', 'BI-%')
            ->get();
        foreach ($nomination_settings as $key => $nomination_setting) {
            $astd_settings[$nomination_setting->key] = $nomination_setting;
        }

        return view('pages.nomination_settings.index')->with('astd_settings', $astd_settings)
            ->with('beneficiary_roles', $beneficiary_roles);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNominationSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNominationSettingRequest $request)
    {
        $nomination_setting_id = $request->id;
        $user = Auth::user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $user->id)->first();
        $nomination_setting = NominationSetting::find($nomination_setting_id);
        if ($nomination_setting == null) {
            $nomination_setting = new NominationSetting();
        }

        $nomination_setting->key = $request->key;
        $nomination_setting->value = $request->value;
        $nomination_setting->updated_by  = $user->id;
        $nomination_setting->beneficiary_institution_id = optional($beneficiary_member)->beneficiary_id;
        $nomination_setting->organization_id  = $user->organization_id;
        $nomination_setting->save();

        return $this->sendSuccess('Setting Updated Successfully');

    }

}
