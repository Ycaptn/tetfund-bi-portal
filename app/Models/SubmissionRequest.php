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
 * Class SubmissionRequest
 * @package App\Models
 * @version October 8, 2022, 5:09 pm UTC
 *
 * @property \App\Models\User $user
 * @property \App\Models\Beneficiary $beneficiary
 * @property string $organization_id
 * @property string $title
 * @property string $status
 * @property string $type
 * @property string $requesting_user_id
 * @property string $beneficiary_id
 * @property string $tf_iterum_portal_request_status
 */
class SubmissionRequest extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'tf_bi_submission_requests';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'title',
        'status',
        'type',
        'requesting_user_id',
        'beneficiary_id',
        'tf_iterum_portal_request_status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'status' => 'string',
        'type' => 'string',
        'display_ordinal' => 'integer',
        'intervention_year1' => 'integer',
        'intervention_year2' => 'integer',
        'intervention_year3' => 'integer',
        'intervention_year4' => 'integer',
        'tf_iterum_portal_request_status' => 'string',
        'tf_iterum_portal_response_meta_data' => 'string'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'requesting_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function beneficiary()
    {
        return $this->hasOne(\App\Models\Beneficiary::class, 'id', 'beneficiary_id');
    }

}
