<?php
    include("import.php");

    if(!empty($header['authorization'])){
		$token = $header['authorization'];

		$responceData = checkAndGenerateToken($conn,$token);

		if($responceData['status']){
			$user_id = $responceData['user_id'];
			$responceToken = $responceData['token'];

			header('authorization: ' . $responceToken);

            $sql = "SELECT * FROM user WHERE user_id='$user_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $sql = "SELECT *,a.blood_group as requestbloodgroup FROM blood_request a LEFT OUTER JOIN user b ON a.user_id=b.user_id WHERE a.status = '0'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $i = 0;
                    while($row = $result->fetch_assoc()){

                        if($row['emergency_status'] == 1){
                            $eStatus = "Emergency";
                        }else{
                            $eStatus = "Non-Emergency";
                        }

                        
                        $output_array['GTS'][$i]['user_id'] = (int)$row['user_id'];
                        $output_array['GTS'][$i]['patient_name'] = $row['patient_name'];
                        $output_array['GTS'][$i]['blood_group'] = $row['requestbloodgroup'];
                        $output_array['GTS'][$i]['phone_number'] = $row['user_phone_number'];
                        $output_array['GTS'][$i]['alter_phone_no'] = $row['alter_phone_no'];
                        $output_array['GTS'][$i]['request_date'] = $row['request_date'];
                        $output_array['GTS'][$i]['unit'] = $row['unit'];
                        $output_array['GTS'][$i]['hospital_location'] = $row['hospital_location'];
                        $output_array['GTS'][$i]['emergency_status'] = $eStatus;
                        $output_array['GTS'][$i]['additional_notes'] = $row['additional_notes'];
                        $output_array['GTS'][$i]['status'] = (int)$row['status'];
                        $i++;
                    }

                    $output_array['status'] = true;
                } else{
                    http_response_code(404);
					$output_array['status'] = false;
					$output_array['message'] = "Data not found";
                }
            } else{
                http_response_code(404);
                $output_array['status'] = false;
                $output_array['message'] = "Data not found";
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