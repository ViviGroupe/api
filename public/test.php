<?php
/*session_start();
$_SESSION['abc'] = "3";
echo json_encode($_SESSION['abc']);*/
$date = '14:30';
$date = str_replace('/', '-', $date);
echo(date('H:i:s',strtotime($date)));
/* require_once('status.php');
    require_once('common.php');
    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token);
        if($valid==true)
        {
            echo 'done';
        }
    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }
    echo json_encode($result);*/

?>
