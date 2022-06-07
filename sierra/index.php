 <?php
 /**
* @link https://girol.es
* @since 1.0.0
* @package producto conector
* Plugin Name: producto conector
* Plugin URI: https://girol.es
* Description: producto conector
* Name: giurol
* Version: 1.0.0
* Author: Daniel Alvarez
* Author URI: https://girol
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain: producto conector
*/





add_action("wp_enqueue_scripts", "dcms_insertar_js");

function dcms_insertar_js(){
    
    wp_register_script('miscript', plugin_dir_url( __FILE__ ) . 'js/insertarplugin.js', array('jquery'), '1.0' );
    wp_enqueue_script('miscript');
    
}




add_action('wp_ajax_get_ajax_posts', 'tabla_de_articulos');
add_action('wp_ajax_nopriv_get_ajax_posts', 'tabla_de_articulos');



//wp_schedule_event(time(), '5min', 'tabla_de_articulos');

/*DANI 
add_filter( 'cron_schedules', 'cyb_cron_schedules');

function cyb_cron_schedules( $schedules ) {
    wp_schedule_event(time(), '5min', 'tabla_de_articulos');
    
}
*/
/* FIN DANI*/
 





add_action('admin_menu', 'test_plugin_setup_menu_conector');
 
function test_plugin_setup_menu_conector(){
    add_menu_page( 'CONECTOR GIROL', 'Girol CONECTOR', 'manage_options', 'menu-importador', 'menu_importador' );
	add_submenu_page(NULL,'Ayuda','Ayuda','manage_options','tabla_de_articulos','tabla_de_articulos');
	add_submenu_page(NULL,'Ayuda','Ayuda','manage_options','cambiarexistencias','cambiarexistencias');
	
} 




add_action('init', 'insertarproductos');
function insertarproductos(){
	
	
	
	
    global $wpdb;
	
	$sql3="Select * from conectorhoras order by id desc";
	
	$total=$wpdb->get_results($sql3);
	
	//print_r($total);
	//echo '<br>';
	
	//echo $total[0]->hora;
	//echo '<br>';
	//echo $total[0]->date;
	//echo '<br>';
	
	
$date1 = new DateTime($total[0]->mifecha);
$date2 = new DateTime(date('Y-m-d H:i:s'));
$diff = $date1->diff($date2);
// will output 2 days
	


	
	if($diff->h>=1){
	//if(5==5){
			
		
		
$wpdb->insert('conectorhoras', array(
    'mifecha' => date('Y-m-d H:i:s')
));
		
		$data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/chg_m?api_key=PAoSkU7u4LlIcF");
	

       $products = json_decode($data, true);

    
		
	
		
	foreach ($products['chg_m'] as $product=>$productito) {
		
		
		
		
		//$data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
	

		
		
//echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF";
	
//$products = json_decode($data, true);
		
		
	
		
	//	echo $productito['id_chg'];
	//	echo '<br>';
		
		$sql4="Select * from conector where id_chg='".$productito['id_chg']."' and state='PENDIENTE' and tipo='".$productito['tipo']."'";
	//echo '<h1>';	echo $sql4;
	//	echo '</h1>';
		
	$total=$wpdb->get_results($sql4);
	//echo $total;	
		

	 $total=count($total);
	
		//echo $total;
		
		if($total==0){

			
$wpdb->insert('conector', array(
    'id_chg' => $productito['id_chg'],
    'tabla' => $productito['tabla'],
	'tipo'=>$productito['tipo'],
	'fch'=>$productito['fch'],
	'state'=>'PENDIENTE'	
));	
		
	
		
	$eliminacion = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/_process/baj_id_chg?param%5Bbody%5D=".$productito['id']."&api_key=PAoSkU7u4LlIcF");	
			
//	echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/_process/baj_id_chg?param%5Bbody%5D=".$productito['id']."&api_key=PAoSkU7u4LlIcF";		
			
			
	}else{	

$eliminacion = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/_process/baj_id_chg?param%5Bbody%5D=".$productito['id']."&api_key=PAoSkU7u4LlIcF");			
			
			
	echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/_process/baj_id_chg?param%5Bbody%5D=".$productito['id']."&api_key=PAoSkU7u4LlIcF";		
/*$eliminacion = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/_process/baj_id_chg?param%5Bbody%5D=".$productito['id']."&api_key=PAoSkU7u4LlIcF");	
	*/	
	//	echo 'No entra';
	}
		
	}
	}
	
	
	/*if($total[0]->date>=){
		
		
	}*/
	
	//echo date('H:i:s');
	
	/*
	echo $wpdb->insert('conector', array(
    'id_producto' => '48',
    'fecha_modificacion' => date('Y-m-d h:i:s')
));
	*/
/*	
echo $wpdb->insert('conectorhoras', array(
    'hora' => date('H:i:s'),
    'date' => date('Y-m-d')
));
	*/		
	
	
}

add_action('init', 'pasarcuandomedelagana');
add_action('tabla_de_articulos','tabla_de_articulos');
add_action('busqueda','busqueda');
add_action('insercion','insercion');

add_action('init', 'eliminarlosnovisibles');
function eliminarlosnovisibles(){
    
    /*
    des_1 destacado1
    new novedad
    des_2 destacado2
    */
    /*
    global $wpdb;
	
	$sql3="Select * from conector where state='PENDIENTE' order by id desc limit 0,1";
	
	$total=$wpdb->get_results($sql3);
	
	//print_r($total);
	//echo '<br>';
	
	//echo $total[0]->hora;
	//echo '<br>';
	//echo $total[0]->date;
	//echo '<br>';
	

// will output 2 days
	


foreach($total as $totalisimo){
    
    //print_r($totalisimo);
    
    $data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/".$totalisimo->id_chg."?api_key=PAoSkU7u4LlIcF");
    
    $products = json_decode($data, true);
    		
	foreach ($products as $product=>$productito) {
      print_r($product);
        print_r($productito);
        //print_r($productito);
    echo $productito['web_vis'];
        echo '<hr>';
        echo 'DANI';
        
    
    }
    
}	*/
    
    
}




