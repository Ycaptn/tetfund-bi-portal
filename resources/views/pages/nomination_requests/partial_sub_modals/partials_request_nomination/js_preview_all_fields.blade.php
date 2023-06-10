if (nomination_type_lower_case == 'tp') {

	$('#full_name_data_tp').text(response.data.nominee.first_name +' '+ middle_name +' '+ response.data.nominee.last_name);
	$('#email_data_tp').text(response.data.nominee.email);
	$('#telephone_data_tp').text(response.data.nominee.telephone);
	$('#beneficiary_institution_id_select_data_tp').text(response.data.nominee_beneficiary.full_name +' ('+ response.data.nominee_beneficiary.short_name +')');
	$('#gender_data_tp').text(response.data.nominee.gender);
	$('#institution_name_data_tp').text(response.data.nominee.institution_name);
	$('#institution_state_data_tp').text(response.data.nominee.intitution_state);
	$('#institution_address_data_tp').text(response.data.nominee.institution_address);
	$('#bank_account_name_data_tp').text(response.data.nominee.bank_account_name);
	$('#bank_account_number_data_tp').text(response.data.nominee.bank_account_number);
	$('#bank_name_data_tp').text(response.data.nominee.bank_name);
	$('#bank_sort_code_data_tp').text(response.data.nominee.bank_sort_code);
	$('#intl_passport_number_data_tp').text(response.data.nominee.intl_passport_number);
	$('#bank_verification_number_data_tp').text(response.data.nominee.bank_verification_number);
	$('#national_id_number_data_tp').text(response.data.nominee.national_id_number);
	$('#program_start_date_data_tp').text(new Date(response.data.nominee.program_start_date).toDateString());
	$('#program_end_date_data_tp').text(new Date(response.data.nominee.program_end_date).toDateString());

} else if (nomination_type_lower_case == 'ca') {

	$('#full_name_data_ca').text(response.data.nominee.first_name +' '+ middle_name +' '+ response.data.nominee.last_name);
	$('#email_data_ca').text(response.data.nominee.email);
	$('#telephone_data_ca').text(response.data.nominee.telephone);
	$('#beneficiary_institution_id_select_data_ca').text(response.data.nominee_beneficiary.full_name +' ('+ response.data.nominee_beneficiary.short_name +')');
	$('#gender_data_ca').text(response.data.nominee.gender);
	$('#bank_account_name_data_ca').text(response.data.nominee.bank_account_name);
	$('#bank_account_number_data_ca').text(response.data.nominee.bank_account_number);
	$('#bank_name_data_ca').text(response.data.nominee.bank_name);
	$('#bank_sort_code_data_ca').text(response.data.nominee.bank_sort_code);
	$('#intl_passport_number_data_ca').text(response.data.nominee.intl_passport_number);
	$('#bank_verification_number_data_ca').text(response.data.nominee.bank_verification_number);
	$('#national_id_number_data_ca').text(response.data.nominee.national_id_number);
	$('#organizer_name_data_ca').text(response.data.nominee.organizer_name);
	$('#conference_theme_data_ca').text(response.data.nominee.conference_theme);
	$('#accepted_paper_title_data_ca').text(response.data.nominee.accepted_paper_title);
	$('#attendee_department_name_data_ca').text(response.data.nominee.attendee_department_name);
	$('#attendee_grade_level_data_ca').text(response.data.nominee.attendee_grade_level);
	$('#has_paper_presentation_data_ca').text((response.data.nominee.has_paper_presentation == true) ? 'Yes' : 'No');
	$('#is_academic_staff_data_ca').text((response.data.nominee.is_academic_staff == true) ? 'Yes' : 'No');
	$('#conference_start_date_data_ca').text(response.data.nominee.conference_start_date);
	$('#conference_end_date_data_ca').text(response.data.nominee.conference_end_date);

} else if (nomination_type_lower_case == 'tsas') {

	$('#full_name_data_tsas').text(response.data.nominee.first_name +' '+ middle_name +' '+ response.data.nominee.last_name);
	$('#email_data_tsas').text(response.data.nominee.email);
	$('#telephone_data_tsas').text(response.data.nominee.telephone);
	$('#beneficiary_institution_id_select_data_tsas').text(response.data.nominee_beneficiary.full_name +' ('+ response.data.nominee_beneficiary.short_name +')');
	$('#gender_data_tsas').text(response.data.nominee.gender);
	$('#institution_name_data_tsas').text(response.data.nominee.institution_name);
	$('#bank_account_name_data_tsas').text(response.data.nominee.bank_account_name);
	$('#bank_account_number_data_tsas').text(response.data.nominee.bank_account_number);
	$('#bank_name_data_tsas').text(response.data.nominee.bank_name);
	$('#bank_sort_code_data_tsas').text(response.data.nominee.bank_sort_code);
	$('#intl_passport_number_data_tsas').text(response.data.nominee.intl_passport_number);
	$('#bank_verification_number_data_tsas').text(response.data.nominee.bank_verification_number);
	$('#national_id_number_data_tsas').text(response.data.nominee.national_id_number);
	$('#degree_type_data_tsas').text(response.data.nominee.degree_type);
	$('#program_title_data_tsas').text(response.data.nominee.program_title);
	$('#is_science_program_data_tsas').text((response.data.nominee.is_science_program == true) ? 'Yes' : 'No');
	$('#program_start_date_data_tsas').text(new Date(response.data.nominee.program_start_date).toDateString());
	$('#program_end_date_data_tsas').text(new Date(response.data.nominee.program_end_date).toDateString());

}
