<?php 

//AQUI LLAMO AL PROCESO QUE TENEMOS QUE HACER PARA ACTUALIZAR DOLIBARR

$dbData = array();
$dbData['actualizar'] = 0;
$wpdb->update('conector', $dbData, array('id_dolibar' =>$iddolibar));


?>
