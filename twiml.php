<?php
    require __DIR__ . '/vendor/autoload.php';
    require("database.php");

    use Twilio\Twiml;

    $response = new Twiml;


    if (empty($_POST["Digits"])) {
        $gather = $response->gather([ 'input' => 'dtmf', 'timeout' => 10, 'numDigits' => 6 ]);
        $gather->say("Please enter your verification code.");
    } else {
        // grab caller phone number and caller entered code
        $submittedNumber = ltrim($_POST["Called"], '+');
        $submittedCode = $_POST["Digits"];

        // verify code and phone number against db record
        $match = matchVerificationCode($submittedNumber, $submittedCode);
        if ($match == 'verified') {
            $response->say("Thank you! Your phone number has been verified.");
        } else {
            $gather->say("Verification code incorrect, please try again.");
        }
    }
    header('Content-Type: text/xml');
    echo $response
?>
