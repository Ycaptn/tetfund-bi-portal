if ($('#email_tp').length){	formData.append('email',$('#email_tp').val());	}

if ($('#telephone_tp').length){	formData.append('telephone',$('#telephone_tp').val());	}

if ($('#beneficiary_institution_id_select_tp').length){	
	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_tp').val());	
}

if ($('#institution_id_select_tp').length){  
		formData.append('tf_iterum_portal_institution_id',$('#institution_id_select_tp').val());  
}

if ($('#country_id_select_tp').length){   formData.append('tf_iterum_portal_country_id',$('#country_id_select_tp').val());   }

if ($('#gender_tp').length){   formData.append('gender',$('#gender_tp').val());   }

if ($('#name_title_tp').length){	formData.append('name_title',$('#name_title_tp').val());	}

if ($('#first_name_tp').length){	formData.append('first_name',$('#first_name_tp').val());	}

if ($('#middle_name_tp').length){	formData.append('middle_name',$('#middle_name_tp').val());	}

if ($('#last_name_tp').length){	formData.append('last_name',$('#last_name_tp').val());	}

if ($('#name_suffix_tp').length){	formData.append('name_suffix',$('#name_suffix_tp').val());	}

if ($('#bank_account_name_tp').length){	formData.append('bank_account_name',$('#bank_account_name_tp').val());	}

if ($('#bank_account_number_tp').length){	formData.append('bank_account_number',$('#bank_account_number_tp').val());	}

if ($('#bank_name_tp').length){	formData.append('bank_name',$('#bank_name_tp').val());	}

if ($('#bank_sort_code_tp').length){	formData.append('bank_sort_code',$('#bank_sort_code_tp').val());	}

if ($('#intl_passport_number_tp').length){	formData.append('intl_passport_number',$('#intl_passport_number_tp').val());	}

if ($('#bank_verification_number_tp').length){
	formData.append('bank_verification_number',$('#bank_verification_number_tp').val());
}

if ($('#national_id_number_tp').length){	formData.append('national_id_number',$('#national_id_number_tp').val());	}

if ($('#degree_type_tp').length){	formData.append('degree_type',$('#degree_type_tp').val());	}

if ($('#program_title_tp').length){	formData.append('program_title',$('#program_title_tp').val());	}

if ($('#program_type_tp').length){	formData.append('program_type',$('#program_type_tp').val());	}

if ($('#is_science_program_tp').length){ formData.append('is_science_program',$('#is_science_program_tp').val());   }

if ($('#program_start_date_tp').length){ formData.append('program_start_date',$('#program_start_date_tp').val());   }

if ($('#program_end_date_tp').length){ formData.append('program_end_date',$('#program_end_date_tp').val());   }



formData.append('passport_photo', $('#passport_photo_tp')[0].files[0]);

formData.append('admission_letter', $('#admission_letter_tp')[0].files[0]);      

formData.append('health_report', $('#health_report_tp')[0].files[0]);  

formData.append('international_passport_bio_page', $('#international_passport_bio_page_tp')[0].files[0]);  

formData.append('conference_attendence_letter', $('#conference_attendence_letter_tp')[0].files[0]);  