<?php

use Acme\classes\Rekening;

require "../vendor/autoload.php";

$idTafel = $_GET['idtafel'] ?? null;

if ($idTafel) {
    // Maak een nieuwe instantie van de Rekening-class en haal de rekening op
    $rekening = new Rekening();
    $bill = $rekening->getBill($idTafel); // Haal de rekening op voor de opgegeven tafel

    if (!empty($bill)) {
        echo "<h1>Rekening voor Tafel: " . ($bill['tafel'][1]) . "</h1>";
        echo "<p>Datum en Tijd: " . ($bill['datumtijd']['formatted']) . "</p>"; //  datum en tijd

        echo "<table border='1' style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
                <thead>
                    <tr>
                        <th style='padding: 10px; text-align: left;'>Product</th>
                        <th style='padding: 10px; text-align: left;'>Aantal</th>
                        <th style='padding: 10px; text-align: left;'>Prijs</th>
                        <th style='padding: 10px; text-align: left;'>Totaal</th>
                    </tr>
                </thead>
                <tbody>";

        $totaal = 0;
        foreach ($bill['products'] as $product) {
            $productTotaal = $product['data']['prijs'] * $product['aantal'];
            $totaal += $productTotaal;
            echo "<tr>
                    <td style='padding: 10px;'>" . ($product['data']['naam']) . "</td>
                    <td style='padding: 10px;'>" . ($product['aantal']) . "</td>
                    <td style='padding: 10px;'>" . ($product['data']['prijs']) . " €</td>
                    <td style='padding: 10px;'>" . ($productTotaal) . " €</td>
                  </tr>";
        }

        echo "<tr>
                <td colspan='3' style='padding: 10px; text-align: right;'><strong>Totaal</strong></td>
                <td style='padding: 10px;'>" . ($totaal) . " €</td>
              </tr>";
        echo "</tbody></table>";

        // Voegt een knop toe waarme je hem op betaald kan zetten
        echo "<form method='POST' style='margin-top: 20px;'>
                <button type='submit' name='setPaid' value='1' style='padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>Betalen</button>
              </form>";

        // als hij dus op de knop heeft gelikt en het ding gemarkt staat als betaald komt er een regel dat die is betaald en zet in de database de status naar 1 wat betaald betekent dit wordt gedaan door de said paid
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setPaid'])) {
            $rekening->setPaid($idTafel); // Zet de rekening als betaald
            echo "<p style='color: green;'>De rekening is betaald. Bedankt!</p>";

            // geeft een knop nadat er op betaald is geklikt waarme je de tafel kan leeg maken
            echo "<form method='POST' style='margin-top: 20px;'>
                    <button type='submit' name='emptyTable' value='1' style='padding: 10px 20px; background-color: #FF5722; color: white; border: none; border-radius: 5px; cursor: pointer;'>Tafel leegmaken</button>
                  </form>";
        }

        // Voegt een knop toe om de tafel leeg te maken  maar alleen als de rekening is betaald
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emptyTable'])) {
            $rekening->emptyTable($idTafel); // Leet de tafel en stuurt je terug naar index.php
            header('Location: index.php'); 
            exit;
        }

    } 

} else {
    http_response_code(404);
    include('error_404.php');
    die();
}
?>
