<?php
$app->post('/api/tourist/SearchTourList',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,1);
        if($valid==true)
        {
            $recordSize = $request->getParsedBody()['record_size'];
            $pageOffset = $request->getParsedBody()['page_offset'];
            
            $tour_place = $request->getParsedBody()['tour_place'];
            $start_date = $request->getParsedBody()['start_date'];
            $end_date = $request->getParsedBody()['end_date'];
            $city = $request->getParsedBody()['city'];
            $country = $request->getParsedBody()['country'];
            $vehicle = $request->getParsedBody()['vehicle'];
            $number_of_guest = $request->getParsedBody()['number_of_guest'];
            $airport = $request->getParsedBody()['airport'];
            $language = $request->getParsedBody()['language'];
            $sortOption = $request->getParsedBody()['sortOption'];
            $moodID = $request->getParsedBody()['moodID'];
            $moodID = implode(', ',$moodID);
            
            $dataStart = ($pageOffset-1)*$recordSize;
            $searchQuery = "SELECT tour.TourID,tour.TourPlace, tour.DisableDate, tour.TourDescription, tour.Vehicle, tour.Airport, tour.NumberOfGuest, tour.Price, tour.StartTime, tour.EndTime,  tour.NumberOfGuest, tour.MeetingPoint, tour.City, tour.State, tour.Country, tour.AvgRating, tour.View, host.FirstName, host.LastName, host.Language, host.ProfileImage, host.Pitch, GROUP_CONCAT(mood.moodName) AS Mood FROM moodtour JOIN tour ON moodtour.TourID = tour.TourID JOIN mood ON mood.MoodID = moodtour.MoodID INNER JOIN host WHERE tour.HostID = host.HostID AND moodtour.MoodID IN ($moodID) ";
            //$cibai = $request->getParsedBody()['vehicle'];
            $query =  $searchQuery."AND tour.TourPlace LIKE '%$tour_place%'  AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID";
            $res = $mysqli->query($query);
            $rowCount = mysqli_num_rows($res);
            $totalPage = ceil($rowCount/(int)$recordSize);
            $query =  $searchQuery."AND tour.TourPlace LIKE '%$tour_place%'  AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID LIMIT $dataStart, $recordSize ";
            
            
            
                
            $res = $mysqli->query($query);
            $rowCount = mysqli_num_rows($res);
           
            
            if($rowCount==0){
           
                
                //$result = array("ReturnCode"=>ZERORECORD,"ReturnDesc"=>ZERORECORDDESC);
                
                $query = $searchQuery."AND tour.City LIKE '%$city%'  AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID";
                $res = $mysqli->query($query);
                $rowCount = mysqli_num_rows($res);
                $totalPage = ceil($rowCount/(int)$recordSize);
                
                
                $query = $searchQuery."AND tour.City LIKE '%$city%'  AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID LIMIT $dataStart, $recordSize ";
                $res = $mysqli->query($query);
                $rowCount = mysqli_num_rows($res);
                $totalPage = ceil($rowCount/(int)$recordSize);
                if($rowCount==0){
                    $query = $searchQuery."AND tour.Country LIKE '%$country%'  AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID";
                    $res = $mysqli->query($query);
                    $rowCount = mysqli_num_rows($res);
                    $totalPage = ceil($rowCount/(int)$recordSize);
                    
                    $query = $searchQuery."AND tour.Country LIKE '%$country%'  AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID LIMIT $dataStart, $recordSize";
                    $res = $mysqli->query($query);
                    $rowCount = mysqli_num_rows($res);
                    //$totalPage = ceil($rowCount/(int)$recordSize);
                    if($rowCount==0){
                        $query = $searchQuery."AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID";
                        $res = $mysqli->query($query);
                        $rowCount = mysqli_num_rows($res);
                        $totalPage = ceil($rowCount/(int)$recordSize);
                        
                        $query = "AND tour.ActiveStatus='1' AND host.ActiveStatus='1' group by tour.tourID LIMIT $dataStart, $recordSize ";
           
                        $res = $mysqli->query($query);
                        $rowCount = mysqli_num_rows($res);
                        $totalPage = ceil($rowCount/(int)$recordSize);
                        while($row = $res->fetch_assoc()){
                            $result[] = $row;
                        }
                        $data = seperateAvailabilityTour($start_date, $end_date, $result, $vehicle, $airport, $number_of_guest, $language, $sortOption);
                    //var_dump($data);
                        $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$data, "TotalPage"=>$totalPage);
                        
                        if($rowCount==0){
                            
                            $result = array("ReturnCode"=>ZERORECORD,"ReturnDesc"=>ZERORECORDDESC);
                        }
                        
                        
                    }else {
                        while($row = $res->fetch_assoc()){
                            $result[] = $row;
                        }
                        $data = seperateAvailabilityTour($start_date, $end_date, $result,$vehicle, $airport, $number_of_guest, $language, $sortOption);
                    //var_dump($data);
                        $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$data, "TotalPage"=>$totalPage);
                    }
                }else {
                    while($row = $res->fetch_assoc()){
                        $result[] = $row;
                    }
                    
                    $data = seperateAvailabilityTour($start_date, $end_date, $result, $vehicle, $airport, $number_of_guest, $language, $sortOption);
                //var_dump($data);
                    $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$data, "TotalPage"=>$totalPage);
                }
            } else {
                while($row = $res->fetch_assoc()){
                    $result[] = $row;
                }
                
                $data = seperateAvailabilityTour($start_date, $end_date, $result, $vehicle, $airport, $number_of_guest, $language, $sortOption);
                //var_dump($data);
                $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$data, "TotalPage"=>$totalPage);
            }
            
        } else {
            $result = array("ReturnCode"=>SESSIONEXPIRED,"ReturnDesc"=>SESSIONEXPIREDDESC);
        }

    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }

    echo json_encode($result);
});

/*$app->get('/api/GetTopTour',function($request){
    require_once('dbConnect.php');
    require_once('status.php');
    require_once('common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token);
        if($valid==true)
        {

            $query = "SELECT COUNT(*) AS count,TourPlace AS test from tour GROUP BY TourPlace ORDER BY count DESC LIMIT 4";
            //$end_data."%";
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
});*/
?>
