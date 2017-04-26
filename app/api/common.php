<?php
function calculateRating($data){
    $totalRatingTotal = 0;
    $totalRatingAvg = 0;
    $totalRatingComm = 0;
    $totalRatingChar = 0;
    $totalRatingVal = 0;
    for($i=0;$i<count($data);$i++){
        $ratingValues = array_map('intval', explode(', ', $data[$i]["Rating"]));
        $totalRatingTotal = array_sum($ratingValues);
        $totalRatingAvg += $totalRatingTotal/3;
        $totalRatingComm += $ratingValues[0];
        $totalRatingChar += $ratingValues[1];
        $totalRatingVal += $ratingValues[2];
    }

    $AvgRating = $totalRatingAvg/count($data) ;
    $AvgRatingComm = $totalRatingComm/count($data);
    $AvgRatingChar = $totalRatingChar/count($data);
    $AvgRatingVal = $totalRatingVal/count($data);
    
    $result["AvgRating"] = $AvgRating;
    $result["AvgRatingComm"] = $AvgRatingComm;
    $result["AvgRatingChar"] = $AvgRatingChar;
    $result["AvgRatingVal"] = $AvgRatingVal;
    
    return $result;
}
function seperateAvailabilityTour($start_date, $end_date, $result, $vehicle, $airport, $number_of_guest, $language, $sortOption){
    
    $days = $end_date - $start_date + 1;
    $dates[] = getDateArray($start_date, $end_date);
    $available = [];
    $notAvailable = [];
    
    for($i=0;$i<count($result);$i++){ //loop whole data
        
        $valid = true;
        
        if($vehicle!=""){
            if($vehicle!=$result[$i]['Vehicle']){
                $valid = false;
            }
        } 
        
        if($airport!=""){
            if($airport!=$result[$i]['Airport']){
                $valid = false;
            }
        }
        
        if($number_of_guest!=""){
            if($number_of_guest!=$result[$i]['NumberOfGuest']){
                $valid = false;
            }
        }
        
        if($result[$i]['DisableDate']!=""){ //validate if got disable date
            $disableDate[] = explode(",", $result[$i]['DisableDate']); 

            if (array_intersect($disableDate[0], $dates[0])) {
                   $valid = false;
            }
        } 
        
        if($result[$i]['Language']!=""){ //validate if got disable date
            $langauges[] = explode(",", $result[$i]['Language']); 
            $languageFromTourist[] = $language;
            if (array_intersect($languageFromTourist, $langauges[0])) {
                  
            } else {
                 $valid = false;
            }
        } 
        
        if($valid){
            
            array_push($available,$result[$i]);
        }else {
            array_push($notAvailable,$result[$i]);
        }
       
    }
    $availableSorted = sortData($available, $sortOption);
    $notAvailableSorted = sortData($notAvailable, $sortOption);
    $data['available'] = $availableSorted;
    $data['notAvailable'] = $notAvailableSorted;

    return $data;
}

function sortData($data, $sortOption){
    //0 = default
    //1 = low to high price
    //2 = high to low price
    //3 = high to low rating
    //4 = high to low view
    //5 = A-Z
    //6 = Z-A
    $sortOption = (int)$sortOption;
    switch ($sortOption) {
    case 0:
        $dataSorted = $data;
        break;
    case 1:
        $dataSorted = array_msort($data, array('Price'=>SORT_ASC));
        break;
    case 2:
        $dataSorted = array_msort($data, array('Price'=>SORT_DESC));
        break;
    case 3:
        $dataSorted = array_msort($data, array('AvgRating'=>SORT_ASC));
        break;
    case 4:
       $dataSorted = array_msort($data, array('View'=>SORT_ASC));
       break;
    case 5:
       $dataSorted = array_msort($data, array('TourPlace'=>SORT_ASC));
       break;
    case 6:
       $dataSorted = array_msort($data, array('TourPlace'=>SORT_DESC));
       break;
    }
    /*if($sortOption=="0"){
        
    } else if ($sortOption=="1"){
        $dataSorted = array_msort($data, array('Price'=>SORT_ASC));
    } else if ($sortOption=="2"){
        $dataSorted = array_msort($data, array('Price'=>SORT_DESC));
    } else if ($sortOption=="3") {
        $dataSorted = array_msort($data, array('Price'=>SORT_DESC));
    }*/
    
    return $dataSorted;
}

function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    //$i=0;
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
                array_push($ret, $array[$k]);
        }
    }
    return $ret;

}

