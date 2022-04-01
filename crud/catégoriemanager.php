<?php
 include 'catégorie.php';
class GestionCatégorie{

    private $Connection = Null;

    private function getConnection(){
      
            $this->Connection = mysqli_connect('localhost', 'oussama', '678dhHQ)oQZVte!H', 'site-e-commerce');
           
         
       
        
        return $this->Connection;
        
    }
    
    public function Ajouter($catégorie){

        $Nom = $catégorie->getNom();
        $descriptions = $catégorie->getDescriptions();
        
        // requête SQL
        $insertRow="INSERT INTO produit(Nom, descriptions) 
                                VALUES('$Nom', '$descriptions')";

        mysqli_query($this->getConnection(), $insertRow);
    }

    

    public function afficher(){
        $SelctRow = 'SELECT id,Nom,descriptions FROM produit';
        $query = mysqli_query($this->getConnection() ,$SelctRow);
        $catégorie_data = mysqli_fetch_all($query, MYSQLI_ASSOC);

        $TableData = array();
        foreach ($catégorie_data as $value_Data) {
            $catégorie = new Catégorie();
            $catégorie->setId($value_Data['id']);
            $catégorie->setNom($value_Data['Nom']);
            $catégorie->setDescriptions ($value_Data['descriptions']);
            array_push($TableData, $catégorie);
        }
        return $TableData;
    }


    public function RechercherParId($id){
        $SelectRowId = "SELECT * FROM produit WHERE id= $id";
        $result = mysqli_query($this->getConnection(),  $SelectRowId);
        // Récupère une ligne de résultat sous forme de tableau associatif
        $catégorie_data = mysqli_fetch_assoc($result);
        $catégorie = new Catégorie();
        $catégorie->setId($catégorie_data['id']);
        $catégorie->setNom($catégorie_data['Nom']);
        $catégorie->setDescriptions ($catégorie_data['Descriptions']);
        
        
        return $catégorie;
    }

    public function delete($id){
        $RowDelet = "DELETE FROM produit WHERE id= '$id'";
        mysqli_query($this->getConnection(), $RowDelet);
    }

    public function edit($id,$Nom,$descriptions){
        // Requête SQL
        $RowUpdate = "UPDATE produit SET 
        Nom='$Nom', descriptions='$descriptions'
        WHERE id=$id";

        mysqli_query($this->getConnection(),$RowUpdate);

    }

}
?>