function insercion(){
	

	//echo "Importando marica";
	
	global $wpdb;
	
	

	
	
	$id_programa=$_GET['id_insercion'];


		

	$id_articulo=$id_programa;
	
	
	
	
	$data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
	
echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF";
	
$products = json_decode($data, true);

	//$contador='1';
	
	
		
			$sql3 = "SELECT * FROM wp_postmeta WHERE meta_value='".$id_articulo."' AND meta_key='id_programa';";
	
	$resultados3=$wpdb->get_results($sql3);
	
			
if(count($resultados3)>0){
	foreach($resultados3 as $resul){
	wp_delete_post($resul->post_id);
		
	echo 'Se ha actualizado el producto';	
	}
	
}
foreach ($products['art_m'] as $product=>$productito) {
    
    
    if($productito['web_vis']==true){
        
    
    
    

	echo '<br>';
	//print_r($product);
	
	//print_r($productito);
	echo $productito['name'];
	
	
	if($productito['img']==''){}else{	
$data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ent_m?filter%5Bid_es_prv%5D=".$productito['prv']."&api_key=PAoSkU7u4LlIcF");
	
        
        
$marca = json_decode($data, true);
        
$mimarca='';
	foreach ($marca['ent_m'] as $marquita=>$marc) {
		
		//echo '<h1>'.$marc['nom_com'].'</h1>';
		//$mimarca=$marc['nom_com'];
$mimarca=$marc['ali'];

        //print_r($marc);
		
	}
        
			$product_1=array( 'post_title' => $productito['name'],
'post_content' => $productito['dsc'],
'post_status' => 'publish',
'post_type' => "product",
'post_name'=>$productito['name'].'-'.$productito['mod_fab'].'-'.$mimarca);

$post_id = wp_insert_post( $product_1 );
wp_set_object_terms( $post_id, 'simple', 'product_type' );
        
 $foo = $productito['name']; 
    
    $new=$productito['new'];
if($productito['new']==true){        
wp_set_object_terms($post_id, 'NOVEDAD', 'product_tag', true);
}
        
if($productito['des_1']==true){        
wp_set_object_terms($post_id, 'DESTACADO', 'product_tag', true);
}

if($productito['des_2']==true){        
wp_set_object_terms($post_id, 'DESTACADO 2', 'product_tag', true);
}
    
        
if (strpos($foo, 'MOCASIN') !== false) {
    echo 'true';
    
    wp_set_object_terms($post_id, 'MOCASIN', 'product_tag', true);
    
}   
        if (strpos($foo, 'BLUCHER') !== false) {
    echo 'true';
    
    wp_set_object_terms($post_id, 'BLUCHER', 'product_tag', true);
    
}     
        
	/*
	5	SEÑORA ANCHO ESPECIAL
2	CABALLERO SPORT
4	SEÑORA SPORT
0	BOLSOS
3	SEÑORA VESTIR
1	CABALLERO VESTIR
6	UNISEX
7	CINTURONES
*/$categoria='';

	
/*CABALLERO VESTIR 1
CABALLERO SPORT 2
SEÑORA VESTIR 3
SEÑORA SPORT 4
SEÑORA ANCHO ESPECIAL 5
UNISEX 6
CINTURONES 7

*/
		
	/*	ZAPATO 2
SANDALIA 4
BOTIN 6
BOTA 7
ZAPATILLA CASA 8	
	*/		
		
		echo '<h1>'.$productito['sec'].'</h1>';
	if($productito['sec']=='1'){
		
	
			wp_set_object_terms($post_id, 120, 'product_cat', true);
	
		$categoria='CABALLERO VESTIR';
		
	}
	if($productito['sec']=='2'){
		
		
			wp_set_object_terms($post_id, 120, 'product_cat', true);
		$categoria='CABALLERO SPORT';
		
		
	}
	if($productito['sec']=='3'){
			
			wp_set_object_terms($post_id, 121, 'product_cat', true);
		
		$categoria='SEÑORA VESTIR';
		
	}
	if($productito['sec']=='4'){
		
		
			wp_set_object_terms($post_id, 121, 'product_cat', true);
		$categoria='SEÑORA SPORT';
	}
	if($productito['sec']=='5'){
			wp_set_object_terms($post_id, 121, 'product_cat', true);
		$categoria='SEÑORA ANCHO ESPECIAL';
	}

	if($productito['sec']=='6'){
		

		
			wp_set_object_terms($post_id, 123, 'product_cat', true);
			$categoria='UNISEX';
		
	}
	if($productito['sec']=='7'){

		wp_set_object_terms($post_id, 117, 'product_cat', true);
			$categoria='CINTURONES';
		
	}
	if($productito['sec']=='0'){

		wp_set_object_terms($post_id, 49, 'product_cat', true);
			$categoria='BOLSOS';
		
	}	
	
	
	//wp_set_object_terms( $post_id, 'simple', 'product_type' );
	 // ID of Post
/*$tags = array('Mango', 'Apple', 'Banana'); // Array of Tags to add
wp_set_post_tags( $post_id, $tags); // Set tags to Post
	
	*/
		
		

		
$data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ent_m?filter%5Bid_es_prv%5D=".$productito['prv']."&api_key=PAoSkU7u4LlIcF");

    echo 'MARCA DANI';    
        
        echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ent_m?filter%5Bid_es_prv%5D=".$productito['prv']."&api_key=PAoSkU7u4LlIcF";
        echo $data;
        echo '<hr>';
        
        
        
		$color = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/con_m/".$productito['con']."?api_key=PAoSkU7u4LlIcF");
		
		/*$material = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/con_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
*/
		$suela=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/sue_m/".$productito['sue']."?api_key=PAoSkU7u4LlIcF");
		echo 'SUELA';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/sue_m/".$productito['sue']."?api_key=PAoSkU7u4LlIcF";
		echo '<br>';
		
		$material=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/mat_m/".$productito['mat']."?api_key=PAoSkU7u4LlIcF");
		
		echo 'MATERIAL';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/mat_m/".$productito['mat']."?api_key=PAoSkU7u4LlIcF";
		echo '<br>';
		
		$alturas=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/alt_m/".$productito['alt']."?api_key=PAoSkU7u4LlIcF");
		
		echo 'ALTURA';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/alt_m/".$productito['alt']."?api_key=PAoSkU7u4LlIcF";
		
		echo '<br>';
		$anios=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ano_m/".$productito['ano']."?api_key=PAoSkU7u4LlIcF");
		
		echo 'ANO';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ano_m/".$productito['ano']."?api_key=PAoSkU7u4LlIcF";
		echo '<br>';
		
		
$marca = json_decode($data, true);
$color = json_decode($color, true);	
$suela=json_decode($suela, true);	
$material=json_decode($material, true);	
$alturas=json_decode($alturas,true);
$anios=json_decode($anios,true);
		
		
		
$colornuestro=@$color['con_m'][0]['name'];
$suelanuestra=@$suela['sue_m'][0]['name'];	
$materialnuestro=@$material['mat_m'][0]['name'];
$alturasnuestras=@$alturas['alt_m'][0]['name'];	
$anios=@$anios['ano_m'][0]['name'];		

		
		//print_r($alturas);
echo '<h1>'.$suelanuestra.'</h1>';
		echo '<h1>'.$colornuestro.'</h1>';
		echo '<h1>'.$materialnuestro.'</h1>';
		echo '<h1>'.$alturasnuestras.'</h1>';
		echo '<h1>'.$anios.'</h1>';
$mimarca='';
	foreach ($marca['ent_m'] as $marquita=>$marc) {
		
		//echo '<h1>'.$marc['nom_com'].'</h1>';
		//$mimarca=$marc['nom_com'];
$mimarca=$marc['ali'];
echo $micarma;
        echo '<hr>';
        //print_r($marc);
		
	}	
		
$my_post = array(
    'ID'           => $post_id,
   // 'post_title'   => $productito['name'].' '.$categoria.' '.$productito['mod_fab'].' '.$mimarca, // new title
    'post_title'   => $productito['name'].' '.$productito['mod_fab'].' '.$mimarca,
);

// Update the post into the database
wp_update_post( $my_post );
		
/*	ZAPATO 2
SANDALIA 4
BOTIN 6
BOTA 7
ZAPATILLA CASA 8	
	*/		
	if($productito['fam']=='1'){
		wp_set_object_terms($post_id, 'ZAPATO', 'product_tag', true);
		
		
	}
	if($productito['fam']=='3'){
		wp_set_object_terms($post_id, 'BOTA AGUA', 'product_tag', true);
		
		
	}
		
		if($productito['fam']=='2'){
		wp_set_object_terms($post_id, 'ZAPATO', 'product_tag', true);
		
		
	}
		if($productito['fam']=='4'){
		wp_set_object_terms($post_id, 'SANDALIA', 'product_tag', true);
		
		
	}	
	if($productito['fam']=='6'){
		wp_set_object_terms($post_id, 'BOTIN', 'product_tag', true);
		
		
	}	
		if($productito['fam']=='7'){
		wp_set_object_terms($post_id, 'BOTA', 'product_tag', true);
		
		
	}	
		if($productito['fam']=='8'){
		wp_set_object_terms($post_id, 'ZAPATILLA CASA', 'product_tag', true);
		
		
	}		
	if($productito['fam']=='9'){
		wp_set_object_terms($post_id, 'DEPORTIVO', 'product_tag', true);
		
		
	}		
		
if($mimarca==''){}else{

wp_set_object_terms($post_id, $mimarca, 'product_tag', true);
wp_set_object_terms($post_id, $mimarca, 'pwb-brand', true);
	
}
		
        wp_set_object_terms($post_id, $categoria, 'product_tag', true);
        
        
        if($productito['out']==true){
	 
	 wp_set_object_terms($post_id, 'OUTLET', 'product_tag', true);
	 
 }		
		
if($colornuestro==''){}else{
wp_set_object_terms($post_id, $colornuestro, 'product_tag', true);
	
}
if($suelanuestra==''){}else{
wp_set_object_terms($post_id, $suelanuestra, 'product_tag', true);
	
}
if($materialnuestro==''){}else{
wp_set_object_terms($post_id, $materialnuestro, 'product_tag', true);
	
}	
if($alturasnuestras==''){}else{

	wp_set_object_terms($post_id, $alturasnuestras, 'product_tag', true);
	
	
}	
if($anios==''){}else{

	wp_set_object_terms($post_id, $anios, 'product_tag', true);
	
	
}	
		
		
		
		
update_post_meta( $post_id, '_price', $productito['pvp_tpv'] );
//update_post_meta( $post_id, '_featured', 'yes' );
//update_post_meta( $post_id, '_stock', $productito['exs'] );
update_post_meta( $post_id, '_stock', 4 );		
update_post_meta( $post_id, '_stock_status', 'instock');
update_post_meta( $post_id, '_sku', $productito['ref'] );
update_post_meta( $post_id, 'id_programa', $id_articulo );
	
echo $id_articulo;
	

	
	
$Base64Img = "data:image/png;base64,".$productito['img'];
 
//eliminamos data:image/png; y base64, de la cadena que tenemos
//hay otras formas de hacerlo                   
list(, $Base64Img) = explode(';', $Base64Img);
list(, $Base64Img) = explode(',', $Base64Img);
//Decodificamos $Base64Img codificada en base64.
$Base64Img = base64_decode($Base64Img);
//escribimos la información obtenida en un archivo llamado 
//unodepiera.png para que se cree la imagen correctamente

file_put_contents('../imagenes/'.$productito['name'].'.png', $Base64Img);    
echo "<img src='../imagenes/".$productito['name'].".png' width='150px' />";
			echo 'Se ha insertado correctamente';
	
	
	
	
	
	
$upload_dir       = wp_upload_dir();
  $image_url        = '../imagenes/'.$productito['name'].'.png'; // Define the image URL here

			
    $image_name       = $productito['name'].'.png';
     // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name );	
			
	copy('../imagenes/'.$productito['name'].'.png',$upload_dir['path'].'/'.$unique_file_name);
			
			//echo $upload_dir['path'].'/'.$pro['NAME'].'.png';	
			
			
			
			
 // Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }		
			
  $wp_filetype = wp_check_filetype( $filename, null );
			
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

    // Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );

	
		
	
		
		
    // And finally assign featured image to post
    set_post_thumbnail( $post_id, $attach_id );		
		
	
		
	//IMAGEN2
		
	if($productito['img1']!=''){
	
	
		
		$Base64Img = "data:image/png;base64,".$productito['img1'];
 
//eliminamos data:image/png; y base64, de la cadena que tenemos
//hay otras formas de hacerlo                   
list(, $Base64Img) = explode(';', $Base64Img);
list(, $Base64Img) = explode(',', $Base64Img);
//Decodificamos $Base64Img codificada en base64.
$Base64Img = base64_decode($Base64Img);
//escribimos la información obtenida en un archivo llamado 
//unodepiera.png para que se cree la imagen correctamente
file_put_contents('../imagenes/'.$productito['name'].'-2.png', $Base64Img);    
echo "<img src='../imagenes/".$productito['name']."-2.png' width='150px' />";
			echo 'Se ha insertado correctamente';
	
	
	
	
	
	
$upload_dir       = wp_upload_dir();
  $image_url        = '../imagenes/'.$productito['name'].'-2.png'; // Define the image URL here

			
    $image_name       = $productito['name'].'-2.png';
     // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name );	
			
    copy('../imagenes/'.$productito['name'].'-2.png',$upload_dir['path'].'/'.$unique_file_name);
			
			//echo $upload_dir['path'].'/'.$pro['NAME'].'.png';	
			
			
			
			
 // Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }		
			
  $wp_filetype = wp_check_filetype( $filename, null );
			
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		// Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );
	
	update_post_meta( $post_id, '_product_image_gallery', $attach_id );		
		
	}	
		
	//update_post_meta( $post_id, '_product_image_gallery', $attach_id );		
		
		
		
	//FIN IMAGEN2	
		//$this->insertartallas();
		//INICIO TALLAS 
		
		
		//FIN TALLAS
		
		//https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/14?api_key=PAoSkU7u4LlIcF
		
		
		
		$tallas=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
		
        echo '<h2>';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/".$id_articulo."?api_key=PAoSkU7u4LlIcF";
		echo '</h2>';
$tallas = json_decode($tallas, true);
		
		
		foreach($tallas['exs_g_tyc'] as $talla=>$mitalla){
			
			
		
			$sql_tallas = "SELECT * FROM conector_tallas WHERE id_articulo='".$id_articulo."' AND id_talla='".$mitalla['tll']."';";
	
			echo $sql_tallas;
			echo '<hr>';
			
	$resultados_tallas=$wpdb->get_results($sql_tallas);
		
	$nombretalla=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/tll/".$mitalla['tll']."?api_key=PAoSkU7u4LlIcF");
		
		
		
$nombretalla = json_decode($nombretalla, true);
			
			
		echo '<h2>'.$nombretalla['tll'][0]['name'].'</h2>';			
			echo 'DANIII<h1>'.count($resultados_tallas).'</h1>';
		if(count($resultados_tallas)>0){
			
			echo '<h1>';
			echo 'HAY TALLA INSERTADA';
			echo '</h1>';
			
			print_r($resultados_tallas);
			
			$resultados_tallas[0]->cantidad;
			  $wpdb->update( 'conector_tallas', 
    array( 
      'cantidad' => $resultados_tallas[0]->cantidad+$mitalla['exs']
      //'email' => 'www.andres-dev.com'
    ),
    // Cuando el ID del campo es igual al número 1
    array( 'ID' => $resultados_tallas[0]->id )
  );
			
			
		}else{
		
			
$wpdb->insert('conector_tallas', array(
    'id_articulo' => $id_articulo,
    'id_talla' => $mitalla['tll'],
	'cantidad' => $mitalla['exs'],
	'talla' => $mitalla['tll'],
    'nombre_talla' => $nombretalla['tll'][0]['name'], // ... and so on
));
			
			
		}	
			
			
			
			
			
		}
		
		
		
	$sql_tallas_insertar = "SELECT * FROM conector_tallas WHERE id_articulo='".$id_articulo."'";
	
			//echo $sql_tallas;
			echo '<hr>';
			
	$resultados_tallas_insertar=$wpdb->get_results($sql_tallas_insertar);		
		
		
		
		
		
		foreach($resultados_tallas_insertar as $mitalla){
			
			echo $mitalla->id_talla;
			echo '<br>';
			
			
			 $product = wc_get_product( $post_id );  
	//$precio=$product->get_regular_price();
	/*		echo $product->get_regular_price();echo '<br>';
echo $product->get_sale_price();echo '<br>';*/



			
	 wp_remove_object_terms( $post_id, 'simple', 'product_type' );
     wp_set_object_terms( $post_id, 'variable', 'product_type', true );

	  $product->save();
	$product_new = wc_get_product( $post_id );  
	
	$product = new WC_Product_Variable($post_id);

$attribute = new WC_Product_Attribute();//declaramos nuestro primer atributo
/*$attribute->set_id(4);//le damos una id
$attribute->set_name('Tallas');// y un nombre
*/

//$term = get_term_by( 'slug', 'Yes', 'tallas' );
// Or use $term = get_term_by( 'slug', 'yes', 'pa_foil' );

// Attribute `options` is an array of term IDs.
		$tallitas=array("176","6");	
$options = [ 176,174,196,197,198,199,200,201,202,203,204,205,206,207,208,185,209,210,211,212,213,214,215,216,217,218,219,220,221,222,186,187,188,189,190,191,192,193,194,195,175,172,173,340 ];
			
	//$options = [ $term->term_id ];			
$attribute->set_name('pa_tallas');
$attribute->set_options($options);
			$attribute->set_visible(1);
			$attribute->set_variation(1);
			$attribute->set_id( 1 );
			$attributes['pa_tallas'] = $attribute;
			$product->set_attributes( $attributes );
$product->save();
	/*		
$attribute_object->set_name( 'pa_foil' );
$attribute_object->set_options( $options );
$attribute_object->set_visible( 1 );
$attribute_object->set_variation( 0 );
$attribute_object->set_id( 1 );
$attributes['pa_foil'] = $attribute_object;

$product->set_attributes( $attributes );
$product->save();			
		*/	
			
			
			//echo '<h1>';
//print_r($resultados44);			
			
			
			echo '<br>';
	

			
			
			
			//$tallas=array();
			
			
			
		//$tallasaimportar=array('6M','5M','5','6','1','2','3','4','7','8','9','10','11','12','13','3M','4M','35','36','37','38','39','40','41');	
			//foreach($resultados44 as $result){
	/*foreach($tallasaimportar as $tal){
		$tallas['pa_tallas']=$tal;
		echo $tal;
		echo '<br>';
	}*/
		
			
		echo '<h2>'.$mitalla->nombre_talla.'</h2>';	
		
		
	//}		
	/*		
 $colores = array(
    0  => $resul->nombre_talla,  //este sera los valores del atributo
	1  => 37,
	2  => 36,
	3  => 38,
	4  => 40,
);*/

// $attribute->set_visible(true);
// $attribute->set_variation(true);
// $attribute->set_options($tallas);//le asignamos los valores al atributo




//$product->set_attributes(array($attribute));
//echo $product->save();
	
	
	
	
	//creamos las variables con los datos de nuestra variación

//$sku = date('Ymdhis').rand(0,100);//el sku es como un id no debe repetirse.
//usar un ean13 es una buena opción para evitar duplicados.
			
			
$price = $productito['pvp_tpv'];//precio normal
//$saleprice = "";//precio rebajado
$descripcion = "";
$disponible = "4";//esto es el stock

$artributo1 = $mitalla->nombre_talla;


$descAtr1 = "";
$descAtr2 = "";


$variation = new WC_Product_Variation();//creamos la variación

//creamos una descripción para la misma
$descripccion_total = "";

//Es importante asignarle la id de el producto al que pertenece
$variation->set_parent_id($post_id);

//Aquí le añadimos los valores del atributo que hereda del producto, si nuestro producto tiene dos atributos
//nuestra variación tendrá que tener un valor que pertenezca dicho producto
//si tienes dudas sobre esto te recomiendo que mires el siguiente post que habla sobre ello



//$variation->set_attributes(array('pa_tallas' => $nombretalla['tll'][0]['name']));
	$variation->set_attributes(array('pa_tallas' => strtolower($mitalla->nombre_talla)));	
		//$variation->set_attributes( array( $taxonomy => $term->slug ) );	
			//array( $taxonomy => $term->slug ) 
			
//$variation->set_name('XS');
//Por ultimo le añadimos sus datos pertinentes y lo guardamos

$variation->set_status( 'publish' );

/*$variation->set_regular_price( $productito['pvp_tpv'] );

$variation->set_price( $productito['pvp_tpv'] );
			
$variation->get_sale_price( $productito['pvp_tpv'] );
	*/		
$variation->set_regular_price( $productito['pvp_tpv'] );

//$variation->set_price( 110 );
	echo '<H1>DANI TOCA AQUI2 INSERCION PRODUCTO SIMPLE DESDE BACKEND</H1>';

            
           echo '<h2>'.$productito['pvp_r1'].'</h2>'; 
            echo '<h2>'.$productito['pvp_r2'].'</h2>'; 
            
if($productito['pvp_r1']>0){	
$variation->set_sale_price( $productito['pvp_r1'] );
}

     if($productito['pvp_r2']>0){
         
      $variation->set_sale_price( $productito['pvp_r2'] );
     }       
            
         /*   if($productito['pvp_r2']=='' || $productito['pvp_r1']==0){
    
    
    
}else{
    
    
$variation->set_sale_price( $productito['pvp_r2'] );
    
    echo 'ENTRO POR AQUI';
    
    
}*/
			
			//get_sale_price()			
$variation->set_stock_quantity($mitalla->cantidad);
//$variation->set_stock_quantity(4);
			
			
//pvp_r1	79.95			
//$variation->set_sale_price( $saleprice );

$variation->set_manage_stock( true );

$variation_id = $variation->get_id();

//$variation->set_sku( $sku );

//$variation-> set_description( $descripccion_total );

$variation->save();
			
			
			
			
			
			
		}
		
		


		
		
}
	
	
	
	//FIN DANI
    }else{
        
        
        echo '<h1>NO VISIBLE EN WEB</h1>';
        
    }
	}

	
	
	
		
		

echo $wpdb->delete(
'conector_tallas', // table to delete from
array(
'id_articulo' => $id_articulo // value in column to target for deletion
),
array(
'%d' // format of value being targeted for deletion
)
);
	echo '<h1>';
echo $id_articulo;	
	echo '</h1>';
	
//	echo '<meta http-equiv="refresh" content="20">';
	
	
}