function getDateArray($start, $end, $format = 'd/m/Y') {
    
    $start = str_replace('/', '-', $start);
    $start = date('Y-m-d',strtotime($start));
    
    $end = str_replace('/', '-', $end);
    $end = date('Y-m-d',strtotime($end));
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) { 
        $array[] = $date->format($format); 
    }

    return $array;
}
function createToken($userID,$userType){

    include('../app/api/dbConnect.php');
    //require_once('status.php');
    $query = "SELECT * FROM token where userID=$userID AND userType=$userType";
    $res = $mysqli->query($query);
    $rowCount = mysqli_num_rows($res);
    if($rowCount>0){
        while($row = $res->fetch_assoc()){
            $data = $row;
        }
        $time = time();
        if($time>$data['ExpireTime']){
            $expireTime = time() + (120*60);
            $token = substr(md5(uniqid(mt_rand(), true)), 0, 16);
            $sql = "UPDATE Token SET Token = '$token',Expiretime = $expireTime WHERE UserID = $userID AND UserType = $userType";

            //success
            if (mysqli_query($mysqli, $sql)) {
                return $token;
            } else {
                //echo $sql;
                echo $mysqli->error;
            }
        } else {
            while($row = $res->fetch_assoc()){
                $data = $row;
            }

            return $data["Token"];
        }
    } else {
        $expireTime = time() + (120*60);
        $token = substr(md5(uniqid(mt_rand(), true)), 0, 16);
        $sql = "INSERT INTO Token (Token, ExpireTime, UserID, UserType) VALUES ('$token', '$expireTime','$userID',$userType)";

        //success
        if (mysqli_query($mysqli, $sql)) {
            return $token;
        } else {
            //return false;
            echo $mysqli->error;
        }
    }
}

function createGuestToken(){

    include('../app/api/dbConnect.php');
    //require_once('status.php');


    $expireTime = time() + (120*60);
    $guestToken = substr(md5(uniqid(mt_rand(), true)), 0, 16);
    $sql = "INSERT INTO Token (Token, ExpireTime, UserType) VALUES ('$guestToken', '$expireTime', '3')";

    //success
    if (mysqli_query($mysqli, $sql)) {
        return $guestToken;
    } else {
        //return false;
        echo $mysqli->error;
    }

}


function validateToken($token,$userType){
    include('dbConnect.php');
    if($userType!=0){
        $query = "SELECT * FROM token WHERE Token='$token' AND UserType='$userType'";
    }else {
        $query = "SELECT * FROM token WHERE Token='$token'";
        
    }
    
    $res = $mysqli->query($query);
    $rowCount = mysqli_num_rows($res);
    if($rowCount>0){
        while($row = $res->fetch_assoc()){
            $data = $row;
        }
        $time = time();
        if($time>$data['ExpireTime']){

            return false;
        } else {

            return true;
        }
    }
}

function getHostIDByToken($token){
    include('dbConnect.php');

    $query = "SELECT * FROM host INNER JOIN token ON token.Token='$token' AND token.UserType=2 AND host.HostID = token.UserID";
    $res = $mysqli->query($query);
    $rowCount = mysqli_num_rows($res);
    if($rowCount>0){
        while($row = $res->fetch_assoc()){
            $data = $row;
        }
        //echo $data['UserID'];
        //echo json_encode($data);
        // echo $query;
        return $data['HostID'];
    }
}

function getTouristIDByToken($token){
    include('dbConnect.php');

    $query = "SELECT * FROM tourist JOIN token ON token.Token='$token' AND token.UserType=1 AND tourist.touristID = token.UserID";
    $res = $mysqli->query($query);
    $rowCount = mysqli_num_rows($res);
    if($rowCount>0){
        while($row = $res->fetch_assoc()){
            $data = $row;
        }
        //echo $data['UserID'];
        //echo json_encode($data);
        // echo $query;
        return $data['TouristID'];
    }
}



function curPageURL()
{

    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/images/";
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"]."/images/";
    }
    return $pageURL;
}


function imageFolderPath($image, $placeImagePath){

    require_once('status.php');

    list($type, $image) = explode(';', $image);
    list(, $image)      = explode(',', $image);
    $image = base64_decode(str_replace(' ','+',$image));

    $imageDirectory = $placeImagePath.'/'.substr(uniqid('', true), -5).".png";
    //echo $imageDirectory;
    $data['imageDirectory'] = $imageDirectory;
    $data['image'] = $image;
    return $data;
}


function imageDBPath($path){
    require_once('status.php');
    $imageToDBURL = substr($path, 10);
    return $imageToDBURL;
}

function sendMailActivation($to,$userId,$userType){
    require_once('status.php');

    $subject = 'Welcome';
    $message = '<html><body>';
    $message .= "Please click <h1>".DOMAINPATH."api/EmailActivation?userID=$userId&userType=$userType</h1> for email activation
                </body></html>";
    $mail = new PHPMailer;
    $mail->isSMTP();            
    //Set SMTP host name                          
    $mail->Host = "smtp.zoho.com";
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;                          
    //Provide username and password     
    $mail->Username = "vivicrew@iotadev.com";                 
    $mail->Password = 'P@$$w0rd';                           
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";                           
    //Set TCP port to connect to 
    $mail->Port = 587;      
    $mail->From = "vivicrew@iotadev.com";
    $mail->FromName = "Vivicrew";
    $mail->addAddress($to);
    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = "This is the plain text version of the email content";
    $mail->send();
}



?>
