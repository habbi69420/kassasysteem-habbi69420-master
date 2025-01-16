<?php

namespace Acme;

use Acme\classes\Bestelling;
use Acme\model\ProductModel;

require "../vendor/autoload.php";

// Haalt de idtafel op uit POST
if ($idTafel = $_POST['idtafel'] ?? false) {

    // Haalt de geklikte producten en aantalle op
    $producten = $_POST['products'] ?? [];
    $aantallen = [];

    // Haalt de aantallen per product op
    foreach ($producten as $productId) {
        $aantallen[$productId] = $_POST["product_$productId"] ?? 1; // Gebruikt 1 als geen getal is ingevuld
    }

    // Controleert of er producten zijn aangevinkt
    if (!empty($producten)) {
        // Maakt een nieuw Bestelling object aan
        $bestelling = new Bestelling($idTafel);

        // Voegt de geselecteerde producten en hun aantallen toe aan de bestelling
        foreach ($producten as $productId) {
            $aantal = (int)$aantallen[$productId]; // Zorgt ervoor dat het aantal een int is
            for ($i = 0; $i < $aantal; $i++) {
                $bestelling->addProducts([$productId]); // Voegt het product het het zelfde aantal zoals het nummer toe
            }
        }

        // Slaat de bestelling op in de database
        $bestelling->saveBestelling();

        // gaat terug naar de index.pph pagina
        echo "<br>Geselecteerde producten: " . implode(", ", $producten);
        header("Location: index.php");
    }}
 else {
    // geen tafel geeft error_404.php
    http_response_code(404);
    include('error_404.php');
    die();
}
?>