function pasarcuandomedelagana(){
    
    if(@$_GET['conector']=='conector'){
        
     // echo 'DANI MARICA2'; 
        
      //add_action("tabla_de_articulos","tabla_de_articulos");
        echo do_action('tabla_de_articulos');
    }
    
}

function busqueda(){
	
	//echo 'AQUI METO EL POST';
	
	
	global $wpdb;
	
	$sql4="Select * from wp_postmeta where meta_value='".$_POST['busqueda']."'";
//echo $sql4;
	
	
	$resultadobd=$wpdb->get_results($sql4);
	
	include 'beforetabla.php';
	
	foreach($resultadobd as $productosmodificados){
	
	 
		?>
<tr class="no-items"><td class="colspanchange"></td>
	
	<td class="colspanchange"><img src="<?php echo get_the_post_thumbnail_url($productosmodificados->post_id)?>" width="100"></td>
	<td class="colspanchange"><?php echo get_the_title( $productosmodificados->post_id )?></td>
<td class="colspanchange"><?php echo $posmetaidarticulo=get_post_meta($productosmodificados->post_id,"id_programa",true);
	//print_r($posmeta);
	?></td>
<td class="colspanchange"><?php echo $posmeta=get_post_meta($productosmodificados->post_id,"_sku",true);
	?><br><a target="_blank" href="https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/<?php echo $posmetaidarticulo;?>?api_key=PAoSkU7u4LlIcF ">https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/<?php echo $posmetaidarticulo;?>?api_key=PAoSkU7u4LlIcF </a><?php
        
        //print_r($posmeta);
	?></td>
<td class="colspanchange"><a href="admin.php?page=menu-importador&id_tab=nuevoproducto&id_insercion=<?php echo $posmetaidarticulo;?>">Insertar</a></td></tr>	
<?php
		
		echo get_template_part('tablaintermedia');
		
		echo $productosmodificados->post_id;
		echo 'DANI<br>';
	
	}
	
	include 'aftertabla.php';
}


