if ($('#email_tp').length && $('#email_tp').val().trim().length > 0){	formData.append('email',$('#email_tp').val());	}

if ($('#telephone_tp').length && $('#telephone_tp').val().trim().length > 0){	formData.append('telephone',$('#telephone_tp').val());	}

if ($('#beneficiary_institution_id_select_tp').length && $('#beneficiary_institution_id_select_tp').val().trim().length > 0){	
	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_tp').val());	
}

if ($('#institution_id_select_tp').length && $('#institution_id_select_tp').val().trim().length > 0){  
		formData.append('tf_iterum_portal_institution_id',$('#institution_id_select_tp').val());  
}

if ($('#gender_tp').length && $('#gender_tp').val().trim().length > 0){   formData.append('gender',$('#gender_tp').val());   }

if ($('#name_title_tp').length && $('#name_title_tp').val().trim().length > 0){	formData.append('name_title',$('#name_title_tp').val());	}

if ($('#first_name_tp').length && $('#first_name_tp').val().trim().length > 0){	formData.append('first_name',$('#first_name_tp').val());	}

if ($('#middle_name_tp').length && $('#middle_name_tp').val().trim().length > 0){	formData.append('middle_name',$('#middle_name_tp').val());	}

if ($('#last_name_tp').length && $('#last_name_tp').val().trim().length > 0){	formData.append('last_name',$('#last_name_tp').val());	}

if ($('#name_suffix_tp').length && $('#name_suffix_tp').val().trim().length > 0){	formData.append('name_suffix',$('#name_suffix_tp').val());	}

if ($('#bank_account_name_tp').length && $('#bank_account_name_tp').val().trim().length > 0){	formData.append('bank_account_name',$('#bank_account_name_tp').val());	}

if ($('#bank_account_number_tp').length && $('#bank_account_number_tp').val().trim().length > 0){	formData.append('bank_account_number',$('#bank_account_number_tp').val());	}

if ($('#bank_name_tp').length && $('#bank_name_tp').val().trim().length > 0){	formData.append('bank_name',$('#bank_name_tp').val());	}

if ($('#bank_sort_code_tp').length && $('#bank_sort_code_tp').val().trim().length > 0){	formData.append('bank_sort_code',$('#bank_sort_code_tp').val());	}

if ($('#bank_verification_number_tp').length && $('#bank_verification_number_tp').val().trim().length > 0){
	formData.append('bank_verification_number',$('#bank_verification_number_tp').val());
}

if ($('#national_id_number_tp').length && $('#national_id_number_tp').val().trim().length > 0){	formData.append('national_id_number',$('#national_id_number_tp').val());	}

if ($('#degree_type_tp').length && $('#degree_type_tp').val().trim().length > 0){	formData.append('degree_type',$('#degree_type_tp').val());	}

if ($('#program_title_tp').length && $('#program_title_tp').val().trim().length > 0){	formData.append('program_title',$('#program_title_tp').val());	}

if ($('#program_type_tp').length && $('#program_type_tp').val().trim().length > 0){	formData.append('program_type',$('#program_type_tp').val());	}

if ($('#is_science_program_tp').length && $('#is_science_program_tp').val().trim().length > 0){ formData.append('is_science_program',$('#is_science_program_tp').val());   }

if ($('#program_start_date_tp').length && $('#program_start_date_tp').val().trim().length > 0){ formData.append('program_start_date',$('#program_start_date_tp').val());   }

if ($('#program_end_date_tp').length && $('#program_end_date_tp').val().trim().length > 0){ formData.append('program_end_date',$('#program_end_date_tp').val());   }



if($('#passport_photo_tp').get(0).files.length != 0){
	formData.append('passport_photo', $('#passport_photo_tp')[0].files[0]);
}

if($('#invitation_letter_tp').get(0).files.length != 0){
	formData.append('invitation_letter', $('#invitation_letter_tp')[0].files[0]);      
}
