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

use Hasob\FoundationCore\Traits\GuidId;
use Hasob\FoundationCore\Traits\Pageable;
use Hasob\FoundationCore\Traits\Disable;
use Hasob\FoundationCore\Traits\Ratable;
use Hasob\FoundationCore\Traits\Taggable;
use Hasob\FoundationCore\Traits\Ledgerable;
use Hasob\FoundationCore\Traits\Artifactable;
use Hasob\FoundationCore\Traits\OrganizationalConstraint;
use Hasob\FoundationCore\Models\User;
use App\Models\Beneficiary;
use App\Models\NominationRequest;

use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class NominationCommitteeVotes extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    public $table = 'tf_bi_nomination_committee_votes';
    

    protected $dates = ['deleted_at'];

    public $fillable = [
        'organization_id',
        'user_id',
        'beneficiary_id',
        'nomination_request_id',
        'additional_param',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function nomination_request()
    {
        return $this->hasOne(\App\Models\NominationRequest::class, 'nomination_request_id', 'id');
    }

}
