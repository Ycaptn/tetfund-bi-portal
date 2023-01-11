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
use Hasob\FoundationCore\Models\Attachable as EloquentAttachable;
use Hasob\FoundationCore\Traits\Artifactable;
use Hasob\FoundationCore\Traits\OrganizationalConstraint;
use Hasob\FoundationCore\Models\User;
use App\Models\Beneficiary;
use App\Models\NominationRequest;

use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CANomination extends Model {
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'tf_bi_ca_nominations';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'email',
        'telephone',
        'beneficiary_institution_id',
        'tf_iterum_portal_conference_id',
        'tf_iterum_portal_country_id',
        'nomination_request_id',
        'bi_submission_request_id',
        'user_id',
        'gender',
        'name_title',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'face_picture_attachment_id',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'bank_sort_code',
        'intl_passport_number',
        'bank_verification_number',
        'national_id_number',
        'organizer_name',
        'conference_theme',
        'accepted_paper_title',
        'attendee_department_name',
        'attendee_grade_level',
        'has_paper_presentation',
        'is_academic_staff',
        'conference_start_date',
        'conference_end_date',
        'conference_fee_amount',
        'conference_fee_amount_local',
        'dta_amount',
        'local_runs_amount',
        'passage_amount',
        'paper_presentation_fee',
        'final_remarks',
        'total_requested_amount',
        'total_approved_amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'display_ordinal' => 'integer',
        'email' => 'string',
        'telephone' => 'string',
        'gender' => 'string',
        'name_title' => 'string',
        'first_name' => 'string',
        'middle_name' => 'string',
        'last_name' => 'string',
        'name_suffix' => 'string',
        'face_picture_attachment_id' => 'string',
        'bank_account_name' => 'string',
        'bank_account_number' => 'string',
        'bank_name' => 'string',
        'bank_sort_code' => 'string',
        'intl_passport_number' => 'string',
        'bank_verification_number' => 'string',
        'national_id_number' => 'string',
        'organizer_name' => 'string',
        'conference_theme' => 'string',
        'accepted_paper_title' => 'string',
        'attendee_department_name' => 'string',
        'attendee_grade_level' => 'string',
        'has_paper_presentation' => 'boolean',
        'is_academic_staff' => 'boolean',
        'conference_duration_days' => 'integer',
        'conference_fee_amount' => 'decimal:2',
        'conference_fee_amount_local' => 'decimal:2',
        'dta_amount' => 'decimal:2',
        'local_runs_amount' => 'decimal:2',
        'passage_amount' => 'decimal:2',
        'final_remarks' => 'string',
        'total_requested_amount' => 'decimal:2',
        'total_approved_amount' => 'decimal:2'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_institution_id', 'id');
    }

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
    public function nomination_request()
    {
        return $this->belongsTo(NominationRequest::class, 'nomination_request_id', 'id');
    }

    public function attachables()
    {
        return $this->hasMany(EloquentAttachable::class, 'attachable_id', 'id');
    }

}
