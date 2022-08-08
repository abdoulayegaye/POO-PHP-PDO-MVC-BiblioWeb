<?php
class Nationalite {
    
    /**
     * numero du nationalité
     *
     * @var int
     */
    private $num;

    /**
     * Libelle du nationalité
     *
     * @var string
     */
    private $libelle;

    /**
     * num continent (clé étrangère) relié à num de Continent
     *
     * @var int
     */
    private $numContinent;

    /**
     * Get the value of num
     */ 
    public function getNum()
    {
    return $this->num;
    }

        /**
     * Set numéro de la nationalite
     *
     * @param  int  $num  numéro de la nationalité
     *
     * @return  self
     */ 
    public function setNum(int $num) :self
    {
        $this->num = $num;

        return $this;
    }
    
    /**
     * Lit le libelle
     *
     * @return string
     */
    public function getLibelle() : string
    {
    return $this->libelle;
    }

    /**
     * ecrit dans le libellé
     *
     * @param string $libelle
     * @return self
     */
    public function setLibelle(string $libelle) : self
    {
    $this->libelle = $libelle;

    return $this;
    }

    
    /**
     * renvoie l'objet continent associé
     *
     * @return Continent
     */
    public function getContinent() : Continent
    {
        return Continent::findById($this->numContinent);
    }

    /**
     * ecrit le num continent
     *
     * @param Continent $continent
     * @return self
     */
    public function setContinent(Continent $continent) : self
    {
        $this->numContinent = $continent->getNum();

        return $this;
    }

    /**
     * Retourne l'ensemble des nationalités
     *
     * @return Nationalite[] tableau d'objet Nationalité
     */
    public static function findAll(?string $libelle="", ?string $continent="Tous") :array
    {
        $texteReq="select n.num as numero, n.libelle as 'libNation', c.libelle as 'libContinent'  from nationalite n, continent c where n.numContinent=c.num";
        if ($libelle != "") {
            $texteReq .= " and n.libelle like '%" . $libelle . "%'";
        }
        if ($continent != "Tous") {
            $texteReq .= " and c.num =" . $continent;
        }
        $texteReq.=" order by n.libelle";
        $req = MonPdo::getInstance()->prepare($texteReq);
        $req->setFetchMode(PDO::FETCH_OBJ);
        $req->execute();
        $lesResultats=$req->fetchAll();
        return $lesResultats;
    }

    /**
     * Trouve une nationalité par son num
     *
     * @param integer $id numéro du nationalité
     * @return Nationalite objet nationalité trouvé
     */
    public static function findById(int $id) :Nationalite
    {
        $req=MonPdo::getInstance()->prepare("Select * from nationalite where num= :id");
        $req->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'Nationalite');
        $req->bindParam(':id', $id);
        $req->execute();
        $leResultats=$req->fetch();
        return $leResultats;
    }

    /**
     * Permet d'ajouter une nationalité
     *
     * @param Nationalité $nationalité continent à ajouter
     * @return integer resultat (1 si l'opération a réussi, 0 sinon)
     */
    public static function add(Nationalite $nationalite) :int
    {
        $req=MonPdo::getInstance()->prepare("insert into nationalite (libelle,numContinent) values(:libelle, :numContinent)");
        $req->bindParam(':libelle', $nationalite->getLibelle());
        $req->bindParam(':numContinent', $nationalite->numContinent);
        $nb=$req->execute();
        return $nb;  
    }

    /**
     * permet de modifier une nationalité
     *
     * @param Nationalite $nationalité continent à modifier
     * @return integer resultat (1 si l'opération a réussi, 0 sinon)
     */
    public static function update(Nationalite $nationalite) :int
    {
        $req=MonPdo::getInstance()->prepare("update nationalite set libelle= :libelle, numContinent= :numContinent where num= :id");
        $req->bindParam(':id', $nationalite->getNum());
        $req->bindParam(':libelle', $nationalite->getLibelle());
        $req->bindParam(':numContinent', $nationalite->numContinent);
        $nb=$req->execute();
        return $nb;  
    }

    /**
     * Permet de supprimer une nationalité
     *
     * @param Nationalite $nationalite
     * @return integer
     */
    public static function delete(Nationalite $nationalite) :int
    {
        $req=MonPdo::getInstance()->prepare("delete from nationalite where num= :id");
        $req->bindParam(':id', $nationalite->getNum());
        $nb=$req->execute();
        return $nb; 
    }


}