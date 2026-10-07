<?php
include_once('functions.php');
// include_once('db.php');
// print_r($_REQUEST);
// exit;
if (isset($_REQUEST))
{
  $users_arr[] = array();
  if(isset($_GET['checkEmail']))
  {
    $email = $_REQUEST['email'];
    // $phone = $_REQUEST['phone'];

    $data = selectData("SELECT * FROM `users` where email='$email'");
    $message = "";
    if(is_array($data) && count($data) >= 1)
    {
	   $users_arr[] = array(
		  "status" => "success"
		);
    }
    else
    {
	   $users_arr[] = array(
		  "status" => "error"
		);
    }

    // encoding array to json format
    echo json_encode($users_arr);
    exit;
  }
  else if(isset($_GET['checkPhone']))
  {
    $phone = $_REQUEST['phone'];
    // $phone = $_REQUEST['phone'];

    $data = selectData("SELECT * FROM `users` where phone='$phone'");
    $message = "";
    if(is_array($data) && count($data) >= 1)
    {
	   $users_arr[] = array(
		  "status" => "success"
		);
    }
    else
    {
	   $users_arr[] = array(
		  "status" => "error"
		);
    }

    // encoding array to json format
    echo json_encode($users_arr);
    exit;
  }
  else if(isset($_GET['getData']))
  {
    $data = selectData("SELECT * FROM `users` order by id desc");
    if(is_array($data) && count($data) >= 1)
    {
	 $no = 1;

	   foreach($data as $value)
	   {
		$date=date_create($value['dt']);
		$dob=date_create($value['dob']);
		echo "<tr>";
		echo "<td>{$no}</td>
		<td>".$value['name']."</td>
		<td>".$value['phone']."</td>
		<td>".$value['email']."</td>
		<td>".$value['gender']."</td>
		<td>".date_format($dob,"d/m/Y")."</td>
		<td>".$value['aadharNo']."</td>
		<td>".($value['status_'] == "active" ? "Active" : "InActive")."</td>
		<td>".date_format($date,"d/m/Y")."</td>
		<td>".'<button type="button" onclick="getDataById('.$value["id"].')" class="btn btn-success" id="">Edit</button>
		<button type="button" onclick="deleteData('.$value["id"].',this)" class="btn btn-danger" id="">Delete</button>'."</td>
		";
		echo "</tr>";
		$no ++;
	   }

    }
    else{
	 echo "<tr>No Data Found...</tr>";
    }
    exit;
  }
  else if(isset($_GET['getDataById']))
  {
    $id = $_REQUEST['getDataById'];
    $data = selectData("SELECT * FROM `users` where id='$id'");
    if(is_array($data) && count($data) >= 1)
    {
	   	$users_arr[] = array(
			"status" => "success",
			"id" => $data[0]['id'],
			"name" => $data[0]['name'],
			"phone" => $data[0]['phone'],
			"email" => $data[0]['email'],
			"gender" => $data[0]['gender'],
			"dob" => $data[0]['dob'],
			"address" => $data[0]['address'],
			"aadharNo" => $data[0]['aadharNo'],
			"pass" => $data[0]['pass'],
			"status_" => $data[0]['status_'],
			"dt" => $data[0]['dt'],
			);
    }
    else
    {
	   $users_arr[] = array(
		  "status" => "error"
		);
    }

    // encoding array to json format
    echo json_encode($users_arr);
    exit;
  }
  else if(isset($_POST['addData']))
  {
    if(isset($_FILES))
    {
	   $uploadResult = uploadFile($_FILES,"idProofImage","upload_images/", array("png","jpg","jpeg") );
	   
	   if($uploadResult['status'] == 'success')
	   {
			$name = $_REQUEST['txtName'];
			$phone = $_REQUEST['txtPhone'];
			$email = $_REQUEST['txtEmail'];
			$password = $_REQUEST['txtPassword'];
			$address = $_REQUEST['txtAddress'];
			$status = 1;//$_REQUEST['isActive'] == "true" ? "active" : "inactive";
			$idproof = $uploadResult['image'];
		
			$sql = "INSERT INTO `users`(`name`,`email`, `pass`, `phone`,`address`, `idproof`, `status`) 
				VALUES ('$name','$email','$password','$phone','$address','$idproof','$status')";
				echo $sql;
			if(insert_update_delete_data($sql) == true)
			{
				$response = array(
					"status" => "success"
					);
			}
			else
			{
				$response = array(
					"status" => "error"
				);
			}
	   }
	   else
	   {
			$response = array(
				"status" => "error",
				"message" => $uploadResult['message']
			);
	   }
    }

    // encoding array to json format
    echo json_encode($response);
    exit;
  }
  else if(isset($_GET['updateData']))
  {
    $id = $_REQUEST['id'];
    $name = $_REQUEST['name'];
    $phone = $_REQUEST['phone'];
    $email = $_REQUEST['email'];
    $gender = $_REQUEST['gender'];
    $dob = $_REQUEST['dob'];
    $aadharNo = $_REQUEST['aadharNo'];
    $password = $_REQUEST['password'];
    $address = $_REQUEST['address'];
    $status = $_REQUEST['isActive'] == "true" ? "active" : "inactive";

    $dt = date("Y-m-d");
    $sql = "update `users` 
		set `name`='$name', 
			`phone`='$phone',
			`email`='$email',
			`gender`='$gender',
			`dob`='$dob',
			`aadharNo`='$aadharNo',
			`address`='$address',
			`pass`='$password', 
			`status_`='$status' 
			where id='$id'";
    if(insert_update_delete_data($sql) == true)
    {
	   $users_arr[] = array(
		  "status" => "success"
		);
    }
    else
    {
	   $users_arr[] = array(
		  "status" => "error"
		);
    }

    // encoding array to json format
    echo json_encode($users_arr);
    exit;
  }
  else if(isset($_GET['deleteDataById']))
  {
    $id = $_REQUEST['id'];
    $sql = "delete from `users` where id='$id'";
    if(insert_update_delete_data($sql) == true)
    {
	   $users_arr[] = array(
		  "status" => "success"
		);
    }
    else
    {
	   $users_arr[] = array(
		  "status" => "error"
		);
    }

    // encoding array to json format
    echo json_encode($users_arr);
    exit;
  }
}

