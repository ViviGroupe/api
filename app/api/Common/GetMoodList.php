<?php
$app->get("/api/common/GetMoodList", function ($request) {

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');
    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,0);
        if($valid==true)
        {
                $query = "SELECT * FROM mood";
             
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
            

        }  else {
            $result = array("ReturnCode"=>INCORRECTTOKEN,"ReturnDesc"=>INCORRECTTOKENDESC);
        }
    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }




    echo json_encode($result);
});

?>
