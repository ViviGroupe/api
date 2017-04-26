<?php
$app->post("/api/host/SignUp", function ($request) {

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');
    
    $email = $request->getParsedBody()['email'];
    $password = $request->getParsedBody()['password'];
    
    if($email=="" || $password=="" )
    {
        $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
    } else {
        $password = hash('sha512', $password);
        $valid = true;
        $query = "select * from host";
        $resultQuery = $mysqli->query($query);
        while($row =  $resultQuery->fetch_assoc()){
            if($row['Email'] == $email ){
                $valid = false;
            }
        }

        if($valid==false){
            $result = array("ReturnCode"=>EMAILEXIST,"ReturnDesc"=>EMAILEXISTDESC);
        } else {

                $sql = "INSERT INTO host (Email, Password, VerificationStatus, Category, CommissionPercentage, ActiveStatus ) VALUES ('$email', '$password','0','1','5','0')";


            //success
            if (mysqli_query($mysqli, $sql)) {

                $query = "select * from host WHERE Email = '$email'";
                $resultQuery = $mysqli->query($query);
                while($row =  $resultQuery->fetch_assoc()){
                    $hostId = $row['HostID'];
                }

                $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);


                sendMailActivation($email,$hostId, 2);
            } else {
                $result = array("ReturnCode"=>FAILADDRECORD,"ReturnDesc"=>FAILADDRECORDDESC,"SqlError"=>$mysqli->error);
            }
        }

    }



    echo json_encode($result);
});

?>
