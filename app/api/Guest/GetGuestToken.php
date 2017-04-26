<?php
$app->get('/api/guest/GetGuestToken',function($request){
    
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');
    
    $guestToken = createGuestToken();
    if(isset($guestToken)){
    $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Token"=>$guestToken);
    }
    echo json_encode($result);
});

?>