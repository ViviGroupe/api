<?php
$app->get('/api/common/GetTourDetail',function($request){
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
            $query = "SELECT host.FirstName, host.LastName, host.Pitch, tour.*,GROUP_CONCAT(mood.MoodName) AS Mood 
FROM tour INNER JOIN moodtour INNER JOIN mood INNER JOIN host
ON host.HostID = tour.HostID AND tour.TourID = $tourID  AND moodtour.MoodID = mood.MoodID AND tour.TourID = moodtour.TourID
GROUP BY tour.tourID";
           // $query = "SELECT tour.*,GROUP_CONCAT(mood.MoodName) AS Mood FROM tour ON TourID = $tourID join moodtour join mood ON moodtour.moodID = mood.MoodID AND moodtour.TourID = tour.TourIDGROUP BY tour.tourID";
            //echo $query;
            $res = $mysqli->query($query);
            $rowCount = mysqli_num_rows($res);
            if($rowCount==0){
                $result = array("ReturnCode"=>ZERORECORD,"ReturnDesc"=>ZERORECORDDESC);
            } else {
                while($row = $res->fetch_assoc()){
                $data[] = $row;
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
