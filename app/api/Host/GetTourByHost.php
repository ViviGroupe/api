<?php
$app->get('/api/host/GetTourByHost',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,2);
        if($valid==true)
        {
            $hostID = getHostIDByToken($token);
             $query = "SELECT 
tour.tourID, tour.TourPlace,tour.TourDescription,tour.Price,tour.StartTime, tour.EndTime, tour.NumberOfGuest , GROUP_CONCAT(mood.moodName) AS Mood
FROM tour INNER JOIN host ON host.HostID = $hostID AND tour.HostID = $hostID AND tour.ActiveStatus='1' 
INNER JOIN moodtour INNER JOIN mood 
ON moodtour.moodID = mood.MoodID AND moodtour.TourID = tour.TourID
GROUP BY tour.tourID";
            //$query = "SELECT tour.TourPlace,tour.TourDescription,tour.Price, tour.StartDate,tour.EndDate,tour.StartTime, tour.EndTime, tour.NumberOfGuest FROM tour INNER JOIN host ON host.HostID = $hostID AND tour.HostID = $hostID AND tour.ActiveStatus='1'";
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
