<?php 
        header('content-type: application/json; charset=utf-8');
 
        $data['ip'] = $_GET['ip'];
        $data['html'] = $_GET['html'];
       
        echo $_GET['callback'] . '('.json_encode($data).')';
?>