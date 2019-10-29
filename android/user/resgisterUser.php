
<?php

/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['re_password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['re_password'];

    // include db connect class
    require_once ('../dbconfig.php');
    require_once ('validate.php');

    // connecting to db
    $database = new Database();
    $db=$database->getDbConnection();

    $validate=new Validate($email,$password,$password2);


    if($validate->verifyPassword()){

      if($validate->createUser($db)){
          $validate->echoResult(true);
      }else{
        $validate->echoResult(false,'Cannot Create');
      }
    }
    else{
      $validate->echoResult(false,'Verify Password Error');
    }
  }
    // mysql inserting a new row
  //  $result = mysql_query("INSERT INTO testtable(id, email, password) VALUES ($email,$password)");
//     $result = mysql_query("INSERT INTO testtable(id, email, password) VALUES (null,'$email', '$password')");
//     // check if row inserted or not
//     if ($result) {
//         // successfully inserted into database
//         $response["success"] = 1;
//         $response["message"] = "Product successfully created.";
//
//         // echoing JSON response
//         echo json_encode($response);
//     } else {
//         // failed to insert row
//         $response["success"] = 0;
//         $response["message"] = "Oops! An error occurred.";
//
//         // echoing JSON response
//         echo json_encode($response);
//     }
// } else {
//     // required field is missing
//     $response["success"] = 0;
//     $response["message"] = "Required field(s) is missing";
//
//     // echoing JSON response
//     echo json_encode($response);
// }
?>
