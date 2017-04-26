<?php
$app->get('/api/common/GetTourRatingList', function($request){
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
            $query = "SELECT tourist.FirstName, tourist.LastName, rating.Comment, rating.Rating FROM tourist INNER JOIN rating ON rating.UserID = tourist.TouristID WHERE rating.TourID = $tourID AND rating.ActiveStatus ='1' ";
            //echo $query;
            $res = $mysqli->query($query);
            $rowCount = mysqli_num_rows($res);
            if($rowCount==0){
                $result = array("ReturnCode"=>ZERORECORD,"ReturnDesc"=>ZERORECORDDESC);
            } else {
                while($row = $res->fetch_assoc()){
                $data[] = $row;
                    //var_dump($row);
            }
                //var_dump($data);
            $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$data);
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
