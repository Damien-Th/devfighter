<?php
class PlayerManager
{
    private $_bdd;


    public function __construct($bdd)
    {
        $this->setDb($bdd);
    }

    public function add(Player $perso)
    {

        $req = $this->_bdd->prepare('INSERT INTO akyos_game(firstname, lastname, type_perso, health, spec_atq) VALUES (:firstname, :lastname, :type_perso, :health, :spec_atq)');
        $req->bindValue(':firstname', $perso->firstname());
        $req->bindValue(':lastname', $perso->lastname());
        $req->bindValue(':type_perso', $perso->type_perso());
        $req->bindValue(':health', $perso->health());
        $req->bindValue(':spec_atq', $perso->spec_atq());

        $req->execute();
        $req->closeCursor();
    }


    public function getList()
    {
        $persos = [];

        $req = $this->_bdd->prepare('SELECT * FROM akyos_game');
        $req->execute();

        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $persos[] = new Player($donnees);
        }

        return $persos;
    }


    public function update($id)
    {
        $req = $this->_bdd->query("SELECT health FROM akyos_game WHERE id = $id");
        $donnees = $req->fetch();
        $health = $donnees['health'] - 10;

        $req = $this->_bdd->prepare("UPDATE akyos_game SET health = $health WHERE ID = $id");
        $req->execute();
        $req->closeCursor();
    }



    public function countPersoRestant()
    {
        $countPersoRestant = $this->_bdd->query("SELECT COUNT(id) AS id FROM akyos_game");
        return $countPersoRestant->fetch();
    }

    public function checkHealth($randomPlayer)
    {
        $checkHealth = $this->_bdd->query("SELECT health FROM akyos_game WHERE id = $randomPlayer");
        return $checkHealth->fetch();
    }


    public function updateHealth($health, $randomPlayer)
    {
        $updateHealth = $this->_bdd->prepare("UPDATE akyos_game SET health = $health WHERE ID = $randomPlayer");
        $updateHealth->execute();
        $updateHealth->closeCursor();
    }

    public function removePlayer($randomPlayer)
    {
        $removePlayer = $this->_bdd->prepare("DELETE FROM akyos_game WHERE id = $randomPlayer");
        $removePlayer->execute();
        $removePlayer->closeCursor();
    }



    public function resetGame(){
        
        $req = $this->_bdd->prepare("DELETE FROM akyos_game");
        $req->execute();
        $req->closeCursor();
    
        $req = $this->_bdd->prepare("ALTER TABLE akyos_game AUTO_INCREMENT = 1");
        $req->execute();
        $req->closeCursor();
    }

    public function setDb(PDO $bdd)
    {
        $this->_bdd = $bdd;
    }
}
