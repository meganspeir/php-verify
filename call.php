<?php
    require __DIR__ . '/vendor/autoload.php';
    require("database.php");

    use Twilio\Rest\Client;

    function returnError($error)
    {
        $json = array();
        $json["error"] = $error;

        header('Content-type: application/json');
        http_response_code(500);
        echo(json_encode($json));
    }

    function makeCall($submittedNumber, $code)
    {
        // put your project information here
        $accountSid = "YOUR_ACOCUNT_SID";
        $authToken = "YOUR_AUTH_TOKEN";
        $outgoingNumber = 'YOUR_TWILIO_NUMBER';
        $endPoint = "YOUR_URL/twiml.php";

        // Instantiate a new Twilio Rest Client
        $client = new Client($accountSid, $authToken);

        try {
            // initiate phone call via Twilio REST API
            $client->account->calls->create(
                $submittedNumber,        // The phone number you wish to dial
                $outgoingNumber,         // Verified Outgoing Caller ID or Twilio number
                [ "url" => $endPoint ]   // The URL of twiml.php on your server
            );
        } catch (Exception $e) {
            returnError($e->getMessage());
        }

        // return verification code as JSON
        $json = array();
        $json["verification_code"] = $code;

        header('Content-type: application/json');
        echo(json_encode($json));
    }

    // require POST request
    if ($_SERVER['REQUEST_METHOD'] != "POST") die;

    // save a verification code in DB with phone number
    // attempts to delete existing entries first
    $submittedNumber = $_POST["phone_number"];
    $code = rand(100000, 999999);
    $updateError = updateDatabase($submittedNumber, $code);

    if (strpos($updateError, 'ERROR:') !== false) {
        returnError($updateError);
    } else {
        makeCall($submittedNumber, $code);
    }
