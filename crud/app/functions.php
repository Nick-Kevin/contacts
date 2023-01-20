<?php

    function pdo_connect_mysql() {
        $SERVER   = "localhost";
        $USERNAME = "root";
        $PASSWORD = "";
        $DB_NAME  = "phpcrud";
        
        try {
            return new PDO("mysql:host=$SERVER;dbname=$DB_NAME;charset=utf8;port=3306", $USERNAME, $PASSWORD);
        } catch (PDOException $exception) {
            exit("Failed to connect to database!");
        }
    }

    function template_header($title) {
        echo <<<EOT
        <DOCTYPE! html>
        <html>
            <head>
                <meta charset="utf8">
                <title>$title</title>
                <link rel="stylesheet" href="style.css" type="text/css">
                <link rel="stylesheet" href="../fontawesome-free/css/all.css">
            </head>
            <body>
            <nav class="navtop">
                <div>
                    <h1>Website title</h1>
                    <a href="index.php"><i class="fas fa-home"></i>Home</a>
                    <a href="read.php"><i class="fas fa-address-book"></i>Contacts</a>
                </div>
            </nav>
        EOT;    
    }

    function template_footer() {
        echo <<<EOT
            </body>
        </html>
        EOT;
    }

    function process($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    function validateDate ($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        
        return $d && $d->format($format) === $date;
    }
?>