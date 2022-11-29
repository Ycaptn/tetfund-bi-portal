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
use Hasob\FoundationCore\Models\User;
use App\Models\Beneficiary;
use App\Models\SubmissionRequest;
use App\Models\NominationCommitteeVotes;
use App\Models\ASTDNomination;
use App\Models\ASTDNomination as TPNomination;
use Hasob\FoundationCore\Models\Attachable as EloquentAttachable;

use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class NominationRequest extends Model
{
    use HasFactory;
    use GuidId;
    use OrganizationalConstraint;
    use Attachable;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'tf_bi_nomination_requests';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'bi_submission_request_id',
        'organization_id',
        'beneficiary_id',
        'type',
        'request_date',
        'status',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function submission_request()
    {
        return $this->belongsTo(SubmissionRequest::class, 'bi_submission_request_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function nomination_committee_votes()
    {
        return $this->hasMany(NominationCommitteeVotes::class, 'nomination_request_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function astd_submission()
    {
        return $this->hasOne(ASTDNomination::class, 'nomination_request_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function tp_submission()
    {
        return $this->hasOne(TPNomination::class, 'nomination_request_id', 'id');
    }

    // get specific attachement for a nomination request
    public static function get_specific_attachment($nomination_request_id, $item_label) {
        $nomination_request = self::find($nomination_request_id);
        if ($nomination_request != null) {
            $attachements = $nomination_request->get_attachments();
            if ($attachements != null) {
                foreach($attachements as $attachement){
                    if ($attachement->label == $item_label || str_contains($attachement->label, $item_label)) {
                        return $attachement;
                        break;
                    }
                }    
            }
        }
        return null;
    }

    // get all attachements a nomination request
    public static function get_all_attachments($nomination_request_id) {
        $nomination_request = self::find($nomination_request_id);
        if ($nomination_request != null) {
            $attachements = $nomination_request->get_attachments();
            if ($attachements != null) {
                return $attachements;
            }
        }
        return null;
    }
}
