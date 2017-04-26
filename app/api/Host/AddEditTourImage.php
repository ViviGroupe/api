<?php
$app->post("/api/host/AddEditTourImage", function ($request) {
    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    if ($request->hasHeader('token')) {
        $token = $request->getHeader('token')[0];
        $valid = validateToken($token,2);
        if($valid==true)
        {
            $tour_image = $request->getParsedBody()['tour_image'];
            $tourID = $request->getParsedBody()['tourID'];
            if($tourID == ""){
                $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
            } else {
                $tourImageDirectory = TOURPLACEIMAGEPATH.$tourID;
                if (!file_exists($tourImageDirectory)) {
                    mkdir($tourImageDirectory, 0777, true);
                    
                    
                } else {
                    //for edit, delete image and re-add;
                    $files = glob($tourImageDirectory.'/*'); // get all file names
                    foreach($files as $file){ // iterate files
                      if(is_file($file)){
                          unlink($file); // delete file
                      }
                    }
                }
                
                for($i=0;$i<count($tour_image);$i++){
                        $imageDirectory = imageFolderPath($tour_image[$i],$tourImageDirectory);
                        $imageToDBURL = imageDBPath($imageDirectory['imageDirectory']);
                        file_put_contents($imageDirectory['imageDirectory'], $imageDirectory['image']);
                }
                 $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
            }
        } else {
            $result = array("ReturnCode"=>INCORRECTTOKEN,"ReturnDesc"=>INCORRECTTOKENDESC);
        }
    } else {
        $result = array("ReturnCode"=>TOKENREQUIRED,"ReturnDesc"=>TOKENREQUIREDDESC);
    }


    //echo
    //$date = date('d-m-Y',strtotime($date));
    echo json_encode($result);
});
?>
