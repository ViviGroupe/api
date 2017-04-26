<?php
$app->post("/api/host/DisableTourDate", function ($request) {
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,2);
        if($valid==true)
        {
            $tourID = $request->getParsedBody()['tourID'];
            $disable_date = $request->getParsedBody()['disable_date'];
           
    if($tourID=="" || $disable_date=="" ){
                $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
            } else {
                $disable_date = implode(',', $disable_date); 
                $sql = "UPDATE Tour SET DisableDate = '$disable_date' WHERE TourID=$tourID";

                //success
                if (mysqli_query($mysqli, $sql)) {
                    $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);

                } else {
                    $result = array("ReturnCode"=>FAILADDRECORD,"ReturnDesc"=>FAILADDRECORDDESC,"SqlError"=>$mysqli->error);
                }
            }
        } else {
            $result = array("ReturnCode"=>INCORRECTTOKEN,"ReturnDesc"=>INCORRECTTOKENDESC);
        }
    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }


    //echo
    //$date = date('d-m-Y',strtotime($date));
    echo json_encode($result);
});
?>
