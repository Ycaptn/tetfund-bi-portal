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
use App\Models\TPNomination;
use App\Models\CANomination;
use App\Models\TSASNomination;
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
        'nomination_request_id',
        'type',
        'request_date',
        'status',
        'is_desk_officer_check',
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
    public function nomination_committee_vote()
    {
        return $this->hasOne(NominationCommitteeVotes::class, 'nomination_request_id', 'id')->where('user_id', auth()->user()->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function tp_submission()
    {
        return $this->hasOne(TPNomination::class, 'nomination_request_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function ca_submission()
    {
        return $this->hasOne(CANomination::class, 'nomination_request_id', 'id');
    }

   /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function tsas_submission()
    {
        return $this->hasOne(TSASNomination::class, 'nomination_request_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function attachables()
    {
        return $this->hasMany(EloquentAttachable::class, 'attachable_id', 'id');
    }

    
    // get all attachments a nomination request
    public static function get_all_attachments($nomination_request_id) {
        $nomination_request = self::find($nomination_request_id);
        if ($nomination_request != null) {
            $attachments = $nomination_request->get_attachments();
            if ($attachments != null) {
                return $attachments;
            }
        }
        return null;
    }


    // get specific attachment for a nomination request
    public static function get_specific_attachment($nomination_request_id, $item_label) {
        $attachments = self::get_all_attachments($nomination_request_id);
        if ($attachments != null) {
            foreach($attachments as $attachment) {
                if ($attachment->label == $item_label || str_contains($attachment->label, $item_label)) {
                    return $attachment;
                    break;
                }
            }
        }

        return null;
    }


    // get all world institutions list
    public static function worldAcademicInstitutions() {
        $filePath = public_path('dist/world_institutions/all_world_institutions_main.txt');
        
        try {
            $array_institutions = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($array_institutions === false) {
                throw new \Exception("Error reading the file.");
            }            
        } catch (\Exception $e) {
            return "An error occurred: " . $e->getMessage();
        }

        return $array_institutions;
    }
}
