<?php
    include("import.php");

    $sql = "SELECT * FROM blood_banner";
    $result = $conn->query($sql);
    $i = 0; 
    while($row = $result->fetch_assoc()){
        $output_array["GTS"][$i]['id'] = (int)$row['id'];
        $output_array["GTS"][$i]['blood_banner_image'] = $row['blood_banner_image'];

        $i++;

    }
    echo json_encode($output_array);
?>