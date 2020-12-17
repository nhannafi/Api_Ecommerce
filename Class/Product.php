<?php

class Product extends Database{

    /**
     * Get List of Products
     *
     * @return void
     */
    public function getAll()
    {
        try {
            $query = $this->pdo->query("SELECT * FROM product");
            General::sendData(200, "Liste des produits", $query->fetchAll(PDO::FETCH_OBJ));

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }

    }

    /**
     * Get one product by id
     *
     * @param integer $id
     * @return void
     */
    public function getOne(int $id)
    {
        try {
            if (is_int($id)) {
                $query = $this->pdo->query("SELECT * FROM product WHERE id = $id");
                General::sendData(200, "Données de le produit." , $query->fetch(PDO::FETCH_OBJ));
                
            } else {
                General::sendError(400, "Erreur d'identifiant, nécessite un integer");
            }

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }

    /**
     * Save an product in DB
     *
     * @param array $data
     * @return void
     */
    public function postOne(array $data)
    {
        try {
            foreach ($data as $key => $value) {
                $data[$key] = htmlspecialchars($value);
            }
            var_dump($data);
            $statement = $this->pdo->prepare("INSERT INTO product (name, infos, buyPrice, sellPrice)
                                            VALUES (:name, :infos, :buyPrice, :sellPrice)");
            $prepare = $this->pdo->prepare($statement);
            $prepare->execute($data);

            $query = $this->pdo->query("SELECT id FROM product WHERE name = .name");

            General::sendData(200, "produit enregistré!");

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }

    /**
     * update an product in DB
     *
     * @param array $data
     * @return void
     */
    public function updateOne(int $id, string $json)
    {
        try {
            $data = json_decode($json);
            // foreach ($data as $key => $value) {
            //     $data[$key] = htmlspecialchars($value);
            // }

            $prepare = $this->pdo->prepare("UPDATE product SET 
                name = :name,
                infos = :infos,
                buyPrice = :buyPrice
                sellPrice = :sellPrice                
                WHERE id = $id
            ");
            $prepare->bindParam(":name", $data->name);
            $prepare->bindParam(":infos", $data->infos);
            $prepare->bindParam(":buyPrice", $data->buyPrice);
            $prepare->bindParam(":sellPrice", $data->sellPrice);
            $prepare->execute();

            $query = $this->pdo->query("SELECT * FROM product WHERE id = $id");

            General::sendData(200, "product modifié!", $query->fetch(PDO::FETCH_OBJ));

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }

    /**
     * Delete an product
     *
     * @param integer $id
     * @return void
     */
    public function deleteOne(int $id)
    {
        try {
            if (is_int($id)) {
                $prepare = $this->pdo->prepare("DELETE FROM product WHERE id = $id");
                $prepare->execute();
                
                General::sendData(200, "product supprimé!");
            } else {
            General::sendError(400, "Erreur d'identifiant, nécessite un integer");
            }

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }
}
