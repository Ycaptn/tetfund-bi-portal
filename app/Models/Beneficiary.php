<?php

namespace App\Models;

use Hash;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

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


    

}
