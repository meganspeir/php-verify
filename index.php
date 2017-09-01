<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Phone Verification by Twilio</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="main.js"></script>
    </head>
    <body>
        <div id="errors"></div>
        <form id="enter_number">
            <p>Enter your phone number:</p>
            <p><input type="text" name="phone_number" id="phone_number" /></p>
            <p><input type="submit" name="submit" value="Verify" /></p>
        </form>

        <div id="verify_code" style="display: none;">
            <p>Calling you now.</p>
            <p>When prompted, enter the verification code:</p>
            <h1 id="verification_code"></h1>
            <p><strong id="status">Waiting...</strong></p>
        </div>
    </body>
</html>
