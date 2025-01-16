<?php

namespace Acme;

use Acme\model\TafelModel;

// Verbinding maken met de database
$conn = new \mysqli('localhost', 'root', 'root', 'kassasysteem');
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// SQL query om de tafels uit de dataabse te hallen
$result = $conn->query("SELECT idtafel, omschrijving FROM tafel");

// Controleert of de tafels zijn opgehaald
$tafels = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tafels[] = $row;
    }
}

$conn->close();
require "../vendor/autoload.php";
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kiezen tafel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        .tafel-lijst {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .tafel-lijst div {
            background-color: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 200px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .tafel-lijst div a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 16px;
        }

        .tafel-lijst div:hover {
            background-color: #e0f7e0;
        }

        @media (max-width: 768px) {
            .tafel-lijst div {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Kies een tafel</h1>
<div class="tafel-lijst">
    <?php
    // gaat door alle tafels
    foreach ($tafels as $tafel) {
        // Haalt het ID van de tafel op
        $idtafel = ($tafel['idtafel']);
        $omschrijving = ($tafel['omschrijving']);
        // geeft een link naar keuze.php met idtafel
        echo "<div><a href='keuze.php?idtafel={$idtafel}'>{$omschrijving}</a></div>";
    }
    ?>
</div>
</body>
</html>