function menu_importador(){
    
	echo "<h1>PROBAR CONECTOR CON PRODUCTO</h1>";
	echo '<p>Desde este boton vamos a proceder a la importacion de productos</p>';
	
?>
<div class="updated vc_license-activation-notice" id="vc_license-activation-notice"><p>Hola bienvenidos al Conector con Belneo</p><button type="button" class="notice-dismiss vc-notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>



<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a href="?page=menu-importador&id_tab=informacion" class="nav-tab nav-tab-active">Información</a>
	<a href="?page=menu-importador&id_tab=nuevoproducto" class="nav-tab ">Meter un nuevo producto</a></nav>
	<div class="clear"></div>

	

<?php
if(!isset($_GET['id_tab']) || $_GET['id_tab']=='informacion'){
		?> 			<h1>Aqui va la informacion</h1>
	
	<p>
	TABLA DE CAMBIOS consulta
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/chg_m?api_key=PAoSkU7u4LlIcF
	</p>
	<p>
BORRAR EN ESA TABLA de cambios la id 9999
	</p><p>
	
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/_process/baj_id_chg?param%5Bbody%5D=9999&api_key=PAoSkU7u4LlIcF
	</p><p>
TABLA DE ARTÍCULOS EL 6 ES LA ID
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/6?api_key=PAoSkU7u4LlIcF
	</p><p>
PROVEEDORES EL 86 LA ID
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ent_m?filter%5Bid_es_prv%5D=86&api_key=PAoSkU7u4LlIcF
	</p><p>
FAMILIA LA ID es 2
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/fam_m/2?api_key=PAoSkU7u4LlIcF
	</p><p>
TEMPORADAS CON ID=1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/tem_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
GRUPOS CON ID=1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/grp_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
seccion id 1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/sec_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
color fabricante  id 1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/cof_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
color nuestro  id 1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/con_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
material  id 1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/mat_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
suela  id 1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/sue_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
alturas  id 1
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/alt_m/1?api_key=PAoSkU7u4LlIcF
	</p><p>
años  id 45
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ano_m/45?api_key=PAoSkU7u4LlIcF
	</p><p>
cambios en existencias del art con ID 14
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/14?api_key=PAoSkU7u4LlIcF
	</p><p>
tallas id 22 que es la 6M
https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/tll/22?api_key=PAoSkU7u4LlIcF

	</p>
	
	<?php 
	}
	
	
	
	if($_GET['id_tab']=='nuevoproducto'){
		include 'form.php';
		if(isset($_POST['busqueda'])){
			
			do_action('busqueda');
		}
		if(isset($_GET['id_insercion'])){
			
			do_action('insercion');
			
		}
		
		
	}

	echo '<div class="clear"></div>';
	//echo '<a href="?page=tabla_de_articulos&id_articulo=6">Tabla de Articulos</a>';
	//echo '<a href="?page=cambiarexistencias&id_articulo=6">cambiarexistencias</a>';
		//do_action('paneldeadministracion');
	
}

function dardebajaarticulos(){
    
    
    global $wpdb;
    $sql4="Select * from conector where (tipo='BAJ')  limit 0,1";

	
	
	$resultadobd=$wpdb->get_results($sql4);
    wp_delete_post($resul->post_id);
    	
    foreach($resultadobd as $productosmodificados){
		
		echo 'Se ha actualizado el id '.$productosmodificados->id;
        
        
    }
    
    
    
    
    
    
    
    
    
    
}



