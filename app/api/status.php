<?php
//Domain path name
define("DOMAINPATH","http://www.iotadev.com:7027/");

//Domain path string length
define("DOMAINPATHSTRINGLENGTH",strlen(DOMAINPATH)-1);
//Image path name
define("PLACEIMAGEPATH","../public/images");

//Tour image path name
define("TOURPLACEIMAGEPATH","./images/tour/");
//TOKEN REQUIRED
define("TOKENREQUIRED","-10");
define("TOKENREQUIREDDESC","Token is required");

//TOKEN REQUIRED
define("INCORRECTTOKEN","-11");
define("INCORRECTTOKENDESC","Token is incorrect");

//TOKEN REQUIRED
define("SESSIONEXPIRED","-12");
define("SESSIONEXPIREDDESC","Session expired or invalid");

//OPERATION SUCCESSFUL
define("SUCCESS","0");
define("SUCCESSDESC","Operation was successful");

//FAIL TO RETRIEVE DATA
define("FAIL","-100");
define("FAILDESC","Failed to retrieve data");

//FAIL TO UPDATE DATA
define("FAILUPDATERECORD","-101");
define("FAILUPDATERECORDDESC","Failed to update data");

//FAIL TO DELETE DATA
define("FAILDELETERECORD","-102");
define("FAILDELETERECORDDESC","Failed to delete data");

//FAIL TO ADD DATA
define("FAILADDRECORD","-200");
define("FAILADDRECORDDESC","Failed to add record");

//ZERORECORD
define("ZERORECORD","20");
define("ZERORECORDDESC","Zero Record");

//FAIL TO SIGN UP
define("EMAILEXIST","-195");
define("EMAILEXISTDESC","Email already exist");

//INPUT NULL
define("INPUTNULL","-180");
define("INPUTNULLDESC","Value cannot be null");

//FAIL ACTIVATION USER
define("FAILACTIVATION","-150");
define("FAILACTIVATIONDESC","Failed to activate user");

//ACTIVATED USER
define("ACTIVATED","-151");
define("ACTIVATEDDESC","User already activated account");

//LOGIN FAIL
define("LOGINFAIL","-30");
define("LOGINFAILDESC","Login Failed");

//LOGIN FAIL
define("CREDENTIALERROR","-31");
define("CREDENTIALERRORDESC","Your username or password is incorrect");

//ACCOUNT NOT YET ACTIVATE
define("NOACTIVATE","-31");
define("NOACTIVATEDESC","Account not yet activate");

//GUEST NOT ELIGIBLE TO ADD RATING
define("NOTELIGIBLEADDRATING","-39");
define("NOTELIGIBLEADDRATINGDESC","Guest not eligible to add rating");
?>
