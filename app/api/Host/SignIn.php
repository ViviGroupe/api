<?php
$app->post("/api/host/SignIn", function ($request) {

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    $email = $request->getParsedBody()['email'];
    $password = $request->getParsedBody()['password'];
    //$roleName = $request->getParsedBody()['roleName'];

    if($email=="" || $password==""){
        $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
    } else {
        $password = hash('sha512', $password);


        $query = "select * from host WHERE Email = '$email' AND Password = '$password'";
        $res = $mysqli->query($query);

        $rowCount = mysqli_num_rows($res);

        if($rowCount>0){
            while($row = $res->fetch_assoc()){
                $data = $row;
            }
            if($data['VerificationStatus']!="1"){
                $result = array("ReturnCode"=>NOACTIVATE,"ReturnDesc"=>NOACTIVATEDESC);
            } else {
                $token = createToken($data["HostID"],'2');
                if($token!=null){
                    $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Token"=>$token);
                }

            }
        } else {
            $result = array("ReturnCode"=>CREDENTIALERROR,"ReturnDesc"=>CREDENTIALERRORDESC);
        }
    }


    //echo $rowCount;
    echo json_encode($result);
});

?>
