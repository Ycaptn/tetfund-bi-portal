if ($('#email').length && $('#email').val().trim().length > 0){	formData.append('email',$('#email').val());	}

if ($('#telephone').length && $('#telephone').val().trim().length > 0){	formData.append('telephone',$('#telephone').val());	}

if ($('#beneficiary_institution_id_select').length && $('#beneficiary_institution_id_select').val().trim().length > 0){	
	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select').val());	
}

if ($('#institution_id_select').length && $('#institution_id_select').val().trim().length > 0){  
		formData.append('tf_iterum_portal_institution_id',$('#institution_id_select').val());  
}

if ($('#country_id_select').length && $('#country_id_select').val().trim().length > 0){   formData.append('tf_iterum_portal_country_id',$('#country_id_select').val());   }

if ($('#gender').length && $('#gender').val().trim().length > 0){   formData.append('gender',$('#gender').val());   }

if ($('#name_title').length && $('#name_title').val().trim().length > 0){	formData.append('name_title',$('#name_title').val());	}

if ($('#first_name').length && $('#first_name').val().trim().length > 0){	formData.append('first_name',$('#first_name').val());	}

if ($('#middle_name').length && $('#middle_name').val().trim().length > 0){	formData.append('middle_name',$('#middle_name').val());	}

if ($('#last_name').length && $('#last_name').val().trim().length > 0){	formData.append('last_name',$('#last_name').val());	}

if ($('#name_suffix').length && $('#name_suffix').val().trim().length > 0){	formData.append('name_suffix',$('#name_suffix').val());	}

if ($('#bank_account_name').length && $('#bank_account_name').val().trim().length > 0){	formData.append('bank_account_name',$('#bank_account_name').val());	}

if ($('#bank_account_number').length && $('#bank_account_number').val().trim().length > 0){	formData.append('bank_account_number',$('#bank_account_number').val());	}

if ($('#bank_name').length && $('#bank_name').val().trim().length > 0){	formData.append('bank_name',$('#bank_name').val());	}

if ($('#bank_sort_code').length && $('#bank_sort_code').val().trim().length > 0){	formData.append('bank_sort_code',$('#bank_sort_code').val());	}

if ($('#intl_passport_number').length && $('#intl_passport_number').val().trim().length > 0){	formData.append('intl_passport_number',$('#intl_passport_number').val());	}

if ($('#bank_verification_number').length && $('#bank_verification_number').val().trim().length > 0){
	formData.append('bank_verification_number',$('#bank_verification_number').val());
}

if ($('#national_id_number').length && $('#national_id_number').val().trim().length > 0){	formData.append('national_id_number',$('#national_id_number').val());	}

if ($('#degree_type').length && $('#degree_type').val().trim().length > 0){	formData.append('degree_type',$('#degree_type').val());	}

if ($('#program_title').length && $('#program_title').val().trim().length > 0){	formData.append('program_title',$('#program_title').val());	}

if ($('#program_type').length && $('#program_type').val().trim().length > 0){	formData.append('program_type',$('#program_type').val());	}

if ($('#is_science_program').length && $('#is_science_program').val().trim().length > 0){ formData.append('is_science_program',$('#is_science_program').val());   }

if ($('#program_start_date').length && $('#program_start_date').val().trim().length > 0){ formData.append('program_start_date',$('#program_start_date').val());   }

if ($('#program_end_date').length && $('#program_end_date').val().trim().length > 0){ formData.append('program_end_date',$('#program_end_date').val());   }



if($('#passport_photo').get(0).files.length != 0){
	formData.append('passport_photo', $('#passport_photo')[0].files[0]);
}

if($('#admission_letter').get(0).files.length != 0){
	formData.append('admission_letter', $('#admission_letter')[0].files[0]);      
}

if($('#health_report').get(0).files.length != 0){
	formData.append('health_report', $('#health_report')[0].files[0]);  
}

if($('#international_passport_bio_page').get(0).files.length != 0){
	formData.append('international_passport_bio_page', $('#international_passport_bio_page')[0].files[0]);  
}

if($('#conference_attendence_letter').get(0).files.length != 0){
	formData.append('conference_attendence_letter', $('#conference_attendence_letter')[0].files[0]);  
}
