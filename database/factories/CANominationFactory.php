<?php

namespace Database\Factories;

use App\Models\CANomination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Hasob\FoundationCore\Models\Organization;

class CANominationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CANomination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organization_id' => $this->faker->word,
        'display_ordinal' => $this->faker->randomDigitNotNull,
        'email' => $this->faker->word,
        'telephone' => $this->faker->word,
        'beneficiary_institution_id' => $this->faker->word,
        'conference_id' => $this->faker->word,
        'country_id' => $this->faker->word,
        'user_id' => $this->faker->word,
        'gender' => $this->faker->word,
        'name_title' => $this->faker->word,
        'first_name' => $this->faker->word,
        'middle_name' => $this->faker->word,
        'last_name' => $this->faker->word,
        'name_suffix' => $this->faker->word,
        'face_picture_attachment_id' => $this->faker->word,
        'bank_account_name' => $this->faker->word,
        'bank_account_number' => $this->faker->word,
        'bank_name' => $this->faker->word,
        'bank_sort_code' => $this->faker->word,
        'intl_passport_number' => $this->faker->word,
        'bank_verification_number' => $this->faker->word,
        'national_id_number' => $this->faker->word,
        'organizer_name' => $this->faker->word,
        'conference_theme' => $this->faker->word,
        'accepted_paper_title' => $this->faker->word,
        'attendee_department_name' => $this->faker->word,
        'attendee_grade_level' => $this->faker->word,
        'has_paper_presentation' => $this->faker->word,
        'is_academic_staff' => $this->faker->word,
        'conference_start_date' => $this->faker->date('Y-m-d H:i:s'),
        'conference_end_date' => $this->faker->date('Y-m-d H:i:s'),
        'conference_duration_days' => $this->faker->randomDigitNotNull,
        'conference_fee_amount' => $this->faker->word,
        'conference_fee_amount_local' => $this->faker->word,
        'dta_amount' => $this->faker->word,
        'local_runs_amount' => $this->faker->word,
        'passage_amount' => $this->faker->word,
        'final_remarks' => $this->faker->word,
        'total_requested_amount' => $this->faker->word,
        'total_approved_amount' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
            'organization_id' => Organization::first()
        ];
    }
}
