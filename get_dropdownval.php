<?php

include 'db_conn.php';

if(isset($_POST['from'])){

   $min = $_POST['from']; 
   
}

$users_arr = array();

    $sql = "SELECT * FROM sample where unique_id >= $min" ;

    $result = mysqli_query($conn,$sql);
    
    while( $row = mysqli_fetch_array($result) ){

        $unique_id = $row['unique_id'];
    
        $users_arr[] = array("unique_id" => $unique_id);
    }

// encoding array to json format
echo json_encode($users_arr);