<?php

namespace Acme;

use Acme\classes\Bestelling;
use Acme\model\ProductModel;

require "../vendor/autoload.php";

// Haal de tafel-id op uit de POST-data
if ($idTafel = $_POST['idtafel'] ?? false) {

    // Haal de geselecteerde producten en aantallen op
    $producten = $_POST['products'] ?? [];
    $aantallen = [];

    // Haal de aantallen per product op
    foreach ($producten as $productId) {
        $aantallen[$productId] = $_POST["product_$productId"] ?? 1; // Gebruik 1 als geen aantal is ingevuld
    }

    // Controleer of er producten zijn geselecteerd
    if (!empty($producten)) {
        // Maak een nieuw Bestelling object aan
        $bestelling = new Bestelling($idTafel);

        // Voeg de geselecteerde producten en hun aantallen toe aan de bestelling
        foreach ($producten as $productId) {
            $aantal = (int)$aantallen[$productId]; // Zorg ervoor dat het aantal een integer is
            for ($i = 0; $i < $aantal; $i++) {
                $bestelling->addProducts([$productId]); // Voeg het product het aantal keren toe
            }
        }

        // Sla de bestelling op in de database
        $bestelling->saveBestelling();

        // Redirect naar de indexpagina of een andere pagina
        echo "Bestelling succesvol opgeslagen!";
        echo "<br>Geselecteerde producten: " . implode(", ", $producten);
        header("Location: index.php");
    } else {
        // Als er geen producten zijn geselecteerd, geef een foutmelding
        echo "Geen producten geselecteerd.";
    }
} else {
    // Als er geen tafel-id is opgegeven, toon een foutmelding
    http_response_code(404);
    include('error_404.php');
    die();
}
?>
