<?php
$app->post("/api/host/AddTour", function ($request) {
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
            $airport = $request->getParsedBody()['airport'];
            $hotel_lobby = $request->getParsedBody()['hotel_lobby'];
            $meeting_point = $request->getParsedBody()['meeting_point'];
            $start_time = $request->getParsedBody()['start_time'];
            $end_time = $request->getParsedBody()['end_time'];
            $vehicle = $request->getParsedBody()['vehicle'];
            $number_of_guest = $request->getParsedBody()['number_of_guest'];
            $city = $request->getParsedBody()['city'];
            $state = $request->getParsedBody()['state'];
            $country = $request->getParsedBody()['country'];
            $hostID = getHostIDByToken($token,2);
            $placeID = $request->getParsedBody()['placeID'];
            $vehicle_type = $request->getParsedBody()['vehicle_type'];
            $moodID = $request->getParsedBody()['moodID'];
          //  $tourImage = $request->getParsedBody()['tourImage'];
    if($tour_place=="" || $tour_description=="" || $price=="" || $vehicle=="" ||$airport==""||$number_of_guest == "" ||$city == "" || $state == "" || $country == "" ||$moodID==""){
                $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
            } else {
                //$start_date = str_replace('/', '-', $start_date);
                //$start_date = date('Y-m-d',strtotime($start_date));
                
                //$end_date = str_replace('/', '-', $end_date);
                //$end_date = date('Y-m-d',strtotime($end_date));
                $meeting_point = implode(', ', $meeting_point); 
                //$moodID = implode(', ', $moodID);  
                $start_time = date('H:i:s',strtotime($start_time));
                $end_time = date('H:i:s',strtotime($end_time));
                $sql = "INSERT INTO tour (PlaceID, TourPlace, TourDescription, HostID, Price, StartTime, EndTime, NumberOfGuest, Vehicle, VehicleType, Airport,HotelLobby, MeetingPoint, City, State, Country,  ActiveStatus) VALUES ($placeID, '$tour_place','".mysqli_real_escape_string($mysqli,$tour_description)."','$hostID',$price,'$start_time','$end_time',$number_of_guest,$vehicle,$vehicle_type, $airport,'$hotel_lobby','".mysqli_real_escape_string($mysqli,$meeting_point)."', '$city', '$state', '$country', '1')";
                
                //success
                if (mysqli_query($mysqli, $sql)) {
                    $tourID = mysqli_insert_id($mysqli);
                    $sql = "INSERT INTO moodTour (MoodID, TourID) VALUES ($moodID[0], $tourID);";
                  
                    if(count($moodID)>1){
                        for($i=1;$i<count($moodID);$i++){
                        
                            $sql .= "INSERT INTO moodTour (MoodID, TourID) VALUES ($moodID[$i], $tourID);";

                        }
                    }
                    
                    
                    if( mysqli_multi_query($mysqli, $sql)){
                         $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC, "TourID"=>$tourID);
                    }
                   

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
