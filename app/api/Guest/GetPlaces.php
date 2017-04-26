<?php
$app->get('/api/guest/GetPlaces',function($request){

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    
    //GET MAX GETPLACES LIMIT
    $maxGetPlacesQuery = "SELECT MaxGetPlaces FROM commonhelper";
    $resultMaxGetPlaces = $mysqli->query($maxGetPlacesQuery);
    $maxGetPlaces = mysqli_fetch_assoc($resultMaxGetPlaces);
    
    //GET PLACES
    $getPlacesQuery = "SELECT PlaceName,PlaceDescription,PlaceImage FROM places ORDER BY AvgView DESC LIMIT ".$maxGetPlaces['MaxGetPlaces'];
    $resultGetPlaces = $mysqli->query($getPlacesQuery);
    while($row = $resultGetPlaces->fetch_assoc()){
        $data[] = $row;
    }
    foreach ($data as &$places) {
        
            $places['PlaceImage'] = DOMAINPATH.$places['PlaceImage'];
        
    }

    if(isset($data)){
        $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$data);
        //echo json_encode($result);
    } else {
        $result = array("ReturnCode"=>FAIL,"ReturnDesc"=>FAILDESC);
        //echo json_encode($result);
    }

    echo json_encode($result);

});
?>
