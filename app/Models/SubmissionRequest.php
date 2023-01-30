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
use Hasob\FoundationCore\Models\Attachable as EloquentAttachable;
use Hasob\FoundationCore\Models\User;
use App\Models\Beneficiary;

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
    use Attachable;

    use HasFactory;

    public $table = 'tf_bi_submission_requests';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'type',
        'title',
        'amount_requested',
        'intervention_year1',
        'intervention_year2',
        'intervention_year3',
        'intervention_year4',
        'status',
        'requesting_user_id',
        'beneficiary_id',
        'bi_submission_request_id',
        'tf_iterum_portal_request_status',
        'tf_iterum_intervention_line_key_id',
        'parent_id',
        'is_aip_request',
        'is_first_tranche_request',
        'is_second_tranche_request',
        'is_third_tranche_request',
        'is_first_tranche_request',
        'is_monitoring_request',
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
        'bi_submission_request_id' => 'string',
        'tf_iterum_portal_request_status' => 'string',
        'tf_iterum_portal_response_meta_data' => 'string'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'requesting_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'id');
    }

    public static function get_specific_attachment($submission_request_id, $item_label) {
        $submission_request = self::find($submission_request_id);
        if ($submission_request != null) {
            $attachements = $submission_request->get_attachments();
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

    public static function get_all_attachments($submission_request_id) {
        $submission_request = self::find($submission_request_id);
        if ($submission_request != null) {
            $attachements = $submission_request->get_attachments();
            if ($attachements != null) {
                return $attachements;
            }
        }
        return null;
    }

    public static function get_all_attachments_count_aside_additional($submission_request_id, $key_to_exclude) {
        $submission_request = SubmissionRequest::find($submission_request_id);
        $counter = 0;
        if ($submission_request != null) {
            $attachements = $submission_request->get_attachments();
            if ($attachements != null) {
                foreach($attachements as $attach) {
                    if (str_contains($attach->label, $key_to_exclude) == false) {
                        $counter += 1;
                    }
                }
            }
        }
        return $counter;
    }

}
