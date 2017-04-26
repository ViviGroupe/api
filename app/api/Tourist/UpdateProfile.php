<?php
$app->post('/api/tourist/UpdateProfile',function($request){
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,1);
        if($valid==true)
        {
            $userID = getTouristIDByToken($token,1);
            $data = $request->getParsedBody();
            if(isset( $data['language'])){
                $language = $data['language'];
            }
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $profile_image = $data['profile_image'];
            $contact = $data['contact'];
            //$pitch = $data['pitch'];
            
        
            

            
            //$imageDBPathLocal = imageDBPathLocal($imageDirectory['imageDirectory']);




            //get profile image see whether is null or exist
            $query = "SELECT ProfileImage from tourist WHERE TouristID = $userID";
            $result = $mysqli->query($query);
            while($row = $result->fetch_assoc()){
                $data = $row;
            }
            
            //if profile image exist update profile image else update info without profile image
            if($profile_image!=""){
                $imageDirectory = imageFolderPath($profile_image,PLACEIMAGEPATH);
                $imageToDBURL = imageDBPath($imageDirectory['imageDirectory']);
                if($data["ProfileImage"]==""){
                    $query = "UPDATE tourist SET ProfileImage='$imageToDBURL', FirstName = '$first_name', LastName = '$last_name', Contact = '$contact' WHERE TouristID = $userID";
                }
                else {
                    $query = "UPDATE tourist SET FirstName = '$first_name', LastName = '$last_name', Contact = '$contact' WHERE TouristID = $userID";
                }
            } else {
                 $query = "UPDATE tourist SET FirstName = '$first_name', LastName = '$last_name', Contact = '$contact' WHERE TouristID = $userID"; 
            }

            //run query update info except profile image and language
            if (mysqli_query($mysqli, $query)) {
                //insert profile image only if profile image exist
                if($profile_image!=""){
                    if($data["ProfileImage"]==""){
                         file_put_contents($imageDirectory['imageDirectory'], $imageDirectory['image']);
                    } else {
                        //echo ;
                         file_put_contents($data["ProfileImage"], $imageDirectory['image']);
                    }
                }
                
                //update language
                if(isset($language)){
                    $sqlDeleteLanguage = "DELETE FROM userlanguage WHERE UserID = $userID AND UserType=1";
                    if (mysqli_query($mysqli, $sqlDeleteLanguage)) {
                        $sql =null;
                        //echo $language;
                        for($i=0;$i<COUNT($language);$i++){
                            $sql.= "INSERT INTO userlanguage (Language, UserID, UserType) VALUES ('$language[$i]', $userID, 1);";
                            //echo $language[$i];
                        }
                        //echo $sql;

                        if (mysqli_multi_query($mysqli, $sql)) {
                            $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
                        } else {
                            $result = array("ReturnCode"=>FAILADDRECORD,"ReturnDesc"=>FAILADDRECORDDESC,"SqlError"=>$mysqli->error);
                        }
                    } else {
                        $result = array("ReturnCode"=>FAILADDRECORD,"ReturnDesc"=>FAILADDRECORDDESC,"SqlError"=>$mysqli->error);
                    }
                } else {
                    //if no language, return success
                    $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
                }


            } else {
                $result = array("ReturnCode"=>FAILUPDATERECORD,"ReturnDesc"=>FAILUPDATERECORDDESC,"SqlError"=>$mysqli->error);
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
