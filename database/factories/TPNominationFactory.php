<?php

namespace Database\Factories;

use App\Models\ASTDNomination as TPNomination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Hasob\FoundationCore\Models\Organization;

class TPNominationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TPNomination::class;

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
        'institution_id' => $this->faker->word,
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
        'degree_type' => $this->faker->word,
        'program_title' => $this->faker->word,
        'program_type' => $this->faker->word,
        'is_science_program' => $this->faker->word,
        'program_start_date' => $this->faker->date('Y-m-d H:i:s'),
        'program_end_date' => $this->faker->date('Y-m-d H:i:s'),
        'program_duration_months' => $this->faker->randomDigitNotNull,
        'fee_amount' => $this->faker->word,
        'tuition_amount' => $this->faker->word,
        'upgrade_fee_amount' => $this->faker->word,
        'stipend_amount' => $this->faker->word,
        'passage_amount' => $this->faker->word,
        'medical_amount' => $this->faker->word,
        'warm_clothing_amount' => $this->faker->word,
        'study_tours_amount' => $this->faker->word,
        'education_materials_amount' => $this->faker->word,
        'thesis_research_amount' => $this->faker->word,
        'final_remarks' => $this->faker->word,
        'total_requested_amount' => $this->faker->word,
        'total_approved_amount' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
            'organization_id' => Organization::first()
        ];
    }
}
