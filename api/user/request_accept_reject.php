<?php
    include("import.php");

    if(!empty($header['authorization'])){
		$token = $header['authorization'];

		$responceData = checkAndGenerateToken($conn,$token);

		if($responceData['status']){
			$user_id = $responceData['user_id'];
			$responceToken = $responceData['token'];

			header('authorization: ' . $responceToken);

            if(!empty($data->donor_id) && !empty($data->request_id)){
                $donor_id=$data->donor_id;
                $request_id=$data->request_id;
                $status=$data->status;
            
                $sql = "SELECT * FROM user WHERE user_id='$user_id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $date = date('d-m-Y');
                    $insertSql = "INSERT INTO rq_accept_reject (donor_id,request_id,accept_reject_date,danated_status) VALUES('$donor_id','$request_id','$date','$status')";
                    if($conn->query($insertSql)===TRUE){
                        if($status == 1){
                            $msg = "Accepted";
                        }else{
                            $msg = "Rejected";
                        }
                        http_response_code(200);
                        $output_array['status'] = true;
                        $output_array['message'] = $msg;
                    }
                } else{
                    http_response_code(404);
                    $output_array['status'] = false;
                    $output_array['message'] = "user not found";
                }
            }else{
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