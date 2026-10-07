<?php
// created by sm

include_once("db.php");
// This Functon is Used to Insert / Update / Delete Data
function insert_update_delete_data($qry)
{
    global $con;
    try
    {
        $result = mysqli_query($con,$qry);
        if($result)
        {
          return true;
        }
        else
        {
          return false;
        }
    }
    catch(Throwable $e)
    {
        // Returning the exception object made a failed query look like success (`== true`)
        return false;
    }
}

function selectData($qry)
{
    global $con;
    $arr = array();
    try
    {
        $result = mysqli_query($con,$qry);
        while($row = mysqli_fetch_assoc($result))
        {
          array_push($arr,$row);
        }
        return $arr;
    }
    catch(Throwable $e)
    {
        return array();
    }
}

// select Example
// print_r(selectData("SELECT * FROM `admin`"));

//This function will help you to run JS without adding script tags.
function runJavascript($script)
{
  echo "<script>{$script}</script>";
}

function getSwalMessgage($title, $text = "", $onSuccess = "", $onFail = "", $icon = "info", $buttons = "false",  $dangerMode = "true")
{
     return $messageString = '
        swal({
          title: "'.$title.'",
          text: "'.$text.'",
          icon: "'.$icon.'",
          '.($buttons == "false" ? "" : "buttons: $buttons,").'
          dangerMode: '.$dangerMode.',
        })
        .then((willDelete) => {
          if (willDelete) 
          {
               '.$onSuccess.'
          }
          else
          {
               '.$onFail.'
          }
        });
        ';
}
// runJavascript("alert('In');");

function getBookingNumberByUser($userId)
{
     $count = 0;
     $data = selectData("select * from bookings where userId='$userId'");
     // print_r($data);
     if(is_array($data) && count($data) >= 1)
     {
          $count = count($data);
     }
     else
     {
          $count = 0;
     }
     $count = $count + 1;
     $bookingId = "BK-$userId-$count";
     return $bookingId;
}
// echo getBookingNumberByUser(9);
function getInvoiceNumber($userId)
{
     $count = 0;
     $data = selectData("select * from bookings");
     if(is_array($data) && count($data) >= 1)
     {
          $count = count($data);
     }
     else
     {
          $count = 0;
     }
     $count = $count + 1;
     $dt = date("dmY");
     $bookingId = "INV-$dt-$userId-$count";
     return $bookingId;
}

function getSizeById($id)
{
     $arr = array();
     $data = selectData("select * from cylindersize where id='$id'");
     if(is_array($data) && count($data) >= 1)
     {
          foreach($data as $row)
          {
               array_push($arr,$row);
          }
     }
     return $arr;
}

function uploadFile($file, $attributeName, $path, $allowed_extension = [], $size = 1000000)
{ 
     try 
     {
          $response = "";   
          $target_dir = $path;
          // echo $target_dir;
          $target_file = $target_dir . basename($file[$attributeName]["name"]);
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
          $uploadOk = 1;

          $fileinfo = @getimagesize($file[$attributeName]["tmp_name"]);
          $width = $fileinfo[0];
          $height = $fileinfo[1];
          $allowed_extension = array("png","jpg","jpeg");

          $file_extension = strtolower(pathinfo($file[$attributeName]["name"], PATHINFO_EXTENSION));
          
          $response = "";
          // Validate file input to check if is not empty
          if (! file_exists($file[$attributeName]["tmp_name"])) 
          {
               $response = array(
                    "status" => "error",
                    "message" => "Choose image file to upload."
               );
          }    // Validate file input to check if is with valid extension
          else if (! in_array($file_extension, $allowed_extension)) 
          {
               $response = array(
                    "status" => "error",
                    "message" => "Upload valiid images. Only PNG and JPEG are allowed."
               );
          }    
          // Validate image file size
          else if (($file[$attributeName]["size"] > $size)) {
               $response = array(
                    "status" => "error",
                    "message" => "Image size exceeds $size bytes"
               );
          }    
          // Validate image file dimension
          // else if ($width > "300" || $height > "200") {
          //      $response = array(
          //      "type" => "error",
          //      "message" => "Image dimension should be within 300X200"
          //      );
          // } 
          else 
          {
               // $target = "image/" . basename($file[$attributeName]["name"]);
               $imageName = time(). "." .$file_extension;
               $target = $path . $imageName;
               if (move_uploaded_file($file[$attributeName]["tmp_name"], $target)) 
               {
                    $response = array(
                         "status" => "success",
                         "message" => "Image uploaded successfully.",
                         "image" => $imageName,
                    );
               } 
               else 
               {
                    $response = array(
                         "status" => "error",
                         "message" => "Problem in uploading image files."
                    );
               }
          }
     } 
     catch (\Throwable $th) 
     {
          $response = array(
               "status" => "error",
               "message" => $th->getMessage()
          );
     }
     catch (\Exception $ex) 
     {
          $response = array(
               "status" => "error",
               "message" => $ex->getMessage()
          );
     }  
     return $response;       
}

function uploadFile2($file,$index, $attributeName, $path, $allowed_extension = [], $size = 1000000)
{ 
     try 
     {
          $response = "";   
          $target_dir = $path;
          // echo $target_dir;
          $target_file = $target_dir . basename($file[$attributeName]["name"][$index]);
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
          $uploadOk = 1;

          $fileinfo = @getimagesize($file[$attributeName]["tmp_name"][$index]);
          $width = $fileinfo[0];
          $height = $fileinfo[1];
          $allowed_extension = array("png","jpg","jpeg");

          $file_extension = strtolower(pathinfo($file[$attributeName]["name"][$index], PATHINFO_EXTENSION));
          
          $response = "";
          // Validate file input to check if is not empty
          if (! file_exists($file[$attributeName]["tmp_name"][$index])) 
          {
               $response = array(
                    "status" => "error",
                    "message" => "Choose image file to upload."
               );
          }    // Validate file input to check if is with valid extension
          else if (! in_array($file_extension, $allowed_extension)) 
          {
               $response = array(
                    "status" => "error",
                    "message" => "Upload valiid images. Only PNG and JPEG are allowed."
               );
          }    
          // Validate image file size
          else if (($file[$attributeName]["size"][$index] > $size)) {
               $response = array(
                    "status" => "error",
                    "message" => "Image size exceeds $size bytes"
               );
          }    
          // Validate image file dimension
          // else if ($width > "300" || $height > "200") {
          //      $response = array(
          //      "type" => "error",
          //      "message" => "Image dimension should be within 300X200"
          //      );
          // } 
          else 
          {
               // $target = "image/" . basename($file[$attributeName]["name"]);
               $imageName = time()."_".uniqid(). "." .$file_extension;
               $target = $path . $imageName;
               if (move_uploaded_file($file[$attributeName]["tmp_name"][$index], $target)) 
               {
                    $response = array(
                         "status" => "success",
                         "message" => "Image uploaded successfully.",
                         "image" => $imageName,
                    );
               } 
               else 
               {
                    $response = array(
                         "status" => "error",
                         "message" => "Problem in uploading image files."
                    );
               }
          }
     } 
     catch (\Throwable $th) 
     {
          $response = array(
               "status" => "error",
               "message" => $th->getMessage()
          );
     }
     catch (\Exception $ex) 
     {
          $response = array(
               "status" => "error",
               "message" => $ex->getMessage()
          );
     }  
     return $response;       
}


// echo getInvoiceNumber(12);

?>
