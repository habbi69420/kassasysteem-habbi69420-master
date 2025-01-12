<?php

namespace Acme;

use Acme\model\ProductModel;

require "../vendor/autoload.php";
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bestelling</title>
    <style>
        /* Algemene reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Basisstijl voor het lichaam */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        /* Stijl voor het formulier */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .product-item label {
            font-size: 16px;
        }

        .product-item input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-left: 10px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        /* Stijl voor mobiele apparaten */
        @media (max-width: 768px) {
            .product-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .product-item input[type="number"] {
                width: 100%;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <form action="bestellingdoorvoeren.php" method="post">
        <?php
        // Haal de tafel-id op via GET
        $idTafel = $_GET['idtafel'] ?? false;
        if ($idTafel) {
            echo "<input type='hidden' name='idtafel' value='$idTafel'>";

            // Haal alle producten op uit de database
            $productModel = new ProductModel();
            $producten = $productModel->getAll(); // Dit haalt alle producten op uit de database

            // Toon elk product als een checkbox met een inputveld voor aantal
            foreach ($producten as $product) {
                $idProduct = $product->getColumnValue('idproduct');
                $naam = $product->getColumnValue('naam'); // Veronderstel dat de naam van het product zo wordt opgehaald
                echo "<div class='product-item'>";
                echo "<label><input type='checkbox' name='products[]' value='$idProduct'> $naam</label>";
                echo "<label>Aantal: <input type='number' name='product_$idProduct' min='1' value='1'></label>";
                echo "</div>";
            }

            echo "<button class='submit-btn' type='submit'>Volgende</button>";
        } else {
            // Als er geen tafel-id is opgegeven, toon een foutmelding
            http_response_code(404);
            include('error_404.php');
            die();
        }
        ?>
    </form>
</body>
</html>
