<?php

include 'db_conn.php';

$from = $_POST['from'];
$to = $_POST['to'];

$select = "SELECT * FROM sample where unique_id BETWEEN '$from' AND '$to' ";
$result = $conn->query($select);

while( $row = mysqli_fetch_array($result) ){

    $unique_id = $row['unique_id'];
    $name = $row['name'];
    $domain = $row['domain'];

    $users_arr[] = array("unique_id" => $unique_id,"name" => $name,"domain" => $domain);
}

// encoding array to json format
echo json_encode($users_arr);
