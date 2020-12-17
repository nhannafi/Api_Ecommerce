<?php
class Stock extends Database {
 /**
     * Get List of Stocks
     *
     * @return void
     */
    public function getAll()
    {
        try {
            $query = $this->pdo->query("SELECT * FROM stock
                                        INNER JOIN product 
                                        ON stock.product_id = product.id");
            General::sendData(200, "Liste des stocks de produit", $query->fetchAll(PDO::FETCH_OBJ));

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }

    }

    /**
     * Get one stock by id
     *
     * @param integer $id
     * @return void
     */
    public function getOne(int $id)
    {
        try {
            if (is_int($id)) {
                $statement = $this->pdo->query("SELECT * FROM stock
                                                INNER JOIN product ON stock.product_id = product.id
                                                WHERE id = $id");
                $query = $this->pdo->query($statement);                                
                General::sendData(200, "Données de le stock." , $query->fetch(PDO::FETCH_OBJ));
                
            } else {
                General::sendError(400, "Erreur d'identifiant, nécessite un integer");
            }

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }

    /**
     * update stock in DB
     *
     * @param array $data
     * @return void
     */
    public function updateOne(int $id, string $json)
    {
        try {
            $data = json_decode($json);
            $prepare = $this->pdo->prepare("UPDATE stock SET 
                quantityInStock = :quantityInStock,
                product_id = :product_id,               
                WHERE id = $id
            ");
            $prepare->bindParam(":quantityInStock", $data->quantityInStock);
            $prepare->bindParam(":product_id", $data->product_id);
            $prepare->execute();

            $query = $this->pdo->query("SELECT * FROM stock WHERE id = $id");

            General::sendData(200, "stock modifié!", $query->fetch(PDO::FETCH_OBJ));

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }


}