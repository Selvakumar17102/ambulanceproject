<?php
    include("import.php");

    $sql = "SELECT * FROM banner JOIN blood_banner";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $i = 0;
        while($row = $result->fetch_assoc()){
            
            $output_array['GTS'][$i]['banner_image'] = $IMAGE_BASE_URL.$row['banner_image'];
            $output_array['GTS'][$i]['blood_banner_image'] = $IMAGE_BASE_URL.$row['blood_banner_image'];

            $i++;
        }

        $output_array['status'] = true;
    } else{
        http_response_code(404);
        $output_array['status'] = false;
        $output_array['message'] = "Data not found";
    }

    echo json_encode($output_array);
?>