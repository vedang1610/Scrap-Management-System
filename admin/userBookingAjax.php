<?php
include_once('functions.php');
// include_once('db.php');
session_start();

if (isset($_GET))
{
  $users_arr[] = array();
  if(isset($_GET['checkData']))
  {
    $name = $_REQUEST['name'];
    $data = selectData("SELECT * FROM `cylindersize` where name='$name'");
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
     $userId = $_SESSION['userId'];
    $data = selectData("SELECT * FROM `bookings` where userId='$userId' and status_!='Canceled by user' order by id desc");
    if(is_array($data) && count($data) >= 1)
    {
      $no = 1;
        foreach($data as $value)
        {
          $bookingDate=date_create($value['dt']);
          $bookingDate = date_format($bookingDate,"d/m/Y");

          $deliveryDate="";
          
          if($value['deliveryDate'] == "-")
          {
               // $deliveryDate="Not Delivered";
               $deliveryDate="-";
          }
          else
          {
               $deliveryDate=date_create($value['deliveryDate']);
               $deliveryDate = date_format($deliveryDate,"d/m/Y");
          }

          $calcelButton = "";
          if(($value['status_'] == "Confirmed" || $value['status_'] == "Pending") 
                    && $value['paymentStatus'] == "Pending")
          {
               $calcelButton = '<button type="button" onclick="cancelBooking('.$value["id"].',this)" class="btn btn-warning" id="">Cancel Booking
               </button>';
          }
          $invoiceButton = "";
          // if($value['status_'] == "Select Cylinder Size") // && $value['paymentStatus'] == "Paid")
          if($value['paymentStatus'] == "Paid")
          {
               $invoiceButton = '<a href="viewInvoice.php?InvoiceId='.$value["invoiceNo"].'" target="_blank"><button type="button" class="btn btn-info" id="">View Invoice
               </button></a>';
          }

          echo "<tr>";
          echo "<td>{$no}</td>
          <td>".$bookingDate."</td>
          <td>".$value['bookingId']."</td>
          <td>".$value['size']."</td>
          <td>".$value['price']."</td>
          <td>".$value['extraChargePrice']."</td>
          <td>".$deliveryDate."</td>
          <td>".$value['remark']."</td>
          <td>".($value['status_'])."</td>
          <td>".($value['paymentMode'])."</td>
          <td>".'<button type="button" onclick="getDataById('.$value["id"].')" class="btn btn-success" id="">View</button>
          '.$calcelButton.$invoiceButton."</td>";
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
    $data = selectData("SELECT * FROM `bookings` where id='$id'");
    if(is_array($data) && count($data) >= 1)
    {
          $bookingDate=date_create($data[0]['dt']);
          $bookingDate = date_format($bookingDate,"d/m/Y");

          $deliveryDate="";
          if($data[0]['deliveryDate'] == "-")
          {
               $deliveryDate="-";
          }
          else
          {
               $deliveryDate=date_create($data[0]['deliveryDate']);
               $deliveryDate = date_format($deliveryDate,"d/m/Y");
          }
        $users_arr[] = array(
            "status" => "success",
            "id" => $data[0]['id'],
            "invoiceNo" => $data[0]['invoiceNo'],
            "bookingId" => $data[0]['bookingId'],
            "userId" => $data[0]['userId'],
            "sizeId" => $data[0]['sizeId'],
            "size" => $data[0]['size'],
            "price" => $data[0]['price'],
            "extraCharge" => $data[0]['extraCharge'],
            "extraChargePrice" => $data[0]['extraChargePrice'],
            "remark" => $data[0]['remark'],
            "staffId" => $data[0]['staffId'],
            "staffName" => $data[0]['staffName'],
            "deliveryDate" => $deliveryDate,
            "status_" => $data[0]['status_'],
            "dt" => $bookingDate,
            "paymentStatus" => $data[0]['paymentStatus'],
            "paymentMode" => $data[0]['paymentMode']
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
  else if(isset($_GET['addData']))
  {
    $sizeId = $_REQUEST['sizeId'];
    $userId = $_SESSION['userId'];
    $paymentMode = $_REQUEST['paymentMode'];

    $bookingId = getBookingNumberByUser($userId);
    $invoiceId = getInvoiceNumber($userId);
    $sizeArray = getSizeById($sizeId);

    $sizeName = "";
    $sizePrice = 0;
    if(is_array($sizeArray) && count($sizeArray) >= 1)
    {
          $sizeName = $sizeArray[0]['name'];
          $sizePrice = $sizeArray[0]['price'];
    }
     
    $dt = date("Y-m-d");
    $sql = "INSERT INTO `bookings`(`invoiceNo`, `bookingId`, `userId`, `sizeId`, `size`, `price`, `extraCharge`, 
          `extraChargePrice`, `remark`, `staffId`, `staffName`, `deliveryDate`, `status_`, `dt`, `paymentStatus`,`paymentMode`) 
          VALUES ('$invoiceId','$bookingId','$userId','$sizeId','$sizeName','$sizePrice','-','0','-','0','-','-','Pending','$dt','Pending','$paymentMode')";
    if(insert_update_delete_data($sql) == true)
    {
        $sqlL = "select * from `bookings` where userId='$userId' order by id desc limit 1";
        $newUserData = selectData($sqlL);
        $bid = $newUserData[0]['id'];
        $bprice = $newUserData[0]['price'];
        $bextraprice = $newUserData[0]['extraChargePrice'];
        $btotal = ($bprice + $bextraprice);
        $url = "payment/payment.php?txtId=$bid&txtAmount=$btotal";
        // }
        // 
        // echo "<script>window.location='$url';</script>";
        if(round($newUserData[0]['price'], 0) != 0 && $newUserData[0]['price'] != "COD")
        {
          $users_arr[] = array(
            "status" => "success",
            "url" => $url
          );
        }
        else
        {
          $users_arr[] = array(
            "status" => "success"
          );
        }
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
  else if(isset($_GET['updateData']))
  {
    $id = $_REQUEST['id'];
    $sizeId = $_REQUEST['sizeId'];
    $paymentMode = $_REQUEST['paymentMode'];

    $sizeArray = getSizeById($sizeId);
    $sizeName = "";
    $sizePrice = 0;
    if(is_array($sizeArray) && count($sizeArray) >= 1)
    {
          $sizeName = $sizeArray[0]['name'];
          $sizePrice = $sizeArray[0]['price'];
    }
    $dt = date("Y-m-d");
    $sql = "update `bookings` set `sizeId`='$sizeId', `size`='$sizeName', `price`='$sizePrice', `paymentMode`='$paymentMode' where id='$id'";
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
    $sql = "delete from `cylindersize` where id='$id'";
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
  else if(isset($_GET['cancelBooking']))
  {
    $id = $_REQUEST['id'];
    $sql = "update bookings set status_='Canceled by user' where id='$id'";
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
