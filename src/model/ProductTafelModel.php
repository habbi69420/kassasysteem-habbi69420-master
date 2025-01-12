<?php

namespace Acme\model;

use Acme\system\Database;

class ProductTafelModel extends Model
{
    protected static string $tableName = "product_tafel";
    protected static string $primaryKey = "idproduct_tafel";

    public function __construct($env = '../.env')
    {
        parent::__construct(Database::getInstance($env));
    }

    // Andere bestaande methoden...

    /**
     * Verwijder een product van de tafel.
     *
     * @param int $idTafel
     * @param int $idProduct
     * @return void
     */
    public function deleteProductFromTable(int $idTafel, int $idProduct): void
    {
        // Verwijder het product van de opgegeven tafel
        $query = "DELETE FROM " . self::$tableName . " WHERE idtafel = :idTafel AND idproduct = :idProduct";
        
        // Verkrijg de databaseverbinding via de constructor
        $db = Database::getInstance('../.env');
        
        // Bereid de query voor
        $stmt = $db->prepare($query);
        
        // Bind de parameters
        $stmt->bindParam(':idTafel', $idTafel, \PDO::PARAM_INT);
        $stmt->bindParam(':idProduct', $idProduct, \PDO::PARAM_INT);
        
        // Voer de query uit
        $stmt->execute();
    }
    public function saveBestelling(array $bestelling): int
{
    $lastInsertId = 0; // Variabele om het laatste ingevoerde ID op te slaan

    foreach ($bestelling['products'] as $idProduct) {
        $this->setColumnValue('idtafel', $bestelling['idtafel']);
        $this->setColumnValue('datumtijd', $bestelling['datetime']);
        $this->setColumnValue('betaald', 0);
        $this->setColumnValue('idproduct', $idProduct);

        // Sla elk product afzonderlijk op in de database
        $lastInsertId = $this->save();
    }

    return $lastInsertId; // Return het laatste ingevoerde ID (kan handig zijn voor verder gebruik)
}

    /**
     * @param $idTafel
     *
     * @return array    [
     * 'idTafel'  => int idTafel,
     * "products" => array [idproduct, idproduct, ...],
     * "datumtijd" => int dateTime,
     * "betaald" => int betaald
     * ]
     */
    public function getBestelling($idTafel): array
    {
        $products = $this->getAll(['idtafel' => $idTafel, 'betaald' => 0]);

        $bestelling['idTafel'] = $idTafel;
        $bestelling['datumtijd'] = isset($products[0])
            ? (int)$products[0]->getColumnValue('datumtijd') : 0;
        $bestelling['betaald'] = 0;

        foreach ($products as $product) {
            $idProduct = $product->getColumnValue('idproduct');
            $bestelling['products'][] = $idProduct;
        }
        return $bestelling;
    }
}