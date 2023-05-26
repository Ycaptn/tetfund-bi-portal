if ($('#email_ca').length && $('#email_ca').val().trim().length > 0){	formData.append('email',$('#email_ca').val());	}

if ($('#telephone_ca').length && $('#telephone_ca').val().trim().length > 0){	formData.append('telephone',$('#telephone_ca').val());	}

if ($('#beneficiary_institution_id_select_ca').length && $('#beneficiary_institution_id_select_ca').val().trim().length > 0){	
	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_ca').val());	
}

if ($('#is_conference_workshop_ca').length && $('#is_conference_workshop_ca').val().trim().length > 0){   formData.append('is_conference_workshop',$('#is_conference_workshop_ca').val());   }

if ($('#country_id_select_ca').length && $('#country_id_select_ca').val().trim().length > 0){   formData.append('tf_iterum_portal_country_id',$('#country_id_select_ca').val());   }

if ($('#conference_title_ca').length && $('#conference_title_ca').val().trim().length > 0){  
	formData.append('conference_title',$('#conference_title_ca').val());  
}

if ($('#conference_state_select_ca').length && $('#conference_state_select_ca').val().trim().length > 0){  
	formData.append('conference_state',$('#conference_state_select_ca').val());  
}

if ($('#gender_ca').length && $('#gender_ca').val().trim().length > 0){   formData.append('gender',$('#gender_ca').val());   }

if ($('#name_title_ca').length && $('#name_title_ca').val().trim().length > 0){	formData.append('name_title',$('#name_title_ca').val());	}

if ($('#first_name_ca').length && $('#first_name_ca').val().trim().length > 0){	formData.append('first_name',$('#first_name_ca').val());	}

if ($('#middle_name_ca').length && $('#middle_name_ca').val().trim().length > 0){	formData.append('middle_name',$('#middle_name_ca').val());	}

if ($('#last_name_ca').length && $('#last_name_ca').val().trim().length > 0){	formData.append('last_name',$('#last_name_ca').val());	}

if ($('#name_suffix_ca').length && $('#name_suffix_ca').val().trim().length > 0){	formData.append('name_suffix',$('#name_suffix_ca').val());	}

if ($('#bank_account_name_ca').length && $('#bank_account_name_ca').val().trim().length > 0){	formData.append('bank_account_name',$('#bank_account_name_ca').val());	}

if ($('#bank_account_number_ca').length && $('#bank_account_number_ca').val().trim().length > 0){	formData.append('bank_account_number',$('#bank_account_number_ca').val());	}

if ($('#bank_name_ca').length && $('#bank_name_ca').val().trim().length > 0){	formData.append('bank_name',$('#bank_name_ca').val());	}

if ($('#bank_sort_code_ca').length && $('#bank_sort_code_ca').val().trim().length > 0){	formData.append('bank_sort_code',$('#bank_sort_code_ca').val());	}

if ($('#intl_passport_number_ca').length && $('#intl_passport_number_ca').val().trim().length > 0){	formData.append('intl_passport_number',$('#intl_passport_number_ca').val());	}

if ($('#bank_verification_number_ca').length && $('#bank_verification_number_ca').val().trim().length > 0){
	formData.append('bank_verification_number',$('#bank_verification_number_ca').val());
}

if ($('#national_id_number_ca').length && $('#national_id_number_ca').val().trim().length > 0){	formData.append('national_id_number',$('#national_id_number_ca').val());	}

if ($('#organizer_name_ca').length && $('#organizer_name_ca').val().trim().length > 0){	formData.append('organizer_name',$('#organizer_name_ca').val());	}

if ($('#conference_theme_ca').length && $('#conference_theme_ca').val().trim().length > 0){	formData.append('conference_theme',$('#conference_theme_ca').val());	}

if ($('#conference_address_ca').length && $('#conference_address_ca').val().trim().length > 0){	formData.append('conference_address',$('#conference_address_ca').val());	}

if ($('#conference_passage_type_ca').length && $('#conference_passage_type_ca').val().trim().length > 0){	formData.append('conference_passage_type',$('#conference_passage_type_ca').val());	}

if ($('#accepted_paper_title_ca').length && $('#accepted_paper_title_ca').val().trim().length > 0){	formData.append('accepted_paper_title',$('#accepted_paper_title_ca').val());	}

if ($('#attendee_department_name_ca').length && $('#attendee_department_name_ca').val().trim().length > 0){	formData.append('attendee_department_name',$('#attendee_department_name_ca').val());	}

if ($('#attendee_grade_level_ca').length){	formData.append('attendee_grade_level',$('#attendee_grade_level_ca').val());	}

if ($('#is_academic_staff_ca').length){ formData.append('is_academic_staff',$('#is_academic_staff_ca').val());   }

{{-- determining paper presentation --}}
if ($('#is_academic_staff_ca').length){
	if ($('#is_academic_staff_ca').val()=='1' || ($('#is_academic_staff_ca').val()=='0' && $('#is_conference_workshop_ca').val()=='0') ) {
		formData.append('has_paper_presentation', '1');
	} else {
		formData.append('has_paper_presentation', '0');
	}
}

if ($('#conference_start_date_ca').length){ formData.append('conference_start_date',$('#conference_start_date_ca').val());   }

if ($('#conference_end_date_ca').length){ formData.append('conference_end_date',$('#conference_end_date_ca').val());   }

/* amounts */
if ($('#conference_fee_amount_local_ca').length){
	formData.append('conference_fee_amount_local',$('#conference_fee_amount_local_ca').val().replace(/,/g,""));
}
/* end amounts */

if($('#passport_photo_ca').get(0).files.length != 0){
	formData.append('passport_photo', $('#passport_photo_ca')[0].files[0]);
}

if($('#conference_attendance_letter_ca').get(0).files.length != 0){
	formData.append('conference_attendance_letter', $('#conference_attendance_letter_ca')[0].files[0]);      
}

if($('#paper_presentation_ca').get(0).files.length != 0){
	formData.append('paper_presentation', $('#paper_presentation_ca')[0].files[0]);  
}

if($('#international_passport_bio_page_ca').get(0).files.length != 0){
	formData.append('international_passport_bio_page', $('#international_passport_bio_page_ca')[0].files[0]);  
}

formData.append('country_nigeria_id', '{{$country_nigeria_id}}');