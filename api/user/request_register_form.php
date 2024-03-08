<?php
	include("import.php");
    include("../onesignaluser.php");
    include("../onesignaldelivery.php");
	date_default_timezone_set("Asia/Calcutta");

	if(!empty($header['authorization'])){
		$token = $header['authorization'];

		$responceData = checkAndGenerateToken($conn,$token);

		if($responceData['status']){
			$user_id = $responceData['user_id'];
			$responceToken = $responceData['token'];

			header('authorization: ' . $responceToken);

			    if(!empty($data->patient_name) && !empty($data->blood_group) && !empty($data->unit)){
					$patient_name = $data->patient_name;
					$age = $data->age;
                    $phone_no = $data->phone_no;
                    $alter_phone_no = $data->alter_phone_no;
                    $blood_group = $data->blood_group;
					$blood_type = $data->blood_type;
                    $request_date = $data->request_date;
                    $unit = $data->unit;
                    $address = $data->address;
                    $emergency_status = $data->emergency_status;
					$latitude = $data->latitude;
					$longitude = $data->longitude;
                    // $city_id = $data->city_id;
                    // $additional_notes = $data->additional_notes;
                    // $check_terms = $data->check_terms;
                    $dummy_otp = rand(0001,9999);

                    $sql = "INSERT INTO blood_request (user_id,patient_name,age,phone_no,alter_phone_no,blood_group,blood_type,request_date,unit,hospital_location,latitude,longitude,emergency_status,dummy_otp)VALUES ('$user_id','$patient_name','$age','$phone_no','$alter_phone_no','$blood_group','$blood_type','$request_date','$unit','$address','$latitude','$longitude','$emergency_status','$dummy_otp')";
                    if($conn->query($sql) === TRUE){
                        http_response_code(200);
                        $output_array['status'] = true;
                        $output_array['message'] = "Register Successfully!";
                    }
                    
			    } else{
			    	http_response_code(400);
			    	$output_array['status'] = false;
			    	$output_array['message'] = "Bad request";
			    }
		} else{
			http_response_code(401);
			$output_array['status'] = false;
			$output_array['message'] = "Invalid Authentication";
		}
	} else{
		http_response_code(401);
		$output_array['status'] = false;
		$output_array['message'] = "Authentication Missing";
	}

	echo json_encode($output_array);
?>