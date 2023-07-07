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
        'proposed_request_date',
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
        'is_final_tranche_request',
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
    public function user() {
        return $this->belongsTo(User::class, 'requesting_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function beneficiary() {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'id');
    }

    public function getInterventionYears() {
        $years = [];
        if ($this->intervention_year1 != null) {
            $years[] = $this->intervention_year1;
        }
        if ($this->intervention_year2 != null) {
            $years[] = $this->intervention_year2;
        }
        if ($this->intervention_year3 != null) {
            $years[] = $this->intervention_year3;
        }
        if ($this->intervention_year4 != null) {
            $years[] = $this->intervention_year4;
        }

        return $years;
    }

    public function attachables() {
        return $this->hasMany(EloquentAttachable::class, 'attachable_id', 'id');
    }

    public static function get_specific_attachment($submission_request_id, $item_label) {
        $submission_request = self::find($submission_request_id);
        if ($submission_request != null) {
            $attachments = $submission_request->get_attachments();
            if ($attachments != null) {
                foreach($attachments as $attachment){
                    if ($attachment->label == $item_label || str_contains($attachment->label, $item_label) || str_contains($item_label, $attachment->label)) {
                        return $attachment;
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
            $attachments = $submission_request->get_attachments();
            if ($attachments != null) {
                return $attachments;
            }
        }
        return null;
    }

    public static function get_all_attachments_count_aside_additional($submission_request_id, $key_to_exclude) {
        $submission_request = SubmissionRequest::find($submission_request_id);
        $counter = 0;
        if ($submission_request != null) {
            $attachments = $submission_request->get_attachments();
            if ($attachments != null) {
                foreach($attachments as $attach) {
                    if (str_contains($attach->label, $key_to_exclude) == false) {
                        $counter += 1;
                    }
                }
            }
        }
        return $counter;
    }

    // all interventions that begins with FIRST TRANCHE and not AIP
    public static function is_start_up_first_tranche_intervention($intervention_name) {
        $start_up_first_tranche_interventions = [
            'equipment fabrication',
            'advocacy and publicity',
            'academic research journal',
            'academic manuscript into books',
        ];

        return in_array(strtolower($intervention_name), $start_up_first_tranche_interventions);
    }

    // all interventions that should be denied submission processing
    public function interventions_denied_submission() {
        return [];
    }

    // all ASTD interventions
    public static function is_astd_intervention($intervention_name) {
        $astd_interventions = [
            'teaching practice',
            'conference attendance',
            'tetfund scholarship',
            'tetfund scholarship for academic staff'
        ];

        return in_array(strtolower($intervention_name), $astd_interventions);
    }

    // all first tranche interventions percentage
    public function first_tranche_intervention_percentage ($intervention_name) {
        $first_tranche_interventions = [
            'ict support' => '85%',
            'zonal intervention' => '85%',
            'library development' => '85%',
            'equipment fabrication' => '50%',
            'advocacy and publicity' => '85%',
            'entrepreneurship centre' => '85%',
            'academic research journal' => '85%',
            'academic manuscript into books' => '85%',
            'academic manuscript development' => '85%',
            'physical infrastructure and program upgrade' => '50%',
        ];
        return $first_tranche_interventions[strtolower($intervention_name)] ?? null;
    }

    // all second tranche interventions percentage
    public function second_tranche_intervention_percentage ($intervention_name) {
        $second_tranche_interventions = [
            'equipment fabrication' => '35%',
            'physical infrastructure and program upgrade' => '35%',
        ];

        return $second_tranche_interventions[strtolower($intervention_name)] ?? null;
    }

    // all third tranche interventions percentage
    public function third_tranche_intervention_percentage ($intervention_name) {
        $third_tranche_interventions = [
            // 'intervention name' => 'Percentage in numeric',
        ];

        return $third_tranche_interventions[strtolower($intervention_name)] ?? null;
    }

    // all final tranche interventions percentage
     public function final_tranche_intervention_percentage ($intervention_name) {
        $final_tranche_interventions = [
            'ict support' => '15%',
            'zonal intervention' => '15%',
            'library development' => '15%',
            'equipment fabrication' => '15%',
            'advocacy and publicity' => '15%',
            'entrepreneurship centre' => '15%',
            'academic research journal' => '15%',
            'academic manuscript into books' => '15%',
            'academic manuscript development' => '15%',
            'physical infrastructure and program upgrade' => '15%',
        ];

        return $final_tranche_interventions[strtolower($intervention_name)] ?? null;
    }

    // all monitoring request interventions
     public function monitoring_evaluation_interventions ($intervention_name) {
        $monitoring_evaluation_interventions = [
            'ict support',
            'zonal intervention',
            'library development',
            'equipment fabrication',
            'advocacy and publicity',
            'academic research journal',
            'academic manuscript into books',
            'physical infrastructure and program upgrade',
        ];

        return in_array(strtolower($intervention_name), $monitoring_evaluation_interventions);
    }

    // submission Request AIP Payment
    public function getParentAIPSubmissionRequest($intervention_name=null) {
        if ($this->is_aip_request) {
            return $this;
        }

        if ($this->is_first_tranche_request && $intervention_name!=null && $this->is_start_up_first_tranche_intervention($intervention_name)) {
            return $this;
        }

        if ($this->parent_id != null) {
            return $this->find($this->parent_id);
        }
        return null;
    }

    // get server side AIP request
    public function getServerSideAIPRequest() {
        if($this->tf_iterum_portal_response_meta_data!=null && str_contains(strtolower($this->title), 'ongoing submission for')) {
            
            return json_decode($this->tf_iterum_portal_response_meta_data)->aip_parent_request ?? null;
        }
        return null;
    }

    // submission Request First Payment
    public function getFirstTrancheSubmissionRequest() {
        if ($this->is_first_tranche_request) {
            return $this;
        }

        $aip_request = $this->getParentAIPSubmissionRequest();
        if ($aip_request != null || $this->id) {
            $aip_request_id = isset($aip_request->id) ? $aip_request->id : null;
            return $this->where('parent_id', ($aip_request_id ?? $this->id))
                ->where('is_first_tranche_request', true)
                ->first();
        }
        return null;
    }

    // submission Request Second tranche Payment
    public function getSecondTrancheSubmissionRequest() {
        if ($this->is_second_tranche_request) {
            return $this;
        }

        $aip_request = $this->getParentAIPSubmissionRequest();
        if ($aip_request != null || $this->id) {
            $aip_request_id = isset($aip_request->id) ? $aip_request->id : null;
            return $this->where('parent_id', ($aip_request_id ?? $this->id))
                ->where('is_second_tranche_request', true)
                ->first();
        }
        return null;
    }

    // submission Request Third tranche Payment
    public function getThirdTrancheSubmissionRequest() {
        if ($this->is_third_tranche_request) {
            return $this;
        }

        $aip_request = $this->getParentAIPSubmissionRequest();
        if ($aip_request != null || $this->id) {
            $aip_request_id = isset($aip_request->id) ? $aip_request->id : null;
            return $this->where('parent_id', ($aip_request_id ?? $this->id))
                ->where('is_third_tranche_request', true)
                ->first();
        }
        return null;
    }

    // submission Request Final tranche Payment
    public function getFinalTrancheSubmissionRequest() {
        if ($this->is_final_tranche_request) {
            return $this;
        }

        $aip_request = $this->getParentAIPSubmissionRequest();
        if ($aip_request != null || $this->id) {
            $aip_request_id = isset($aip_request->id) ? $aip_request->id : null;
            return $this->where('parent_id', ($aip_request_id ?? $this->id))
                ->where('is_final_tranche_request', true)
                ->first();
        }
        return null;
    }

    // submission Request Monitoring
    public function getMonitoringSubmissionRequests() {
        $parent_request = $this->find($this->parent_id) ?? $this;
        if ($parent_request != null) {
            return $this->where('id', '!=', $this->id)
                ->when($this->is_monitoring_request==true, function($query) use ($parent_request) {
                    return $query->where('parent_id', $parent_request->id);
                })
                ->when($this->is_monitoring_request==false, function($query) {
                    return $query->where('parent_id', $this->id);
                })
                ->where('is_monitoring_request', true)
                ->orderBy('created_at', 'DESC')
                ->get();
        }
        return null;
    }

    public function getAllRelatedSubmissionRequest($is_monitoring_inclusive=false) {
        $get_all_related_requests = array();

        $aip_request = $this->getParentAIPSubmissionRequest();
        if (!empty($aip_request) && $aip_request->id != $this->id) {
            array_push($get_all_related_requests, $aip_request);
        }

        $first_tranche_request = $this->getFirstTrancheSubmissionRequest();
        if (!empty($first_tranche_request) && $first_tranche_request->id != $this->id) {
            array_push($get_all_related_requests, $first_tranche_request);
        }

        $second_tranche_request = $this->getSecondTrancheSubmissionRequest();
        if (!empty($second_tranche_request) && $second_tranche_request->id != $this->id) {
            array_push($get_all_related_requests, $second_tranche_request);
        }

        $third_tranche_request = $this->getThirdTrancheSubmissionRequest();
        if (!empty($third_tranche_request) && $third_tranche_request->id != $this->id) {
            array_push($get_all_related_requests, $third_tranche_request);
        }

        $final_tranche_request = $this->getFinalTrancheSubmissionRequest();
        if (!empty($final_tranche_request) && $final_tranche_request->id != $this->id) {
            array_push($get_all_related_requests, $final_tranche_request);
        }

        if ($is_monitoring_inclusive == true) {
            $monitoring_requests = $this->getMonitoringSubmissionRequests();
            if (!empty($monitoring_requests)) {
                $get_all_related_requests = array_merge($get_all_related_requests, $monitoring_requests->toArray());
            }
            
        }

        return $get_all_related_requests;
    }

}
