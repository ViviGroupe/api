<?php
$app->post("/api/tourist/AddTourRating", function ($request) {

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');
    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,1);
        if($valid==true)
        {
            $rating = $request->getParsedBody()['rating'];
            $comment = $request->getParsedBody()['comment'];
            $tourID = $request->getParsedBody()['tourID'];
             $date = date("Y-m-d");
            //$userID = $request->getParsedBody()['userID'];
            if($rating=="" || $comment=="" ||$tourID=="")
            {
                $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
            } else {
                $userID = getTouristIDByToken($token);
                $sql = "SELECT * from tourRecord where TourID = $tourID AND TouristID = $userID AND PaymentStatus = '1'";
                $res = $mysqli->query($sql);
                $rowCount = mysqli_num_rows($res);
                if($rowCount>0){
                  
                    $sql = "INSERT INTO rating (Rating, Comment, TourID, UserID, Date, ActiveStatus) VALUES ('$rating', '".mysqli_real_escape_string($mysqli,$comment)."',$tourID,$userID,'$date','1')";


                    //success
                    if (mysqli_query($mysqli, $sql)) {
                        
                        $sql = "SELECT Rating from rating where TourID = $tourID AND ActiveStatus ='1'";
                        $res = $mysqli->query($sql);
                        $rowCount = mysqli_num_rows($res);
                        if($rowCount>0){
                            while($row = $res->fetch_assoc()){
                                $data[] = $row;
                            }
                            
                            $ratingCalculate = calculateRating($data);
                            $AvgRating = $ratingCalculate['AvgRating'];
                            $AvgRatingComm = $ratingCalculate['AvgRatingComm'];
                            $AvgRatingChar = $ratingCalculate['AvgRatingChar'];
                            $AvgRatingVal = $ratingCalculate['AvgRatingVal'];
                           
                             $sql = "UPDATE tour SET AvgRating = $AvgRating, AvgRatingComm = $AvgRatingComm, AvgRatingChar = $AvgRatingChar, AvgRatingVal = $AvgRatingVal WHERE tourID = $tourID";
                             if (mysqli_query($mysqli, $sql)) {
                                $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
                             }
                        } else {
                            $sql = "SELECT * from tour where TourID = $tourID";
                            $res = $mysqli->query($sql);
                            $rowCount = mysqli_num_rows($res);
                            if($rowCount>0){
                                $sql = "UPDATE tour SET AvgRating = $AvgRating, AvgRatingComm = $AvgRatingComm, AvgRatingChar = $AvgRatingChar, AvgRatingVal = $AvgRatingVal WHERE tourID = $tourID";
                                if (mysqli_query($mysqli, $sql)) {
                                    $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
                                }
                            }
                        }
                    } else {
                        $result = array("ReturnCode"=>FAILADDRECORD,"ReturnDesc"=>FAILADDRECORDDESC,"SqlError"=>$mysqli->error);
                    }
                } else {
                     $result = array("ReturnCode"=>NOTELIGIBLEADDRATING,"ReturnDesc"=>NOTELIGIBLEADDRATINGDESC);
                }


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
