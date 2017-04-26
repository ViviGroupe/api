<?php
$app->get('/api/EmailActivation',  function ($request) {
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    $userID = $request->getParam('userID');
    $userType = $request->getParam('userType');
    if($userType==1){
        $query = "select COUNT(*) as total from tourist WHERE TouristID = '$userID' AND VerificationStatus=1";
    } else {
        $query = "select COUNT(*) as total from host WHERE HostID = '$userID' AND VerificationStatus=1";
    }
    
    $res = $mysqli->query($query);
    $row =  $res->fetch_assoc();

    if ( $row['total']> 0) {
        $result = array("ReturnCode"=>ACTIVATED,"ReturnDesc"=>ACTIVATEDDESC);
    } else {
        if($userType==1){
            $sql = "UPDATE tourist  SET VerificationStatus='1', ActiveStatus = '1' WHERE touristID='$userID'";
        } else {
            $sql = "UPDATE host SET VerificationStatus='1',ActiveStatus = '1' WHERE hostID='$userID'";
        }
        
        if (mysqli_query($mysqli, $sql)) {
            $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);

        } else {
            $result = array("ReturnCode"=>FAILACTIVATION,"ReturnDesc"=>FAILACTIVATIONDESC,"SqlError"=>$mysqli->error);
        }
    }


    echo json_encode($result);
});
?>
