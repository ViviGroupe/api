<?php
$app->get('/api/tourist/GetProfile',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,1);
        if($valid==true)
        {
            $touristID = getTouristIDByToken($token);

            $query = "SELECT Email, ProfileImage, FirstName, LastName, Contact FROM tourist WHERE touristID = $touristID";
            //$end_data."%";
            //echo $query;
            $res = $mysqli->query($query);

            while($row = $res->fetch_assoc()){
                $data = $row;
            }
            if($data["ProfileImage"]!=null){
                $data["ProfileImage"] = DOMAINPATH.$data["ProfileImage"];
            }
            //$query = "SELECT Language FROM userlanguage WHERE UserID = $touristID AND UserType=1";
            /*$res = $mysqli->query($query);
            $rowCount = mysqli_num_rows($res);
            if($rowCount>0){
                while($row = $res->fetch_assoc()){
                $language[] = $row;
                }
               
                    $data['Language'] =$language;
                
            }else {
                $data['Language'] = null;
                
            }*/
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
