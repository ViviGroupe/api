<?php
$app->post('/api/host/EditTour',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');


    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,2);
        if($valid==true)
        {
            $tour_place = $request->getParsedBody()['tour_place'];
            $tour_description = $request->getParsedBody()['tour_description'];
            $price = $request->getParsedBody()['price'];
            $start_time = $request->getParsedBody()['start_time'];
            $end_time = $request->getParsedBody()['end_time'];
            $number_of_guest = $request->getParsedBody()['number_of_guest'];
            $tourID = $request->getParsedBody()['tourID'];
            $airport = $request->getParsedBody()['airport'];
            $hotel_lobby = $request->getParsedBody()['hotel_lobby'];
            $meeting_point = $request->getParsedBody()['meeting_point'];
            $vehicle = $request->getParsedBody()['vehicle'];
            $city = $request->getParsedBody()['city'];
            $state = $request->getParsedBody()['state'];
            $country = $request->getParsedBody()['country'];
            $vehicle_type = $request->getParsedBody()['vehicle_type'];
            $moodID = $request->getParsedBody()['moodID'];
            if($tour_place=="" || $tour_description=="" || $price=="" ||$start_time=="" || $end_time=="" ||$airport==""||$hotel_lobby==""||$vehicle==""|| $city == "" || $state == "" || $country == ""||$number_of_guest==""||$moodID==""){
                $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
            } else {
                
                $meeting_point = implode(', ', $meeting_point); 
                $start_time = date('H:i:s',strtotime($start_time));
                $end_time = date('H:i:s',strtotime($end_time));
                $query = "UPDATE tour SET TourPlace='$tour_place',TourDescription='".mysqli_real_escape_string($mysqli,$tour_description)."',Price=$price,StartTime='$start_time', EndTime = '$end_time',Airport = $airport, HotelLobby= $hotel_lobby, MeetingPoint= '".mysqli_real_escape_string($mysqli,$meeting_point)."', Vehicle=$vehicle, VehicleType = $vehicle_type, City='$city', State='$state', Country='$country', NumberOfGuest = $number_of_guest WHERE TourID=$tourID";
                //echo $query;
                
                if (mysqli_query($mysqli, $query)) {
                    $sql = "DELETE FROM moodTour WHERE TourID = $tourID";
                    if (mysqli_query($mysqli, $sql)) {
                        $sql = "INSERT INTO moodTour (MoodID, TourID) VALUES ($moodID[0], $tourID);";
                       
                        if(count($moodID)>1){
                            for($i=1;$i<count($moodID);$i++){

                                $sql .= "INSERT INTO moodTour (MoodID, TourID) VALUES ($moodID[$i], $tourID);";

                            }
                        }


                        if( mysqli_multi_query($mysqli, $sql)){
                             $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
                        }
                    
                    }
                   
                    
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
