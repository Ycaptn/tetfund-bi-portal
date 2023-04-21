<?php

namespace App\Models;

use Hash;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SubmissionRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Hasob\Workflow\Traits\Workable;
use Hasob\FoundationCore\Traits\GuidId;
use Hasob\FoundationCore\Traits\Pageable;
use Hasob\FoundationCore\Traits\Disable;
use Hasob\FoundationCore\Traits\Ratable;
use Hasob\FoundationCore\Traits\Taggable;
use Hasob\FoundationCore\Traits\Ledgerable;
use Hasob\FoundationCore\Traits\Attachable;
use Hasob\FoundationCore\Traits\Artifactable;
use Hasob\FoundationCore\Traits\OrganizationalConstraint;

use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Beneficiary
 * @package App\Models
 * @version October 8, 2022, 5:09 pm UTC
 *
 * @property string $organization_id
 * @property string $email
 * @property string $full_name
 * @property string $short_name
 * @property string $official_email
 * @property string $official_website
 * @property string $official_phone
 * @property string $address_street
 * @property string $address_town
 * @property string $address_state
 * @property string $head_of_institution_title
 * @property string $geo_zone
 * @property string $owner_agency_type
 * @property string $tf_iterum_portal_beneficiary_status
 */
class Beneficiary extends Model
{
    use GuidId;
    //use OrganizationalConstraint;
    
    use SoftDeletes;
    use Attachable;
    use HasFactory;

    public $table = 'tf_bi_portal_beneficiaries';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'email',
        'full_name',
        'short_name',
        'official_email',
        'official_website',
        'official_phone',
        'address_street',
        'address_town',
        'address_state',
        'head_of_institution_title',
        'geo_zone',
        'owner_agency_type',
        'tf_iterum_portal_beneficiary_status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'display_ordinal' => 'integer',
        'email' => 'string',
        'full_name' => 'string',
        'short_name' => 'string',
        'official_email' => 'string',
        'official_website' => 'string',
        'type' => 'string',
        'official_phone' => 'string',
        'address_street' => 'string',
        'address_town' => 'string',
        'address_state' => 'string',
        'head_of_institution_title' => 'string',
        'geo_zone' => 'string',
        'owner_agency_type' => 'string',
        'tf_iterum_portal_beneficiary_status' => 'string',
        'tf_iterum_portal_response_meta_data' => 'string'
    ];

    public function hasRequest($tf_iterum_intervention_line_key_id, $intervention_year1=null, $intervention_year2=null, $intervention_year3=null, $intervention_year4=null, $id_to_skip=null, $intervention_name=null) {
        $requests = SubmissionRequest::where('beneficiary_id',$this->id)
                    ->when(SubmissionRequest::is_start_up_first_tranche_intervention($intervention_name)==true, function($qry) {
                            return $qry->where('is_first_tranche_request', true);                        
                        },
                        function($qry) {
                            return $qry->where('is_aip_request', true);
                        }
                    )
                    ->when($id_to_skip!=null, function($query) use ($id_to_skip) {
                        return $query->where('id', '!=', $id_to_skip);
                    })
                    ->where('tf_iterum_intervention_line_key_id',$tf_iterum_intervention_line_key_id)
                    ->get();

        if ($requests != null){
            foreach($requests as $item){
                $intervention_years = array(
                  $item->intervention_year1, $item->intervention_year2,
                  $item->intervention_year3, $item->intervention_year4
                );

                $request_years = [];
                if ($intervention_year1!=0){ $request_years []= $intervention_year1; }
                if ($intervention_year2!=0){ $request_years []= $intervention_year2; }
                if ($intervention_year3!=0){ $request_years []= $intervention_year3; }
                if ($intervention_year4!=0){ $request_years []= $intervention_year4; }
                $intersect = array_intersect($request_years, $intervention_years);

                Log::info("request items");
                Log::info(print_r($request_years, true));

                Log::info("intervention items");
                Log::info(print_r($intervention_years, true));

                Log::info("intersect");
                Log::info(print_r($intersect, true));

                if ( $intersect!=null && is_array($intersect) && count($intersect)>0 ){
                    return true;
                }
            }
        }
        return false;
    }

}
