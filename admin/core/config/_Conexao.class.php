<?php 
class Conexao{
    
    public static $instance;
    
    private function __construct() {
        throw new Exception("Essa Classe não deve ser instanciada !", 1);
    }
    
    public static function getInstance() {
        
        include_once("db_definitions.php");

        if (!isset(self::$instance)) {
            try {
                self::$instance->exec("SET CHARACTER SET utf8");
                self::$instance = new PDO("$drive:host=$servidor;dbname=$banco;", $usuario, $senha); 
            } catch (PDOException $e){ 
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        return self::$instance;
    }


}
?>