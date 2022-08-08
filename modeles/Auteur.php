<?php
class Auteur {
    
    /**
     * numero de l'auteur
     *
     * @var int
     */
    private $num;

    /**
     * nom de l'auteur
     *
     * @var string
     */
    private $nom;

    /**
     * prenom de l'auteur
     *
     * @var string
     */
    private $prenom;

    /**
     * num nationalite (clé étrangère) relié à num de Nationalite
     *
     * @var int
     */
    private $numNationalite;

    /**
     * Get the value of num
     */ 
    public function getNum()
    {
    return $this->num;
    }

        /**
     * Set numéro de la auteur
     *
     * @param  int  $num  numéro de l'auteur
     *
     * @return  self
     */ 
    public function setNum(int $num) :self
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get the value of nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nom de l'auteur
     *
     * @param  string  $nom  nom de l'auteur
     *
     * @return  self
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set prenom de l'auteur
     *
     * @param  string  $prenom prenom de l'auteur
     *
     * @return  self
     */
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    } 

    /**
     * renvoie l'objet nationalite associé
     *
     * @return Nationanite
     */
    public function getNationalite() : Nationalite
    {
        return Nationalite::findById($this->numNationalite);
    }

    /**
     * ecrit le num nationalite
     *
     * @param Nationalite $nationalite
     * @return self
     */
    public function setNationalite(Nationalite $nationalite) : self
    {
        $this->numNationalite = $nationalite->getNum();

        return $this;
    }

    /**
     * Retourne l'ensemble des auteurs
     *
     * @return Auteur[] tableau d'objet Auteur
     */
    public static function findAll(?string $nom="", ?string $prenom = "", ?string $nationalite="Toutes") :array
    {
        $texteReq="select a.num as numero, a.nom , a.prenom, n.libelle from auteur a, nationalite n where a.numnationalite=n.num";
        if ($nom != "") {
            $texteReq .= " and a.nom like '%" . $nom . "%'";
        }
        if ($prenom != "") {
            $texteReq .= " and a.prenom like '%" . $prenom . "%'";
        }
        if ($nationalite != "Toutes") {
            $texteReq .= " and n.num =" . $nationalite;
        }
        $texteReq.=" order by a.nom";
        $req = MonPdo::getInstance()->prepare($texteReq);
        $req->setFetchMode(PDO::FETCH_OBJ);
        $req->execute();
        $lesResultats=$req->fetchAll();
        return $lesResultats;
    }

    /**
     * Trouve une auteur par son num
     *
     * @param integer $id numéro de l'auteur
     * @return Auteur objet auteur trouvé
     */
    public static function findById(int $id) :Auteur
    {
        $req=MonPdo::getInstance()->prepare("Select * from auteur where num= :id");
        $req->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'Auteur');
        $req->bindParam(':id', $id);
        $req->execute();
        $leResultats=$req->fetch();
        return $leResultats;
    }

    /**
     * Permet d'ajouter un auteur
     *
     * @param Nationalité $auteur continent à ajouter
     * @return integer resultat (1 si l'opération a réussi, 0 sinon)
     */
    public static function add(Auteur $auteur) :int
    {
        $req=MonPdo::getInstance()->prepare("insert into auteur (nom,prenom,numNationalite) values(:nom, :prenom, :numNationalite)");
        $req->bindParam(':nom', $auteur->getNom());
        $req->bindParam(':prenom', $auteur->getPrenom());
        $req->bindParam(':numNationalite', $auteur->numNationalite);
        $nb=$req->execute();
        return $nb;  
    }

    /**
     * permet de modifier un auteur
     *
     * @param Auteur $auteur auteur à modifier
     * @return integer resultat (1 si l'opération a réussi, 0 sinon)
     */
    public static function update(Auteur $auteur) :int
    {
        $req=MonPdo::getInstance()->prepare("update auteur set nom= :nom, prenom= :prenom, numNationalite= :numNationalite where num= :id");
        $req->bindParam(':id', $auteur->getNum());
        $req->bindParam(':nom', $auteur->getNom());
        $req->bindParam(':prenom', $auteur->getPrenom());
        $req->bindParam(':numNationalite', $auteur->numNationalite);
        $nb=$req->execute();
        return $nb;  
    }

    /**
     * Permet de supprimer un auteur
     *
     * @param Auteur $auteur
     * @return integer
     */
    public static function delete(Auteur $auteur) :int
    {
        $req=MonPdo::getInstance()->prepare("delete from auteur where num= :id");
        $req->bindParam(':id', $auteur->getNum());
        $nb=$req->execute();
        return $nb; 
    }

        public static function nombreAuteurs():int
    {
        $texteReq="select count(*) as 'nb' from auteur";
        $req=MonPdo::getInstance()->prepare($texteReq);
        $req->execute();
        $leResultat=$req->fetch();
        return $leResultat[0];
    }


}