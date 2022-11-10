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

use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ASTDNomination
 * @package TETFund\ASTD\Models
 * @version July 27, 2022, 8:40 pm UTC
 *
 * @property \TETFund\ASTD\Models\Beneficiary $beneficiary
 * @property \TETFund\ASTD\Models\Institution $institution
 * @property \TETFund\ASTD\Models\Country $country
 * @property \TETFund\ASTD\Models\User $user
 * @property string $organization_id
 * @property string $email
 * @property string $telephone
 * @property string $beneficiary_institution_id
 * @property string $tf_iterum_portal_institution_id
 * @property string $tf_iterum_portal_country_id
 * @property string $nomination_request_id
 * @property string $user_id
 * @property string $gender
 * @property string $name_title
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $name_suffix
 * @property string $face_picture_attachment_id
 * @property string $bank_account_name
 * @property string $bank_account_number
 * @property string $bank_name
 * @property string $bank_sort_code
 * @property string $intl_passport_number
 * @property string $bank_verification_number
 * @property string $national_id_number
 * @property string $degree_type
 * @property string $program_title
 * @property string $program_type
 * @property number $fee_amount
 * @property number $tuition_amount
 * @property number $upgrade_fee_amount
 * @property number $stipend_amount
 * @property number $passage_amount
 * @property number $medical_amount
 * @property number $warm_clothing_amount
 * @property number $study_tours_amount
 * @property number $education_materials_amount
 * @property number $thesis_research_amount
 * @property string $final_remarks
 * @property number $total_requested_amount
 * @property number $total_approved_amount
 */
class ASTDNomination extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'tf_bi_astd_nominations';
    

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
        'type_of_nomination'
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
        'type_of_nomination' => 'string'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'id', 'beneficiary_institution_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
