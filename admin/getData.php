<?php
include_once('functions.php');
// include_once('db.php');
session_start();

if(isset($_REQUEST))
{
  $users_arr[] = array();
  if(isset($_GET['getTypesById']))
  {
    $id = $_REQUEST['id'];
    $data = selectData("SELECT * FROM `stock` where id='$id'");
    if(is_array($data) && count($data) >= 1)
    {
        $users_arr = array(
            "status" => "success",
            "id" => $data[0]['id'],
            "name" => $data[0]['name'],
            "type" => $data[0]['type'],
            "details" => $data[0]['details'],
            "price" => $data[0]['price'],
            "status" => $data[0]['status'],
          );
    }
    else
    {
        $users_arr = array(
            "status" => "error"
          );
    }

    // encoding array to json format
    echo json_encode($users_arr);
    exit;
  }
}
?>