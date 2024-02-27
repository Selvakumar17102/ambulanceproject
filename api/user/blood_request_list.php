<?php
    include("import.php");

    if(!empty($header['authorization'])){
		$token = $header['authorization'];

		$responceData = checkAndGenerateToken($conn,$token);

		if($responceData['status']){
			$user_id = $responceData['user_id'];
			$responceToken = $responceData['token'];

			header('authorization: ' . $responceToken);

            // $sql = "SELECT * FROM user WHERE user_id='$user_id'";
            // $result = $conn->query($sql);
            // if($result->num_rows > 0){
            //     $row = $result->fetch_assoc();

                $donorsql = "SELECT * FROM blood_donation WHERE user_id = '$user_id'";
                $donorresult = $conn->query($donorsql);
                if($donorresult->num_rows > 0){
                    $donorrow = $donorresult->fetch_assoc();
                    $city_id = $donorrow['donor_city_id'];
                    $reqSql ="SELECT *,a.blood_group as blood FROM blood_request a LEFT OUTER JOIN user b ON a.user_id=b.user_id WHERE request_city_id ='$city_id'";
                    $reqResult = $conn->query($reqSql);
                    $i = 0;
                    if($reqResult->num_rows > 0){
                        while($reqRow = $reqResult->fetch_assoc()){
                            if($reqRow['emergency_status'] == 1){
                                $eStatus = "Emergency";
                            }else{
                                $eStatus = "Non-Emergency";
                            }

                            $output_array['GTS'][$i]['blood_request_id'] = $reqRow['blood_request_id'];
                            $output_array['GTS'][$i]['patient_name'] = $reqRow['patient_name'];
                            $output_array['GTS'][$i]['blood_group'] = $reqRow['blood'];
                            $output_array['GTS'][$i]['phone_number'] = $reqRow['user_phone_number'];
                            $output_array['GTS'][$i]['alter_phone_no'] = $reqRow['alter_phone_no'];
                            $output_array['GTS'][$i]['request_date'] = $reqRow['request_date'];
                            $output_array['GTS'][$i]['unit'] = $reqRow['unit'];
                            $output_array['GTS'][$i]['hospital_location'] = $reqRow['hospital_location'];
                            $output_array['GTS'][$i]['emergency_status'] = $eStatus;
                            $output_array['GTS'][$i]['additional_notes'] = $reqRow['additional_notes'];
                            $i++;

                            $output_array['status'] = true;
                        }
                    }else{
                        http_response_code(404);
					    $output_array['status'] = false;
					    $output_array['message'] = "Request not found";
                    }
                } else{
                    http_response_code(404);
					$output_array['status'] = false;
					$output_array['message'] = "Data not found";
                }
            // } else{
            //     http_response_code(404);
            //     $output_array['status'] = false;
            //     $output_array['message'] = "Data not found";
            // }
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