function tabla_de_articulos(){
	

	//echo "Importando marica";
	
	global $wpdb;
	
	
	$sql4="Select * from conector where (tipo='ALT' or tipo='MOD') and state='PENDIENTE' limit 0,1";

	
	
	echo $sql4;
	
	
	$resultadobd=$wpdb->get_results($sql4);
	
	print_r($resultadobd);
	
	foreach($resultadobd as $productosmodificados){
		
		echo 'Se ha actualizado el id '.$productosmodificados->id;
		

	
$actualizacion=$wpdb->update('conector', array('state'=>'INSERTADO','fecha_actualizacion'=>date('Y-m-d H:i:s')), array('id_chg'=>$productosmodificados->id_chg));
	echo '<h1>'.$actualizacion.'</h1>';
	echo date('Y-m-d H:i:s');
	$id_articulo=$productosmodificados->id_chg;
	
	
	
	
	$data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
	
echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/art_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF";
	
$products = json_decode($data, true);

	//$contador='1';
	
	
		
			$sql3 = "SELECT * FROM wp_postmeta WHERE meta_value='".$id_articulo."' AND meta_key='id_programa';";
	
	$resultados3=$wpdb->get_results($sql3);
	
			
if(count($resultados3)>0){
	foreach($resultados3 as $resul){
	wp_delete_post($resul->post_id);
		
	echo 'Se ha actualizado el producto';	
	}
	
}
foreach ($products['art_m'] as $product=>$productito) {
    
    
    if($productito['web_vis']==true){
        
    
    
    

	echo '<br>';
	//print_r($product);
	
	//print_r($productito);
	echo $productito['name'];
	
	
	if($productito['img']==''){}else{	

    $data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ent_m?filter%5Bid_es_prv%5D=".$productito['prv']."&api_key=PAoSkU7u4LlIcF");
	
        
        
$marca = json_decode($data, true);
        
$mimarca='';
	foreach ($marca['ent_m'] as $marquita=>$marc) {
		
		//echo '<h1>'.$marc['nom_com'].'</h1>';
		//$mimarca=$marc['nom_com'];
$mimarca=$marc['ali'];

        //print_r($marc);
		
	}    
			//'post_name' => $new_slug
                
			$product_1=array( 'post_title' => $productito['name'],
'post_content' => $productito['dsc'],
'post_status' => 'publish',
'post_type' => "product",
'post_name'=>$productito['name'].'-'.$productito['mod_fab'].'-'.$mimarca);

$post_id = wp_insert_post( $product_1 );
wp_set_object_terms( $post_id, 'simple', 'product_type' );
	/*
	5	SEÑORA ANCHO ESPECIAL
2	CABALLERO SPORT
4	SEÑORA SPORT
0	BOLSOS
3	SEÑORA VESTIR
1	CABALLERO VESTIR
6	UNISEX
7	CINTURONES
*/
 $foo = $productito['name']; 
        
        
//DANI GIROL ULTIMO CAMIBO
        
$new=$productito['new'];
if($productito['new']==true){        
wp_set_object_terms($post_id, 'NOVEDAD', 'product_tag', true);
}
        
if($productito['des_1']==true){        
wp_set_object_terms($post_id, 'DESTACADO', 'product_tag', true);
}

if($productito['des_2']==true){        
wp_set_object_terms($post_id, 'DESTACADO 2', 'product_tag', true);
}

        

        
//FIN DANI GIROL ULTIMO    
        
        
        
        
        
$categoria='';
        if (strpos($foo, 'MOCASIN') !== false) {
    echo 'true';
    
    wp_set_object_terms($post_id, 'MOCASIN', 'product_tag', true);
    
}   
        if (strpos($foo, 'BLUCHER') !== false) {
    echo 'true';
    
    wp_set_object_terms($post_id, 'BLUCHER', 'product_tag', true);
    
}    

	
/*CABALLERO VESTIR 1
CABALLERO SPORT 2
SEÑORA VESTIR 3
SEÑORA SPORT 4
SEÑORA ANCHO ESPECIAL 5
UNISEX 6
CINTURONES 7

*/
		
	/*	ZAPATO 2
SANDALIA 4
BOTIN 6
BOTA 7
ZAPATILLA CASA 8	
	*/		
		
		echo '<h1>'.$productito['sec'].'</h1>';
	if($productito['sec']=='1'){
		
	
			wp_set_object_terms($post_id, 120, 'product_cat', true);
	
		$categoria='CABALLERO VESTIR';
		
	}
	if($productito['sec']=='2'){
		
		
			wp_set_object_terms($post_id, 120, 'product_cat', true);
		$categoria='CABALLERO SPORT';
		
		
	}
	if($productito['sec']=='3'){
			
			wp_set_object_terms($post_id, 121, 'product_cat', true);
		
		$categoria='SEÑORA VESTIR';
		
	}
	if($productito['sec']=='4'){
		
		
			wp_set_object_terms($post_id, 121, 'product_cat', true);
		$categoria='SEÑORA SPORT';
	}
	if($productito['sec']=='5'){
			wp_set_object_terms($post_id, 121, 'product_cat', true);
		$categoria='SEÑORA ANCHO ESPECIAL';
	}

	if($productito['sec']=='6'){
		

		
			wp_set_object_terms($post_id, 123, 'product_cat', true);
			$categoria='UNISEX';
		
	}
	if($productito['sec']=='7'){

		wp_set_object_terms($post_id, 117, 'product_cat', true);
			$categoria='CINTURONES';
		
	}
	if($productito['sec']=='0'){

		wp_set_object_terms($post_id, 49, 'product_cat', true);
			$categoria='BOLSOS';
		
	}	
	
	
	//wp_set_object_terms( $post_id, 'simple', 'product_type' );
	 // ID of Post
/*$tags = array('Mango', 'Apple', 'Banana'); // Array of Tags to add
wp_set_post_tags( $post_id, $tags); // Set tags to Post
	
	*/
		
		

		
$data = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ent_m?filter%5Bid_es_prv%5D=".$productito['prv']."&api_key=PAoSkU7u4LlIcF");

		$color = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/con_m/".$productito['con']."?api_key=PAoSkU7u4LlIcF");
		
		/*$material = file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/con_m/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
*/
		$suela=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/sue_m/".$productito['sue']."?api_key=PAoSkU7u4LlIcF");
		echo 'SUELA';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/sue_m/".$productito['sue']."?api_key=PAoSkU7u4LlIcF";
		echo '<br>';
		
		$material=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/mat_m/".$productito['mat']."?api_key=PAoSkU7u4LlIcF");
		
		echo 'MATERIAL';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/mat_m/".$productito['mat']."?api_key=PAoSkU7u4LlIcF";
		echo '<br>';
		
		$alturas=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/alt_m/".$productito['alt']."?api_key=PAoSkU7u4LlIcF");
		
		echo 'ALTURA';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/alt_m/".$productito['alt']."?api_key=PAoSkU7u4LlIcF";
		
		echo '<br>';
		$anios=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ano_m/".$productito['ano']."?api_key=PAoSkU7u4LlIcF");
		
		echo 'ANO';
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/ano_m/".$productito['ano']."?api_key=PAoSkU7u4LlIcF";
		echo '<br>';
		
		
$marca = json_decode($data, true);
$color = json_decode($color, true);	
$suela=json_decode($suela, true);	
$material=json_decode($material, true);	
$alturas=json_decode($alturas,true);
$anios=json_decode($anios,true);
		
		
		
$colornuestro=@$color['con_m'][0]['name'];
$suelanuestra=@$suela['sue_m'][0]['name'];	
$materialnuestro=@$material['mat_m'][0]['name'];
$alturasnuestras=@$alturas['alt_m'][0]['name'];	
$anios=@$anios['ano_m'][0]['name'];		

		
		//print_r($alturas);
echo '<h1>'.$suelanuestra.'</h1>';
		echo '<h1>'.$colornuestro.'</h1>';
		echo '<h1>'.$materialnuestro.'</h1>';
		echo '<h1>'.$alturasnuestras.'</h1>';
		echo '<h1>'.$anios.'</h1>';
$mimarca='';
	foreach ($marca['ent_m'] as $marquita=>$marc) {
		
		//echo '<h1>'.$marc['nom_com'].'</h1>';
		//$mimarca=$marc['nom_com'];
$mimarca=$marc['ali'];

        //print_r($marc);
		
	}	
		
$my_post = array(
    'ID'           => $post_id,
    //'post_title'   => $productito['name'].' '.$categoria.' '.$productito['mod_fab'].' '.$mimarca, // new title
    'post_title'   => $productito['name'].' '.$productito['mod_fab'].' '.$mimarca, // new title
    
);

// Update the post into the database
wp_update_post( $my_post );
		
/*	ZAPATO 2
SANDALIA 4
BOTIN 6
BOTA 7
ZAPATILLA CASA 8	
	*/	
        
       
      wp_set_object_terms($post_id, $categoria, 'product_tag', true);
        
	if($productito['fam']=='1'){
		wp_set_object_terms($post_id, 'ZAPATO', 'product_tag', true);
		
		
	}
	if($productito['fam']=='3'){
		wp_set_object_terms($post_id, 'BOTA AGUA', 'product_tag', true);
		
		
	}
		
		if($productito['fam']=='2'){
		wp_set_object_terms($post_id, 'ZAPATO', 'product_tag', true);
		
		
	}
		if($productito['fam']=='4'){
		wp_set_object_terms($post_id, 'SANDALIA', 'product_tag', true);
		
		
	}	
	if($productito['fam']=='6'){
		wp_set_object_terms($post_id, 'BOTIN', 'product_tag', true);
		
		
	}	
		if($productito['fam']=='7'){
		wp_set_object_terms($post_id, 'BOTA', 'product_tag', true);
		
		
	}	
		if($productito['fam']=='8'){
		wp_set_object_terms($post_id, 'ZAPATILLA CASA', 'product_tag', true);
		
		
	}		
	if($productito['fam']=='9'){
		wp_set_object_terms($post_id, 'DEPORTIVO', 'product_tag', true);
		
		
	}		
		
if($mimarca==''){}else{

wp_set_object_terms($post_id, $mimarca, 'product_tag', true);
wp_set_object_terms($post_id, $mimarca, 'pwb-brand', true);
	
}
		
 if($productito['out']==true){
	 
	 wp_set_object_terms($post_id, 'OUTLET', 'product_tag', true);
	 
 }		
		
		
if($colornuestro==''){}else{
wp_set_object_terms($post_id, $colornuestro, 'product_tag', true);
	
}
if($suelanuestra==''){}else{
wp_set_object_terms($post_id, $suelanuestra, 'product_tag', true);
	
}
if($materialnuestro==''){}else{
wp_set_object_terms($post_id, $materialnuestro, 'product_tag', true);
	
}	
if($alturasnuestras==''){}else{

	wp_set_object_terms($post_id, $alturasnuestras, 'product_tag', true);
	
	
}	
if($anios==''){}else{

	wp_set_object_terms($post_id, $anios, 'product_tag', true);
	
	
}	
		
		
		
		
update_post_meta( $post_id, '_price', $productito['pvp_tpv'] );
//update_post_meta( $post_id, '_featured', 'yes' );
//update_post_meta( $post_id, '_stock', $productito['exs'] );
update_post_meta( $post_id, '_stock', 4 );		

update_post_meta( $post_id, '_stock_status', 'instock');
update_post_meta( $post_id, '_sku', $productito['ref'] );
update_post_meta( $post_id, 'id_programa', $id_articulo );
	
echo $id_articulo;
	

	
	
$Base64Img = "data:image/png;base64,".$productito['img'];
 
        
        
//eliminamos data:image/png; y base64, de la cadena que tenemos
//hay otras formas de hacerlo                   
list(, $Base64Img) = explode(';', $Base64Img);
list(, $Base64Img) = explode(',', $Base64Img);
//Decodificamos $Base64Img codificada en base64.
$Base64Img = base64_decode($Base64Img);
        
       // echo $productito['img'];
        
//escribimos la información obtenida en un archivo llamado 
//unodepiera.png para que se cree la imagen correctamente
echo '<h1>AQUI FALLA';
  
        echo dirname(__FILE__);
        echo file_put_contents('/home/calzadossierra/www/imagenes/'.$productito['name'].'.png', $Base64Img); 
         //echo file_put_contents(dirname(__FILE__).'../imagenes/'.$productito['name'].'.png', $Base64Img);   
        
        echo '</h1>';
        
echo "<img src='/imagenes/".$productito['name'].".png' width='150px' />";
			echo 'Se ha insertado correctamente';
	
	
	
	
	
	
$upload_dir       = wp_upload_dir();
  $image_url        = '/imagenes/'.$productito['name'].'.png'; // Define the image URL here

			
    $image_name       = $productito['name'].'.png';
     // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name );	
			
	copy('/home/calzadossierra/www/imagenes/'.$productito['name'].'.png',$upload_dir['path'].'/'.$unique_file_name);
			
			//echo $upload_dir['path'].'/'.$pro['NAME'].'.png';	
			
			
			
			
 // Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }		
			
  $wp_filetype = wp_check_filetype( $filename, null );
			
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

    // Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );

	
		
	
		
		
    // And finally assign featured image to post
    set_post_thumbnail( $post_id, $attach_id );		
		
	
		
	//IMAGEN2
		
	if($productito['img1']!=''){
	
	
		
		$Base64Img = "data:image/png;base64,".$productito['img1'];
 
//eliminamos data:image/png; y base64, de la cadena que tenemos
//hay otras formas de hacerlo                   
list(, $Base64Img) = explode(';', $Base64Img);
list(, $Base64Img) = explode(',', $Base64Img);
//Decodificamos $Base64Img codificada en base64.
$Base64Img = base64_decode($Base64Img);
//escribimos la información obtenida en un archivo llamado 
//unodepiera.png para que se cree la imagen correctamente
file_put_contents('/home/calzadossierra/www/imagenes/'.$productito['name'].'-2.png', $Base64Img);    
echo "<img src='/imagenes/".$productito['name']."-2.png' width='150px' />";
			echo 'Se ha insertado correctamente';
	
	
	
	
	
	
$upload_dir       = wp_upload_dir();
  $image_url        = '/imagenes/'.$productito['name'].'-2.png'; // Define the image URL here

			
    $image_name       = $productito['name'].'-2.png';
     // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name );	
			
    copy('/home/calzadossierra/www/imagenes/'.$productito['name'].'-2.png',$upload_dir['path'].'/'.$unique_file_name);
			
			//echo $upload_dir['path'].'/'.$pro['NAME'].'.png';	
			
			
			
			
 // Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }		
			
  $wp_filetype = wp_check_filetype( $filename, null );
			
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		// Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );
	
	update_post_meta( $post_id, '_product_image_gallery', $attach_id );		
		
	}	
		
	//update_post_meta( $post_id, '_product_image_gallery', $attach_id );		
		
		
		
	//FIN IMAGEN2	
		//$this->insertartallas();
		//INICIO TALLAS 
		
		
		//FIN TALLAS
		
		//https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/14?api_key=PAoSkU7u4LlIcF
		
		
		
		$tallas=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
		
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/".$id_articulo."?api_key=PAoSkU7u4LlIcF";
		
$tallas = json_decode($tallas, true);
		
		
		foreach($tallas['exs_g_tyc'] as $talla=>$mitalla){
			
			
		
			$sql_tallas = "SELECT * FROM conector_tallas WHERE id_articulo='".$id_articulo."' AND id_talla='".$mitalla['tll']."';";
	
			echo $sql_tallas;
			echo '<hr>';
			
	$resultados_tallas=$wpdb->get_results($sql_tallas);
		
	$nombretalla=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/tll/".$mitalla['tll']."?api_key=PAoSkU7u4LlIcF");
		
		
		
$nombretalla = json_decode($nombretalla, true);
			
			
		echo '<h2>'.$nombretalla['tll'][0]['name'].'</h2>';			
			echo 'DANIII<h1>'.count($resultados_tallas).'</h1>';
		if(count($resultados_tallas)>0){
			
			echo '<h1>';
			echo 'HAY TALLA INSERTADA';
			echo '</h1>';
			
			print_r($resultados_tallas);
			
			$resultados_tallas[0]->cantidad;
			  $wpdb->update( 'conector_tallas', 
    array( 
      'cantidad' => $resultados_tallas[0]->cantidad+$mitalla['exs']
      //'email' => 'www.andres-dev.com'
    ),
    // Cuando el ID del campo es igual al número 1
    array( 'ID' => $resultados_tallas[0]->id )
  );
			
			
		}else{
		
			
$wpdb->insert('conector_tallas', array(
    'id_articulo' => $id_articulo,
    'id_talla' => $mitalla['tll'],
	'cantidad' => $mitalla['exs'],
	'talla' => $mitalla['tll'],
    'nombre_talla' => $nombretalla['tll'][0]['name'], // ... and so on
));
			
			
		}	
			
			
			
			
			
		}
		
		
		
	$sql_tallas_insertar = "SELECT * FROM conector_tallas WHERE id_articulo='".$id_articulo."'";
	
			//echo $sql_tallas;
			echo '<hr>';
			
	$resultados_tallas_insertar=$wpdb->get_results($sql_tallas_insertar);		
		
		
		
		
		
		foreach($resultados_tallas_insertar as $mitalla){
			
			echo $mitalla->id_talla;
			echo '<br>';
			
			
			 $product = wc_get_product( $post_id );  
	//$precio=$product->get_regular_price();
	/*		echo $product->get_regular_price();echo '<br>';
echo $product->get_sale_price();echo '<br>';*/



			
	 wp_remove_object_terms( $post_id, 'simple', 'product_type' );
     wp_set_object_terms( $post_id, 'variable', 'product_type', true );

	  $product->save();
	$product_new = wc_get_product( $post_id );  
	
	$product = new WC_Product_Variable($post_id);

$attribute = new WC_Product_Attribute();//declaramos nuestro primer atributo
/*$attribute->set_id(4);//le damos una id
$attribute->set_name('Tallas');// y un nombre
*/

//$term = get_term_by( 'slug', 'Yes', 'tallas' );
// Or use $term = get_term_by( 'slug', 'yes', 'pa_foil' );

// Attribute `options` is an array of term IDs.
		$tallitas=array("176","6");	
$options = [ 176,174,196,197,198,199,200,201,202,203,204,205,206,207,208,185,209,210,211,212,213,214,215,216,217,218,219,220,221,222,186,187,188,189,190,191,192,193,194,195,175,172,173,340 ];
			
	//$options = [ $term->term_id ];			
$attribute->set_name('pa_tallas');
$attribute->set_options($options);
			$attribute->set_visible(1);
			$attribute->set_variation(1);
			$attribute->set_id( 1 );
			$attributes['pa_tallas'] = $attribute;
			$product->set_attributes( $attributes );
$product->save();
	/*		
$attribute_object->set_name( 'pa_foil' );
$attribute_object->set_options( $options );
$attribute_object->set_visible( 1 );
$attribute_object->set_variation( 0 );
$attribute_object->set_id( 1 );
$attributes['pa_foil'] = $attribute_object;

$product->set_attributes( $attributes );
$product->save();			
		*/	
			
			
			//echo '<h1>';
//print_r($resultados44);			
			
			
			echo '<br>';
	

			
			
			
			//$tallas=array();
			
			
			
		//$tallasaimportar=array('6M','5M','5','6','1','2','3','4','7','8','9','10','11','12','13','3M','4M','35','36','37','38','39','40','41');	
			//foreach($resultados44 as $result){
	/*foreach($tallasaimportar as $tal){
		$tallas['pa_tallas']=$tal;
		echo $tal;
		echo '<br>';
	}*/
		
			
		echo '<h2>'.$mitalla->nombre_talla.'</h2>';	
		
		
	//}		
	/*		
 $colores = array(
    0  => $resul->nombre_talla,  //este sera los valores del atributo
	1  => 37,
	2  => 36,
	3  => 38,
	4  => 40,
);*/

// $attribute->set_visible(true);
// $attribute->set_variation(true);
// $attribute->set_options($tallas);//le asignamos los valores al atributo




//$product->set_attributes(array($attribute));
//echo $product->save();
	
	
	
	
	//creamos las variables con los datos de nuestra variación

//$sku = date('Ymdhis').rand(0,100);//el sku es como un id no debe repetirse.
//usar un ean13 es una buena opción para evitar duplicados.
			
			
$price = $productito['pvp_tpv'];//precio normal
//$saleprice = "";//precio rebajado
$descripcion = "";
$disponible = "4";//esto es el stock

$artributo1 = $mitalla->nombre_talla;


$descAtr1 = "";
$descAtr2 = "";


$variation = new WC_Product_Variation();//creamos la variación

//creamos una descripción para la misma
$descripccion_total = "";

//Es importante asignarle la id de el producto al que pertenece
$variation->set_parent_id($post_id);

//Aquí le añadimos los valores del atributo que hereda del producto, si nuestro producto tiene dos atributos
//nuestra variación tendrá que tener un valor que pertenezca dicho producto
//si tienes dudas sobre esto te recomiendo que mires el siguiente post que habla sobre ello



//$variation->set_attributes(array('pa_tallas' => $nombretalla['tll'][0]['name']));
	$variation->set_attributes(array('pa_tallas' => strtolower($mitalla->nombre_talla)));	
		//$variation->set_attributes( array( $taxonomy => $term->slug ) );	
			//array( $taxonomy => $term->slug ) 
			
//$variation->set_name('XS');
//Por ultimo le añadimos sus datos pertinentes y lo guardamos

$variation->set_status( 'publish' );

/*$variation->set_regular_price( $productito['pvp_tpv'] );

$variation->set_price( $productito['pvp_tpv'] );
			
$variation->get_sale_price( $productito['pvp_tpv'] );
	*/		
$variation->set_regular_price( $productito['pvp_tpv'] );

//$variation->set_price( 110 );
	echo '<H1>DANI TOCA AQUI 3</H1>';
if($productito['pvp_r1']>0){		
$variation->set_sale_price( $productito['pvp_r1'] );
}

     if($productito['pvp_r2']>0){
         
      $variation->set_sale_price( $productito['pvp_r2'] );
     }                
			
			//get_sale_price()			
$variation->set_stock_quantity($mitalla->cantidad);
//$variation->set_stock_quantity(4);
			
			
//pvp_r1	79.95			
//$variation->set_sale_price( $saleprice );

$variation->set_manage_stock( true );

$variation_id = $variation->get_id();

//$variation->set_sku( $sku );

//$variation-> set_description( $descripccion_total );

$variation->save();
			
			
			
			
			
			
		}
		
		


		
		
}
	
	
	
	//FIN DANI
    }else{
        
        
        echo '<h1>NO VISIBLE EN WEB</h1>';
        
    }
	}

	
	
	
		}
	
if(isset($id_articulo)){	
echo $wpdb->delete(
'conector_tallas', // table to delete from
array(
'id_articulo' => @$id_articulo // value in column to target for deletion
),
array(
'%d' // format of value being targeted for deletion
)
);
	echo '<h1>';
echo $id_articulo;	
	echo '</h1>';
	
//	echo '<meta http-equiv="refresh" content="20">';
	
	
}}




