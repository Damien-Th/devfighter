<?php

class Player
{

    private $_id;
    private $_firstname;
    private $_lastname;
    private $_type_perso;
    private $_health;
    private $_atq;
    private $_spec_atq;

    const DEFENCE_SPECIALE = 10;


    public function __construct($donnees)
    {
      $this->hydrate($donnees);
    }


    public function hydrate(array $donnees)
    {

      foreach ($donnees as $key => $value)
      {
        // On récupère le nom du setter correspondant à l'attribut.
        $method = 'set'.ucfirst($key);
            
        // Si le setter correspondant existe.
        if (method_exists($this, $method))
        {
          // On appelle le setter.
          $this->$method($value);
        }
      }
    }


    // getters

    public function id()
    {
        return $this->_id;
    }
    public function firstname()
    {
        return $this->_firstname;
    }
    public function lastname()
    {
        return $this->_lastname;
    }
    public function type_perso()
    {
        return $this->_type_perso;
    }
    public function health()
    {
        return $this->_health;
    }
    public function atq()
    {
        return $this->_atq;
    }
    public function spec_atq()
    {
        return $this->_spec_atq;
    }

    // setters

    public function setId(int $id)
    {
        $this->_id = $id;
    }

    public function setFirstname(string $firstname)
    {
        $this->_firstname = $firstname;
    }

    public function setLastname(string $lastname)
    {
        $this->_lastname = $lastname;
    }

    public function setType_perso(string $type_perso)
    {
        $this->_type_perso = $type_perso;
    }

    public function setHealth(int $health)
    {
        $this->_health = $health;
    }

    public function setAtq(int $atq)
    {
        $this->_atq = $atq;
    }

    public function setSpec_atq(int $spec_atq)
    {
        $this->_spec_atq = $spec_atq;
    }
}