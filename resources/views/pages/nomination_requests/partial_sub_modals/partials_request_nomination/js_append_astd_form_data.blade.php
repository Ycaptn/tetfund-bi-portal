if ($('#email').length){	formData.append('email',$('#email').val());	}

if ($('#telephone').length){	formData.append('telephone',$('#telephone').val());	}

if ($('#beneficiary_institution_id_select').length){	
	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select').val());	
}

if ($('#institution_id_select').length){  
		formData.append('tf_iterum_portal_institution_id',$('#institution_id_select').val());  
}

if ($('#country_id_select').length){   formData.append('tf_iterum_portal_country_id',$('#country_id_select').val());   }

if ($('#gender').length){   formData.append('gender',$('#gender').val());   }

if ($('#name_title').length){	formData.append('name_title',$('#name_title').val());	}

if ($('#first_name').length){	formData.append('first_name',$('#first_name').val());	}

if ($('#middle_name').length){	formData.append('middle_name',$('#middle_name').val());	}

if ($('#last_name').length){	formData.append('last_name',$('#last_name').val());	}

if ($('#name_suffix').length){	formData.append('name_suffix',$('#name_suffix').val());	}

if ($('#bank_account_name').length){	formData.append('bank_account_name',$('#bank_account_name').val());	}

if ($('#bank_account_number').length){	formData.append('bank_account_number',$('#bank_account_number').val());	}

if ($('#bank_name').length){	formData.append('bank_name',$('#bank_name').val());	}

if ($('#bank_sort_code').length){	formData.append('bank_sort_code',$('#bank_sort_code').val());	}

if ($('#intl_passport_number').length){	formData.append('intl_passport_number',$('#intl_passport_number').val());	}

if ($('#bank_verification_number').length){
	formData.append('bank_verification_number',$('#bank_verification_number').val());
}

if ($('#national_id_number').length){	formData.append('national_id_number',$('#national_id_number').val());	}

if ($('#degree_type').length){	formData.append('degree_type',$('#degree_type').val());	}

if ($('#program_title').length){	formData.append('program_title',$('#program_title').val());	}

if ($('#program_type').length){	formData.append('program_type',$('#program_type').val());	}

if ($('#is_science_program').length){ formData.append('is_science_program',$('#is_science_program').val());   }

if ($('#program_start_date').length){ formData.append('program_start_date',$('#program_start_date').val());   }

if ($('#program_end_date').length){ formData.append('program_end_date',$('#program_end_date').val());   }



formData.append('passport_photo', $('#passport_photo')[0].files[0]);

formData.append('admission_letter', $('#admission_letter')[0].files[0]);      

formData.append('health_report', $('#health_report')[0].files[0]);  

formData.append('international_passport_bio_page', $('#international_passport_bio_page')[0].files[0]);  

formData.append('conference_attendence_letter', $('#conference_attendence_letter')[0].files[0]);  