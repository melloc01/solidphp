<?php  
class Conexao{
    
    public static $instance;
    
    private function __construct() {
        throw new Exception("Essa Classe não deve ser instanciada !", 1);
    }
    
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
                self::$instance = new PDO( DB_DRIVER.":host=".DB_HOST.";dbname=".DB_SCHEMA_NAME.";", DB_USER, DB_USER_PASSWORD); 
                self::$instance->exec("SET CHARACTER SET utf8");
        }
        return self::$instance;
    }

    public static function getInstanceNoDB() {
        
        if (!isset(self::$instance)) {
                self::$instance = new PDO( DB_DRIVER.":host=".DB_HOST.";", DB_USER, DB_USER_PASSWORD); 
                self::$instance->exec("SET CHARACTER SET utf8");
        }
        return self::$instance;
    }


}
?>