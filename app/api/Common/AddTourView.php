<?php
$app->post("/api/common/AddTourView", function ($request) {

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');
    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,0);
        if($valid==true)
        {
            $tourID = $request->getParsedBody()['tourID'];
            //$roleType = $request->gsdaetParsedBody()['roleType'];

            //$validateInput = true;
            if($tourID=="" )
            {
                $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
            } else {

                $sql = "UPDATE tour SET View = View + 1 WHERE TourID = $tourID";


                //success
                if (mysqli_query($mysqli, $sql)) {
                    $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
                } else {
                    $result = array("ReturnCode"=>FAILUPDATERECORDDESC,"ReturnDesc"=>FAILUPDATERECORDDESC,"SqlError"=>$mysqli->error);
                }
            }

        }  else {
            $result = array("ReturnCode"=>INCORRECTTOKEN,"ReturnDesc"=>INCORRECTTOKENDESC);
        }
    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }




    echo json_encode($result);
});

?>