function cambiarexistencias(){
	
	
	global $wpdb;
	
	$sql4="Select * from conector where tipo='EXS' and state='PENDIENTE' limit 0,1";
echo $sql4;
	
	
	$resultadobd=$wpdb->get_results($sql4);
	
	
	
	foreach($resultadobd as $productosmodificados){
		
		echo 'Se ha actualizado el id '.$productosmodificados->id;
	
	$sql3 = "SELECT * FROM wp_postmeta WHERE meta_value='".$productosmodificados->id."' AND meta_key='id_programa';";
	
		echo $sql3;
	$resultados3=$wpdb->get_results($sql3);
	
		$wpdb->update('conector', array('state'=>'INSERTADO','fecha_actualizacion'=>date('Y-m-d H:i:s')), array('id'=>$productosmodificados->id));		
//if(count($resultados3)>0){
foreach($resultados3 as $resul){
	$post_id=$resul->post_id;
	

	
		echo '<h1>'.$post_id.'</h1>';
		
		
		
	//echo 'Cambiar existencias';
	$id_articulo=$productosmodificados->id;
		
		
		
		
		$tallas=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/".$id_articulo."?api_key=PAoSkU7u4LlIcF");
		
		echo "https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/exs_g_tyc/".$id_articulo."?api_key=PAoSkU7u4LlIcF";
		
$tallas = json_decode($tallas, true);
		
		
		foreach($tallas['exs_g_tyc'] as $talla=>$mitalla){
			
			
		
			$sql_tallas = "SELECT * FROM conector_tallas WHERE id_articulo='".$id_articulo."' AND id_talla='".$mitalla['tll']."';";
	
			echo $sql_tallas;
			echo '<hr>';
			
	$resultados_tallas=$wpdb->get_results($sql_tallas);
		
	$nombretalla=file_get_contents("https://clientes1.aplgestion.es/aplapache/alfaZapaterias_dat_dat/v1/tll/".$mitalla['tll']."?api_key=PAoSkU7u4LlIcF");
		
		
		
$nombretalla = json_decode($nombretalla, true);
			
			
		echo '<h2>'.$nombretalla['tll'][0]['name'].'</h2>';			
			echo 'DANIII<h1>'.count($resultados_tallas).'</h1>';
		if(count($resultados_tallas)>0){
			
			echo '<h1>';
			echo 'HAY TALLA INSERTADA';
			echo '</h1>';
			
			//print_r($resultados_tallas);
			
			$resultados_tallas[0]->cantidad;
			  $wpdb->update( 'conector_tallas', 
    array( 
      'cantidad' => $resultados_tallas[0]->cantidad+$mitalla['exs']
      //'email' => 'www.andres-dev.com'
    ),
    // Cuando el ID del campo es igual al número 1
    array( 'ID' => $resultados_tallas[0]->id )
  );
			
			
		}else{
		
			
$wpdb->insert('conector_tallas', array(
    'id_articulo' => $id_articulo,
    'id_talla' => $mitalla['tll'],
	'cantidad' => $mitalla['exs'],
	'talla' => $mitalla['tll'],
    'nombre_talla' => $nombretalla['tll'][0]['name'], // ... and so on
));
			
			
		}	
			
			
			
			
			
		}
		
		
		
	$sql_tallas_insertar = "SELECT * FROM conector_tallas WHERE id_articulo='".$id_articulo."'";
	
			//echo $sql_tallas;
			//echo '<hr>';
			
	$resultados_tallas_insertar=$wpdb->get_results($sql_tallas_insertar);		
		
		
		
		
		
		foreach($resultados_tallas_insertar as $mitalla){
			
			echo $mitalla->id_talla;
			echo '<br>';
			
			
			 $product = wc_get_product( $post_id );  
	//$precio=$product->get_regular_price();
	/*		echo $product->get_regular_price();echo '<br>';
echo $product->get_sale_price();echo '<br>';*/



			
	 wp_remove_object_terms( $post_id, 'simple', 'product_type' );
     wp_set_object_terms( $post_id, 'variable', 'product_type', true );

	  $product->save();
	$product_new = wc_get_product( $post_id );  
	
	$product = new WC_Product_Variable($post_id);

$attribute = new WC_Product_Attribute();//declaramos nuestro primer atributo
/*$attribute->set_id(4);//le damos una id
$attribute->set_name('Tallas');// y un nombre
*/

//$term = get_term_by( 'slug', 'Yes', 'tallas' );
// Or use $term = get_term_by( 'slug', 'yes', 'pa_foil' );

// Attribute `options` is an array of term IDs.
		$tallitas=array("176","6");	
$options = [ 176,174,196,197,198,199,200,201,202,203,204,205,206,207,208,185,209,210,211,212,213,214,215,216,217,218,219,220,221,222,186,187,188,189,190,191,192,193,194,195,175,172,173 ];
			
	//$options = [ $term->term_id ];			
$attribute->set_name('pa_tallas');
$attribute->set_options($options);
			$attribute->set_visible(1);
			$attribute->set_variation(1);
			$attribute->set_id( 1 );
			$attributes['pa_tallas'] = $attribute;
			$product->set_attributes( $attributes );
$product->save();
	/*		
$attribute_object->set_name( 'pa_foil' );
$attribute_object->set_options( $options );
$attribute_object->set_visible( 1 );
$attribute_object->set_variation( 0 );
$attribute_object->set_id( 1 );
$attributes['pa_foil'] = $attribute_object;

$product->set_attributes( $attributes );
$product->save();			
		*/	
			
			
			//echo '<h1>';
//print_r($resultados44);			
			
			
			echo '<br>';
	

			
			
			
			//$tallas=array();
			
			
			
		//$tallasaimportar=array('6M','5M','5','6','1','2','3','4','7','8','9','10','11','12','13','3M','4M','35','36','37','38','39','40','41');	
			//foreach($resultados44 as $result){
	/*foreach($tallasaimportar as $tal){
		$tallas['pa_tallas']=$tal;
		echo $tal;
		echo '<br>';
	}*/
		
			
		echo '<h2>'.$mitalla->nombre_talla.'</h2>';	
		
		
	//}		
	/*		
 $colores = array(
    0  => $resul->nombre_talla,  //este sera los valores del atributo
	1  => 37,
	2  => 36,
	3  => 38,
	4  => 40,
);*/

// $attribute->set_visible(true);
// $attribute->set_variation(true);
// $attribute->set_options($tallas);//le asignamos los valores al atributo




//$product->set_attributes(array($attribute));
//echo $product->save();
	
	
	
	
	//creamos las variables con los datos de nuestra variación

//$sku = date('Ymdhis').rand(0,100);//el sku es como un id no debe repetirse.
//usar un ean13 es una buena opción para evitar duplicados.
			
			
$price = $productito['pvp_tpv'];//precio normal
//$saleprice = "";//precio rebajado
$descripcion = "";
$disponible = "4";//esto es el stock

$artributo1 = $mitalla->nombre_talla;


$descAtr1 = "";
$descAtr2 = "";


$variation = new WC_Product_Variation();//creamos la variación

//creamos una descripción para la misma
$descripccion_total = "";

//Es importante asignarle la id de el producto al que pertenece
$variation->set_parent_id($post_id);

//Aquí le añadimos los valores del atributo que hereda del producto, si nuestro producto tiene dos atributos
//nuestra variación tendrá que tener un valor que pertenezca dicho producto
//si tienes dudas sobre esto te recomiendo que mires el siguiente post que habla sobre ello



//$variation->set_attributes(array('pa_tallas' => $nombretalla['tll'][0]['name']));
	$variation->set_attributes(array('pa_tallas' => strtolower($mitalla->nombre_talla)));	
		//$variation->set_attributes( array( $taxonomy => $term->slug ) );	
			//array( $taxonomy => $term->slug ) 
			
//$variation->set_name('XS');
//Por ultimo le añadimos sus datos pertinentes y lo guardamos

$variation->set_status( 'publish' );

/*$variation->set_regular_price( $productito['pvp_tpv'] );

$variation->set_price( $productito['pvp_tpv'] );
			
$variation->get_sale_price( $productito['pvp_tpv'] );
	*/		
$variation->set_regular_price( $productito['pvp_tpv'] );

//$variation->set_price( 110 );
echo '<H1>DANI TOCA AQUI</H1>';	
if($productito['pvp_r1']>0){}else{			
$variation->set_sale_price( $productito['pvp_r1'] );
}

     if($productito['pvp_r2']>0){
         
      $variation->set_sale_price( $productito['pvp_r2'] );
     }    
			
			//get_sale_price()			
$variation->set_stock_quantity($mitalla->cantidad);
//$variation->set_stock_quantity(4);
			
			
//pvp_r1	79.95			
//$variation->set_sale_price( $saleprice );

$variation->set_manage_stock( true );

$variation_id = $variation->get_id();

//$variation->set_sku( $sku );

//$variation-> set_description( $descripccion_total );

$variation->save();
			
			
			
			
			
			
		}
		
		
		
		
		
		
		
	
	
	}
	
		
		
		
	}
	
	
	
	
	
	
}


