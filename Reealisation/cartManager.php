<?php
include "cart.php";
include "cartLine.php";
include "productClass.php";


class CartManager {

    public $name ;

    private $Connection = Null;

    private function getConnection(){
      
            $this->Connection = mysqli_connect('localhost', 'oussama', '678dhHQ)oQZVte!H', 'site-e-commerce');
           
         
       
        
        return $this->Connection;
    }


  public function initCode() {
    if(!isset($_COOKIE['cartCookie']))
    {
        $expire=time() + (86400 * 30);//however long you want
        $cookieId = uniqid();
        setcookie('cartCookie', $cookieId, $expire);
        $_SESSION["product"] = array();
        $_SESSION["quantity"] = 0;
        $_SESSION["product"] = array();
        $this->addCartCookie($cookieId);
    }
  }
    
    // Add product to cart
    public function addProduct($cart, $product, $quantity){
        $cartId = $cart->getId();
        $productId = $product->getId();
        $sql = "INSERT INTO chariot_line(idProduct,idChariot, productChariotqnt) VALUES('$productId', '$cartId', '$quantity')";
        $result = mysqli_query($this->getConnection(), $sql);
        if($result){
            $this->getConnection()->close();
        }

    }

    public function getCartLine($id){
        $sql = "SELECT * FROM chariot_line INNER JOIN product on product.id_product=chariot_line.idProduct WHERE idChariot='$id'";
        $query = mysqli_query($this->getConnection(), $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        
       
        $cartLineList = array();
        foreach($result as $value){
            $product = new Product();
            $cartLine = new CartLine();
            $cartLine->setIdCartLine($value['idChariotline']);
            $cartLine->setIdCart($value['idChariot']);
            $cartLine->setIdProduct($value['idProduct']);
            $cartLine->setProductCartQuantity($value['productChariotqnt']);
            $product->setId($value['id_product']);
            $product->setName($value['Nom_product']);
            $product->setPrice($value['prix']);
            $product->setDescription($value['description']);
            $product->setDateOfExpiration($value["date"]);
            $product->setQuantity($value['quantite']);
            $product->setCategory($value['categorie_product']);
            $product->setImage($value["photo"]);   

            $cartLine->setProduct($product);
            array_push($cartLineList, $cartLine);
        }
        return $cartLineList;
    }
    
    // pour ajouter session
    public function set($cart, $product, $quantity){
        session_start();
        $_SESSION["cart"] = $cart;
        array_push($_SESSION["product"], $product);
        if(!isset($_SESSION["quantity"])){
            $_SESSION["quantity"] = 0;
        }
        $_SESSION["quantity"] += $quantity; 

    }

      // afficher session

      public function getCartProducts($cartId){

        $sql = "SELECT * FROM  INNER JOIN produit on chariot.idchariot = produit.id_produit WHERE idCart = $cartId";
        $query = mysqli_query($this->getConnection(), $sql);
        $result =  mysqli_fetch_all($query, MYSQLI_ASSOC);
        return $result;
        $product = new Product();
        $productsList = array();
        foreach ($result as $value_Data) {
            $product->setId($value_Data['id_produit']);
            $product->setName($value_Data['nom_produit']);
            $product->setPrice($value_Data['prix']);
            $product->setDescription($value_Data['description']);
            $product->setDateOfExpiration($value_Data["date_d'expiration"]);
            $product->setQuantity($value_Data['quantite_stock']);
            $product->setCategory($value_Data['categorie_produit']);
            array_push($productsList, $product);
        }
          return $productsList;
        // if(isset($_SESSION["product"])){
        //     return $_SESSION["product"];
        // }

      }

      public function getCartQuantity(){
          if(isset($_SESSION["quantity"])){
              return $_SESSION["quantity"];
          }
      }

          //supprimer session
    public function delete($id){
        if(isset($_SESSION["paniers"]["products"][$id])){
            unset($_SESSION["paniers"]["products"][$id]);
        }
    }

    
    // pour afficher  session 
    public function getProductCart($idchariot){
        $sql = "SELECT * FROM chariot INNER JOIN produit on chariot.idProduct = produit.id_produit WHERE idchariot = $idchariot";
        $query = mysqli_query($this->getConnection(),$sql);
        $result = mysqli_fetch_assoc($query);

        $cartLine = new cartLine();
        $cartLine->setIdchariot($result['idChariotline']);
        $cartLine->setIdCart($result['idChariot']);
        $cartLine->setIdProduct($result['idProduct']);
        $cartLine->setProductCartQuantity($result['productChariotqnt']);
        
        $product = new Product();
        $product->setId($result['id_product']);
        $product->setName($result['Nom_product']);
        $product->setPrice($result['prix']);
        $product->setDescription($result['description']);
        $product->setDateOfExpiration($result["date"]);
        $product->setQuantity($result['quantite']);
        $product->setCategory($result['categorie_product']);
        $product->setImage($result['photo']);

        $cartLine->setProduct($product);

        return $cartLine;
    }

    // Edit  cart line
    public function editCartLine($idCartLine, $quantity){
        $sql = "UPDATE chariot_line SET productChariotqnt = '$quantity' WHERE idChariotLine=$idCartLine";
        mysqli_query($this->getConnection(), $sql);
        
    }

  
  

// afficher  les produits : page index
    
public function getAllProducts(){
    $SelctRow = "SELECT * FROM product INNER JOIN categorie ON product.categorie_product = categorie.idCategorie ";
                                    
    $query = mysqli_query($this->getConnection() ,$SelctRow);
    $produits_data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $TableData = array();
    foreach ( $produits_data as $value_Data) {
              
                
                $product = new Product();
                $product->setId($value_Data['id_product']);
                $product->setName($value_Data['Nom_product']);
                $product->setPrice($value_Data['prix']);
                $product->setDescription($value_Data['description']);
                $product->setDateOfExpiration($value_Data["date"]);
                $product->setQuantity($value_Data['quantite']);
                $product->setCategory($value_Data['categorie_product']); 
                $product->setImage($value_Data["photo"]);   
                array_push($TableData, $product);
               
            }
         
         
               return $TableData;
}
 
        
// afficher  les produits : page panier

        public function afficherProduit($id){
            $SelctRow =  "SELECT * FROM product
            INNER JOIN categorie ON product.categorie_product = categorie.idCategorie where id_product = '$id' ";
            $query = mysqli_query($this->getConnection() ,$SelctRow);
            $produits_data = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $product = new Product();

            
            foreach ($produits_data as $value) {
            $product->setId($value['id_product']);
            $product->setName($value['Nom_product']);
            $product->setPrice($value['prix']);
            $product->setDescription($value['description']);
            $product->setDateOfExpiration($value["date"]);
            $product->setQuantity($value['quantite']);
            $product->setCategory($value['categorie_product']);
            $product->setImage($value["photo"]);   

               
            }
              return $product;
        }
      
 

        function compteur(){ 
        if(isset($_SESSION["paniers"]) != null){
                $compteur = count($_SESSION["paniers"]["products"]) ;
            
            }else {
                $compteur = 0;
            
            }
            return $compteur;
        }

        function addCartCookie($cookie){
            $sql = "INSERT INTO chariot(userReference) VALUES('$cookie')";
            mysqli_query($this->getConnection(), $sql);
        }

        function getCart($userRefe){
            $sql = "SELECT * from chariot WHERE userReference = '$userRefe'";
            $query = mysqli_query($this->getConnection(), $sql);
            $result = mysqli_fetch_assoc($query);

            
            $cart = new Cart();
            $cart->setId($result["id"]);
            $cart->setUserReference($result["userReference"]);

            $cartLine = $this->getCartLine($cart->getId());
            $cart->setCartLineList($cartLine);
            return $cart;
        }
    }
