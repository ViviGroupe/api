<?php
$app->post("/api/guest/AddPlaces", function ($request) {

    require_once('../app/api/dbConnect.php');
    require_once('../app/api/status.php');
    require_once('../app/api/common.php');

    $place_name = $request->getParsedBody()['place_name'];
    $place_description = $request->getParsedBody()['place_description'];
    $place_image = $request->getParsedBody()['place_image'];

    if($place_name=="" || $place_description=="" || $place_image==""){
        $result = array("ReturnCode"=>INPUTNULL,"ReturnDesc"=>INPUTNULLDESC);
    } else {
        $imageDirectory = imageFolderPath($place_image,PLACEIMAGEPATH);

        $imageToDBURL = imageDBPath($imageDirectory['imageDirectory']);
        //var_dump($imageToDBURL);
        $sql = "INSERT INTO Places (Placename, Placedescription, Placeimage, AvgView) VALUES ('".mysqli_real_escape_string($mysqli,$place_name)."', '".mysqli_real_escape_string($mysqli,$place_description)."','$imageToDBURL',0)";

        //success
        if (mysqli_query($mysqli, $sql)) {
            $result = array("ReturnCode"=>SUCCESS,"ReturnDesc"=>SUCCESSDESC);
            file_put_contents($imageDirectory['imageDirectory'], $imageDirectory['image']);
        } else {
            $result = array("ReturnCode"=>FAILADDRECORD,"ReturnDesc"=>FAILADDRECORDDESC,"SqlError"=>$mysqli->error);
        }
    }


    echo json_encode($result);
});

?>
