<h1>BIENVENIDO AL CONECTOR DE DOLIPOST</h1>

Aqui vamos a meter el conector para la api directamente!!
<?php 
$url = "https://api.stressgijon.com/clientes?token=281aa9cccb7713d4f54bb675567bfe6c";
$pagina_inicio = file_get_contents($url);

$pagina=json_decode($pagina_inicio);

print_r($pagina);
?>