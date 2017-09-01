<?php

    function setupDatabase()
    {
        // put your database information here
        $username = 'root';
        $password = 'root';
        $host = 'localhost';
        $dbname = 'verify';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }

        return $pdo;
    }

    // attempts to delete existing entries and
    // save verification code in DB with phone number
    function updateDatabase($phoneNumber, $code)
    {
        $pdo = setupDatabase();
        if (!is_a($pdo, 'PDO')) {
            echo 'PDO is false';
          return $pdo;
        }

        // Assuming US country code for example
        $params = [ 'phoneNumber' => '1' . $phoneNumber ];

        try {
            $stmt = $pdo->prepare("DELETE FROM numbers WHERE phone_number=:phoneNumber");
            $stmt->execute($params);

            $params['code'] = $code;
            $stmt = $pdo->prepare("INSERT INTO numbers (phone_number, verification_code) VALUES(:phoneNumber, :code)");
            $stmt->execute($params);

        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }

        return $code;
    }

    function matchVerificationCode($phoneNumber, $code)
    {
        $pdo = setupDatabase();
        if (!is_a($pdo, PDO::class)) {
            echo 'ERROR: PDO is false';
            return 'ERROR: PDO is false '.$pdo;
        }

        $params = [ 'phoneNumber' => $phoneNumber ];

        try {
            $stmt = $pdo->prepare("SELECT * FROM numbers WHERE phone_number=:phoneNumber");
            $stmt->execute($params);

            $result = $stmt->fetch();
            $response = 'unverified';
            if ($result['verification_code'] == $code) {
                $stmt = $pdo->prepare("UPDATE numbers SET verified = 1 WHERE phone_number=:phoneNumber");
                $stmt->execute($params);
                $response = 'verified';
            }

            return $response;

        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }

    function statusIs($phoneNumber)
    {
        $pdo = setupDatabase();
        if (!is_a($pdo, 'PDO')) {
            echo 'PDO is false';
            return $pdo;
        }

        $params = [ 'phoneNumber' => $phoneNumber ];

        try {
            $stmt = $pdo->prepare("SELECT * FROM numbers WHERE phone_number=:phoneNumber");
            $stmt->execute($params);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['verified'] == 1) {
                return 'verified';
            }

            return 'unverified';

        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
