<?php

namespace Acme\classes;

use Acme\model\ProductTafelModel;
use Acme\model\TafelModel;
use Acme\model\ProductModel;

class Rekening
{
    public function setPaid($idTafel): void
    {
        // Haal de bestelling op voor de opgegeven tafel
        $bm = new ProductTafelModel();
        $bestelling = $bm->getBestelling($idTafel);

        // Controleer of er producten zijn voor deze tafel die nog niet betaald zijn
        if (isset($bestelling['products']) && !empty($bestelling['products'])) {
            // Update de betaald status van alle producten naar 1 wat betaald betekent
            foreach ($bestelling['products'] as $idProduct) {
                $bm->setColumnValue('betaald', 1); // zet hem in de database als betaald
                $bm->setColumnValue('idtafel', $idTafel);
                $bm->setColumnValue('idproduct', $idProduct);
                $bm->save(); // Slaalt de wijziging op
            }
        }
    }

    /**
     * Haal de rekening op voor een specifieke tafel
     *
     * @param $idTafel
     * @return array
     */
    public function getBill($idTafel): array
    {
        $bill = [];
        $bm = new ProductTafelModel();
        $bestelling = $bm->getBestelling($idTafel);

        $tm = new TafelModel();

        $bill['tafel'] = $tm->getTafel($idTafel);
        $bill['datumtijd'] = [
            'timestamp' => $bestelling['datumtijd'],
            'formatted' => date('d-m-Y H:i', (int)$bestelling['datumtijd']) 
        ];
        if (isset($bestelling['products'])) {
            foreach ($bestelling['products'] as $idProduct) {
                if(!isset($bill['products'][$idProduct]['data'])) {
                    $bill['products'][$idProduct]['data'] = (new ProductModel(
                    ))->getProduct(
                        $idProduct
                    );
                }
                if (!isset($bill['products'][$idProduct]['aantal'])) $bill['products'][$idProduct]['aantal'] = 0;
                $bill['products'][$idProduct]['aantal']++;
            }
        }

        // Bereken het totaal van de prijzen
        $totaal = 0;
        foreach ($bill['products'] as $product) {
            $totaal += $product['data']['prijs'] * $product['aantal']; 
        }
        $bill['totaal'] = $totaal;

        return $bill;
    }

    /**
     * Verwijder de producten van de tafel en stuur de gebruiker terug naar index.php
     *
     * @param $idTafel
     */
    public function emptyTable($idTafel)
    {
        $bm = new ProductTafelModel();
        $bestelling = $bm->getBestelling($idTafel);

        // Verwijderd alle producten van de bestelling
        foreach ($bestelling['products'] as $idProduct) {
            $bm->deleteProductFromTable($idTafel, $idProduct); // Verwijderd het product van de tafel
        }

        // terug naar index.php
        header("Location: index.php");
        exit();  
    }
}
