<?php
class User extends Database{
     /**
     * Save an user in DB
     *
     * @param array $data
     * @return void
     */
    public function signup(array $data)
    {
        try {
            foreach ($data as $key => $value) {
                if ($key === "password") {
                    $data[$key] =  password_hash($value, PASSWORD_DEFAULT);
                } else {
                    $data[$key] = htmlspecialchars($value);
                }
              
            }
            // var_dump($data);
            $prepare = $this->pdo->prepare("INSERT INTO user (firstName, lastName, email, password)
                                            VALUES (:firstName, :lastName, :email, :password)");
            $prepare->execute($data);

            General::sendData(200, "user enregistré!");

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }
     /**
     * Get one user by id
     *
     * @param integer $
     * @return void
     */
   
    public function login()
    {
        try {
             
                $query = $this->pdo->query("SELECT * FROM user WHERE email = $email");
                General::sendData(200, "Données de l'utilisateur." , $query->fetch(PDO::FETCH_OBJ));
            if (password_verify($data["password"], $user->password)) {
                General::sendError(200, "Login success");
            }  
             else {
                General::sendError(400, "Erreur d'identifiant, ou password ");
            }

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }
  
    /**
     * Get List of Users
     *
     * @return void
     */
    public function getAll()
    {
        try {
            $query = $this->pdo->query("SELECT * FROM user");
            General::sendData(200, "Liste des ustilisateurs", $query->fetchAll(PDO::FETCH_OBJ));

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }

    }

    /**
     * update an stock in DB
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

            $prepare = $this->pdo->prepare("UPDATE user SET 
                firstName = :firstName,
                lastName = :lastName, 
                email  = :email,
                password  = :password,            
                WHERE id = $id
            ");
            $prepare->bindParam(":firstName", $data->firstName);
            $prepare->bindParam(":lastName", $data->lastName);
            $prepare->bindParam(":email", $data->email);
            $prepare->bindParam(":password", $data->password);
            $prepare->execute();

            $query = $this->pdo->query("SELECT * FROM user WHERE id = $id");

            General::sendData(200, "stock modifié!", $query->fetch(PDO::FETCH_OBJ));

        } catch (\PDOException $e) {
            General::sendError(400, $e->getMessage());
        }
    }


}