<?php
	include("import.php");

	if(!empty($data->city_id)){
		$city_id = $data->city_id;

		$sql = "SELECT * FROM blood_bank WHERE blood_bank_city_id='$city_id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
            $i = 0; 
            while($row = $result->fetch_assoc()){
                $output_array["GTS"][$i]['blood_bank_id'] = (int)$row['blood_bank_id'];
                $output_array["GTS"][$i]['blood_bank_name'] = $row['blood_bank_name'];
                $output_array["GTS"][$i]['blood_bank_address'] = $row['blood_bank_address'];
                $output_array["GTS"][$i]['blood_bank_latitude'] = $row['blood_bank_latitude'];
                $output_array["GTS"][$i]['blood_bank_longitude'] = $row['blood_bank_longitude'];
                $i++;
            }
		} else{
			http_response_code(500);
			$output_array['status'] = false;
			$output_array['message'] = "blood bank not found";
		}
	} else{
		http_response_code(400);
		$output_array['status'] = false;
		$output_array['message'] = "Bad request";
	}

	echo json_encode($output_array);
?>