function segundafoto(){
	
	
	global $wpdb;
	
	$sql4="Select * from conector where tipo='EXS' and state='PENDIENTE' limit 0,1";
echo $sql4;
	
	
	$resultadobd=$wpdb->get_results($sql4);
	
	
	
	foreach($resultadobd as $productosmodificados){
		
		echo 'Se ha actualizado el id '.$productosmodificados->id;
	
	//echo 'Cambiar existencias';
	
		
		
		
		
	
	
	}
	
	
	
	
	
	
}

function bajaproducto(){
	
	echo 'BAJA PRODUCTO';
	
	
	global $wpdb;

	$sql4="Select * from conector where tipo='BAJ' and state='PENDIENTE' limit 0,1";
echo $sql4;
	
	
	$resultadobd=$wpdb->get_results($sql4);
	
	
	
	foreach($resultadobd as $productosmodificados){
		
		echo 'Se ha actualizado el id '.$productosmodificados->id;
	
	//echo 'Cambiar existencias';
	
		echo 'Eliminar producto';
		

		
		
	
	
	}
	
	
	
	
}

function segundaimagen(){
	

	
	
	global $wpdb;
	
	$sql4="Select * from conector where tipo='BAJ' and state='PENDIENTE' limit 0,1";
echo $sql4;
	
	
	$resultadobd=$wpdb->get_results($sql4);
	
	
	
	foreach($resultadobd as $productosmodificados){
		
		echo 'Se ha actualizado el id '.$productosmodificados->id;
	
	//echo 'Cambiar existencias';
	
		
		
		
		
	
	
	}
	
	
	
	
}





?>