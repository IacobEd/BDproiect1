<?php

class product{

    public $con;
    public  static $instance = null;

    public function procedure()
    {
        $drop = "DROP PROCEDURE IF EXISTS getProduct;";
        $sql = "CREATE PROCEDURE getProduct(in intID INT)
                SELECT * FROM product WHERE ID = intID";
        $this->con->query($drop);
        $this->con->query($sql);

        $drop = "DROP PROCEDURE IF EXISTS deleteProduct;";
        $sql = "CREATE PROCEDURE deleteProduct(in intID int)
                DELETE FROM product WHERE ID = intID;";
        $this->con->query($drop);
        $this->con->query($sql);

        $drop = "DROP PROCEDURE IF EXISTS getProductChange;";
        $sql = "CREATE PROCEDURE getProductChange()
                SELECT * FROM product_change;";
        $this->con->query($drop);
        $this->con->query($sql);

        $drop = "DROP PROCEDURE IF EXISTS updateProduct;";
        $sql = "CREATE PROCEDURE updateProduct(IN intID INT, IN strProductName VARCHAR(30), IN intPrice INT, IN textImage TEXT, 
                IN textProducer TEXT, IN intMemory INT, IN textColor TEXT)
                UPDATE product SET productName = strProductName,  price = intPrice, image = textImage,
                producer = textProducer, memory = intMemory, color = textColor, WHERE id = intID;";
        $this->con->query($drop);
        $this->con->query($sql);
    }

    public function trigger(){
        $drop = "DROP TRIGGER IF EXISTS beforeInsert;";
        $sql = "CREATE TRIGGER beforeInsert BEFORE INSERT ON product FOR EACH ROW
                BEGIN
                SET NEW.productName = CONCAT('NEW!', NEW.productName);
                INSERT INTO product_change(productName, price, producer, memory, color, operation, operation_time) VALUES
                NEW.productName, NEW.price, NEW.image, NEW.producer, NEW.memory, NEW.color, 'INSERT', now());
                END;";
        $this->con->query($drop);
        $this->con->query($sql);

        $drop = "DROP TRIGGER IF EXISTS beforeUpdate;";
        $sql = "CREATE TRIGGER beforeUpdate BEFORE UPDATE ON product FOR EACH ROW
                INSERT INTO product_change(productName, price, image, producer, memory, color, operation, operation_time)
                VALUES(OLD.productName, OLD.price, OLD.image, OLD.producer, OLD.memory, OLD.color, 'UPDATE', now());";
        $this->con->query($drop);
        $this->con->query($sql);

        $drop = "DROP TRIGGER IF EXISTS afterDelete;";
        $sql = "CREATE TRIGGER afterDelete AFTER DELETE ON product FOR EACH ROW
                INSERT INTO product_change(productName, price, image, producer, memory, color, operation, operation_time)
                VALUES(OLD.productName, OLD.price, OLD.image, OLD.producer, OLD.memory, OLD.color, 'DELETE', now());";
        $this->con->query($drop);
        $this->con->query($sql);
    }

    public function getProduct($id){
        $query = $this->con->query("CALL getProduct('".$id."')");
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function updateProduct($product){
        $prod = $this->con->prepare("CALL updateProduct('".$product['productName']."','".$product['price']."','"
                .$product['image']."','".$product['producer']."','".$product['memory']."','".$product['color']
                ."','".$product['id']."')");
        $prod->execute();
    }

    public function getProductChange(){
        $query = $this->con->query("CALL getProductChange()");
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function deleteProduct($id){
        $this->con->prepare("CALL deleteProduct('".$id."')")->execute();
    }

    public function insertProduct($product){
        $prod = $this->con->prepare("CALL insertProduct('".$product['productName']."','".$product['price']."','"
            .$product['image']."','".$product['producer']."','".$product['memory']."','".$product['color']
            ."')");
        $prod->execute();
    }

    private function _construct(){
        $host = 'localhost';
        $dbms = 'mysql';
        $db = 'product';
        $user = 'root';
        $pass = '';
        $dsn = "$dbms:host=$host;dbname=$db";
        $this->con = new PDO($dsn, $user, $pass);
        $this->procedure();
        $this->trigger();
    }
}