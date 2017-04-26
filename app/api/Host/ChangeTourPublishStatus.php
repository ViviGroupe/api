<?php
$app->post('/api/host/ChangeTourPublishStatus',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');


    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,2);
        if($valid==true)
        {

            $tourID = $request->getParsedBody()['tourID'];
            $publishStatus = $request->getParsedBody()['publish_status'];
            if($tourID==""){
                $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
            } else {
                $query = "UPDATE tour SET PublishStatus=$publishStatus WHERE TourID=$tourID";
                //echo $query;
                
                if (mysqli_query($mysqli, $query)) {
                  
                             $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
                        
                    
                    
                   
                    
                } else {
                    //echo $sql;
                    $result = array("ReturnCode"=>FAILUPDATERECORD,"ReturnDesc"=>FAILUPDATERECORDDESC,"SqlError"=>$mysqli->error);
                }

            }

        } else {
            $result = array("ReturnCode"=>SESSIONEXPIRED,"ReturnDesc"=>SESSIONEXPIREDDESC);
        }

    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }

    echo json_encode($result);
});
?>
