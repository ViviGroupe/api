<?php
$app->post("/api/tourist/SignUp", function ($request) {

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');
    
    $email = $request->getParsedBody()['email'];
    $first_name = $request->getParsedBody()['first_name'];
    $last_name = $request->getParsedBody()['last_name'];
    $birth_date = $request->getParsedBody()['birth_date'];
    $password = $request->getParsedBody()['password'];
    if($email=="" || $password=="" ||$birth_date=="" || $first_name==""|| $last_name=="")
    {
        $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
    } else {
        $birth_date = str_replace('/', '-', $birth_date);
        $birth_date = date('Y-m-d',strtotime($birth_date));
        $password = hash('sha512', $password);
        $valid = true;
        $query = "select * from tourist";
        $resultQuery = $mysqli->query($query);
        while($row =  $resultQuery->fetch_assoc()){
            if($row['Email'] == $email ){
                $valid = false;
            }
        }

        if($valid==false){
            $result = array("ReturnCode"=>EMAILEXIST,"ReturnDesc"=>EMAILEXISTDESC);
        } else {

                $sql = "INSERT INTO tourist (Email, Password, FirstName, LastName, BirthDate, VerificationStatus, ActiveStatus ) VALUES ('$email','$password','$first_name','$last_name','$birth_date','0','0')";


            //success
            if (mysqli_query($mysqli, $sql)) {

                $query = "select * from tourist WHERE Email = '$email'";
                $resultQuery = $mysqli->query($query);
                while($row =  $resultQuery->fetch_assoc()){
                    $touristId = $row['TouristID'];
                }

                $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);


                sendMailActivation($email,$touristId, 1);
            } else {
                $result = array("ReturnCode"=>FAILADDRECORD,"ReturnDesc"=>FAILADDRECORDDESC,"SqlError"=>$mysqli->error);
            }
        }

    }



    echo json_encode($result);
});

?>
