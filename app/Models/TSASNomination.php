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


class TSASNomination extends Model {
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'tf_bi_tsas_nominations';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'email',
        'telephone',
        'beneficiary_institution_id',
        'tf_iterum_portal_institution_id',
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
        'degree_type',
        'program_title',
        'program_type',
        'is_science_program',
        'program_start_date',
        'program_end_date',
        'fee_amount',
        'tuition_amount',
        'upgrade_fee_amount',
        'stipend_amount',
        'passage_amount',
        'medical_amount',
        'warm_clothing_amount',
        'study_tours_amount',
        'education_materials_amount',
        'thesis_research_amount',
        'final_remarks',
        'total_requested_amount',
        'total_approved_amount',
        'institution_name',
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
        'institution_name' => 'string',
        'face_picture_attachment_id' => 'string',
        'bank_account_name' => 'string',
        'bank_account_number' => 'string',
        'bank_name' => 'string',
        'bank_sort_code' => 'string',
        'intl_passport_number' => 'string',
        'bank_verification_number' => 'string',
        'national_id_number' => 'string',
        'degree_type' => 'string',
        'program_title' => 'string',
        'program_type' => 'string',
        'is_science_program' => 'boolean',
        'program_duration_months' => 'integer',
        'fee_amount' => 'decimal:2',
        'tuition_amount' => 'decimal:2',
        'upgrade_fee_amount' => 'decimal:2',
        'stipend_amount' => 'decimal:2',
        'passage_amount' => 'decimal:2',
        'medical_amount' => 'decimal:2',
        'warm_clothing_amount' => 'decimal:2',
        'study_tours_amount' => 'decimal:2',
        'education_materials_amount' => 'decimal:2',
        'thesis_research_amount' => 'decimal:2',
        'final_remarks' => 'string',
        'total_requested_amount' => 'decimal:2',
        'total_approved_amount' => 'decimal:2',
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
