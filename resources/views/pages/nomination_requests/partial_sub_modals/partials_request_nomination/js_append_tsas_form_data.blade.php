if ($('#email_tsas').length && $('#email_tsas').val().trim().length > 0){	formData.append('email',$('#email_tsas').val());	}

if ($('#telephone_tsas').length && $('#telephone_tsas').val().trim().length > 0){	formData.append('telephone',$('#telephone_tsas').val());	}

if ($('#beneficiary_institution_id_select_tsas').length && $('#beneficiary_institution_id_select_tsas').val().trim().length > 0){	
	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_tsas').val());	
}

if ($('#country_id_select_tsas').length && $('#country_id_select_tsas').val().trim().length > 0){   formData.append('tf_iterum_portal_country_id',$('#country_id_select_tsas').val());   }

if ($('#institution_name_tsas').length && $('#institution_name_tsas').val().trim().length > 0){
	formData.append('institution_name',$('#institution_name_tsas').val());  
}

if ($('#gender_tsas').length && $('#gender_tsas').val().trim().length > 0){   formData.append('gender',$('#gender_tsas').val());   }

if ($('#name_title_tsas').length && $('#name_title_tsas').val().trim().length > 0){	formData.append('name_title',$('#name_title_tsas').val());	}

if ($('#first_name_tsas').length && $('#first_name_tsas').val().trim().length > 0){	formData.append('first_name',$('#first_name_tsas').val());	}

if ($('#middle_name_tsas').length && $('#middle_name_tsas').val().trim().length > 0){	formData.append('middle_name',$('#middle_name_tsas').val());	}

if ($('#last_name_tsas').length && $('#last_name_tsas').val().trim().length > 0){	formData.append('last_name',$('#last_name_tsas').val());	}

if ($('#name_suffix_tsas').length && $('#name_suffix_tsas').val().trim().length > 0){	formData.append('name_suffix',$('#name_suffix_tsas').val());	}

if ($('#bank_account_name_tsas').length && $('#bank_account_name_tsas').val().trim().length > 0){	formData.append('bank_account_name',$('#bank_account_name_tsas').val());	}

if ($('#bank_account_number_tsas').length && $('#bank_account_number_tsas').val().trim().length > 0){	formData.append('bank_account_number',$('#bank_account_number_tsas').val());	}

if ($('#bank_name_tsas').length && $('#bank_name_tsas').val().trim().length > 0){	formData.append('bank_name',$('#bank_name_tsas').val());	}

if ($('#bank_sort_code_tsas').length && $('#bank_sort_code_tsas').val().trim().length > 0){	formData.append('bank_sort_code',$('#bank_sort_code_tsas').val());	}

if ($('#intl_passport_number_tsas').length && $('#intl_passport_number_tsas').val().trim().length > 0){	formData.append('intl_passport_number',$('#intl_passport_number_tsas').val());	}

if ($('#bank_verification_number_tsas').length && $('#bank_verification_number_tsas').val().trim().length > 0){
	formData.append('bank_verification_number',$('#bank_verification_number_tsas').val());
}

if ($('#national_id_number_tsas').length && $('#national_id_number_tsas').val().trim().length > 0){	formData.append('national_id_number',$('#national_id_number_tsas').val());	}

if ($('#degree_type_tsas').length && $('#degree_type_tsas').val().trim().length > 0){	formData.append('degree_type',$('#degree_type_tsas').val());	}

if ($('#program_title_tsas').length && $('#program_title_tsas').val().trim().length > 0){	formData.append('program_title',$('#program_title_tsas').val());	}

if ($('#program_type_tsas').length && $('#program_type_tsas').val().trim().length > 0){	formData.append('program_type',$('#program_type_tsas').val());	}

if ($('#is_science_program_tsas').length && $('#is_science_program_tsas').val().trim().length > 0){ formData.append('is_science_program',$('#is_science_program_tsas').val());   }

if ($('#program_start_date_tsas').length && $('#program_start_date_tsas').val().trim().length > 0){ formData.append('program_start_date',$('#program_start_date_tsas').val());   }

if ($('#program_end_date_tsas').length && $('#program_end_date_tsas').val().trim().length > 0){ formData.append('program_end_date',$('#program_end_date_tsas').val());   }



if($('#passport_photo_tsas').get(0).files.length != 0){
	formData.append('passport_photo', $('#passport_photo_tsas')[0].files[0]);
}

if($('#admission_letter_tsas').get(0).files.length != 0){
	formData.append('admission_letter', $('#admission_letter_tsas')[0].files[0]);      
}

if($('#health_report_tsas').get(0).files.length != 0){
	formData.append('health_report', $('#health_report_tsas')[0].files[0]);  
}

if($('#curriculum_vitae_tsas').get(0).files.length != 0){
	formData.append('curriculum_vitae', $('#curriculum_vitae_tsas')[0].files[0]);  
}

if($('#signed_bond_with_beneficiary_tsas').get(0).files.length != 0){
	formData.append('signed_bond_with_beneficiary', $('#signed_bond_with_beneficiary_tsas')[0].files[0]);  
}

if($('#international_passport_bio_page_tsas').get(0).files.length != 0){
	formData.append('international_passport_bio_page', $('#international_passport_bio_page_tsas')[0].files[0]);  
}

formData.append('country_nigeria_id', '{{$country_nigeria_id}}');