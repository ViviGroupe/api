<?php
$app->get('/api/common/GetTourImage',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');


    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,0);
        if($valid==true)
        {
            $tourID = $request->getQueryParams()['tourID'];
            //$userID = getUserIDByToken($token);
            $folderDirectory = TOURPLACEIMAGEPATH.$tourID;
            $files = glob($folderDirectory.'/*'); // get all file names
                    
            
            
            $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$files);
            

        } else {
            $result = array("ReturnCode"=>SESSIONEXPIRED,"ReturnDesc"=>SESSIONEXPIREDDESC);
        }

    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }

    echo json_encode($result);
});
?>
