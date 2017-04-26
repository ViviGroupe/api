<?php
$app->get('/api/host/GetProfile',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,2);
        if($valid==true)
        {
            $hostID = getHostIDByToken($token);

            $query = "SELECT Email, ProfileImage, FirstName, LastName, Contact, Pitch FROM host WHERE HostID = $hostID";
            //$end_data."%";
            //echo $query;
            $res = $mysqli->query($query);

            while($row = $res->fetch_assoc()){
                $data = $row;
            }

            if($data["ProfileImage"]!=null){
                $data["ProfileImage"] = DOMAINPATH.$data["ProfileImage"];
            }
            /*$query = "SELECT Language FROM userlanguage WHERE UserID = $hostID AND UserType=2";
            $res = $mysqli->query($query);
            $rowCount = mysqli_num_rows($res);
            if($rowCount>0){
                while($row = $res->fetch_assoc()){
                $language[] = $row;
                }
               
                    $data['Language'] =$language;
                
            }else {
                $data['Language'] = null;
                
            }
            */
            
            $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC,"Data"=>$data);

        }else {
            $result = array("ReturnCode"=>SESSIONEXPIRED,"ReturnDesc"=>SESSIONEXPIREDDESC);
        }

    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }

    echo json_encode($result);
});
?>
