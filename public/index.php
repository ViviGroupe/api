<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$app = new \Slim\App;

/*$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});*/
$corsOptions = array(
    "origin" => "*",
    "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);
$cors = new \CorsSlim\CorsSlim($corsOptions);

$app->add($cors);
//For guest
require_once('../app/api/Guest/GetGuestToken.php');
require_once('../app/api/Guest/GetPlaces.php');
require_once('../app/api/Guest/AddPlaces.php');

//For tourist
require_once('../app/api/Tourist/Register.php');
require_once('../app/api/Tourist/SignIn.php');
require_once('../app/api/Tourist/GetProfile.php');
require_once('../app/api/Tourist/UpdateProfile.php');
require_once('../app/api/Tourist/AddTourRating.php');
require_once('../app/api/Tourist/DeleteTourRating.php');
require_once('../app/api/Tourist/EditTourRating.php');
require_once('../app/api/Tourist/SearchTourList.php');

//For host
require_once('../app/api/Host/Register.php');
require_once('../app/api/Host/SignIn.php');
require_once('../app/api/Host/GetProfile.php');
require_once('../app/api/Host/UpdateProfile.php');
require_once('../app/api/Host/AddTour.php');
require_once('../app/api/Host/AddEditTourImage.php');
require_once('../app/api/Host/DisableTourDate.php');
require_once('../app/api/Host/EditTour.php');
require_once('../app/api/Host/DeleteTour.php');
require_once('../app/api/Host/GetTourByHost.php');
require_once('../app/api/Host/ChangeTourPublishStatus.php');

//Common
require_once('../app/api/Common/GetTourRatingList.php');
require_once('../app/api/Common/AddTourView.php');
require_once('../app/api/Common/GetTourDetail.php');
require_once('../app/api/Common/GetTourImage.php');
require_once('../app/api/Common/GetMoodList.php');

require_once('../app/api/EmailActivation.php');









$app->run();
