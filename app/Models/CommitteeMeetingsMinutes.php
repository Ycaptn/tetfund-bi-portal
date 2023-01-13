<?php

namespace App\Models;

use Hash;
use Response;
use Carbon\Carbon;
use Eloquent as Model;
use App\Models\Beneficiary;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Traits\GuidId;
use Hasob\FoundationCore\Traits\Pageable;
use Hasob\FoundationCore\Traits\Disable;
use Hasob\FoundationCore\Traits\Ratable;
use Hasob\FoundationCore\Traits\Taggable;
use Hasob\FoundationCore\Traits\Ledgerable;
use Hasob\FoundationCore\Traits\Artifactable;
use Hasob\FoundationCore\Traits\OrganizationalConstraint;


class CommitteeMeetingsMinutes extends Model
{
    use GuidId;
    use HasFactory;
    use SoftDeletes;
    use OrganizationalConstraint;

    public $table = 'tf_bi_committee_meetings_minutes';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'organization_id',
        'user_id',
        'beneficiary_id',
        'nomination_type',
        'description',
        'usage_status',
        'additional_param',
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
}
