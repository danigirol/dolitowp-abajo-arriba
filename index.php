<?php
/*
 * Plugin Name: Girol consulting
 * Plugin URI: https://girol.es
 * Version: 3.5.0
 * Description: Plugin para conectarnos con dolibar
 * Author: Girol
 * Author URI: https://giol.es
 *
 * Text Domain: Girol
 * Domain Path: /
 *
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
$token="281aa9cccb7713d4f54bb675567bfe6c";

function add_my_stylesheet1() 
{
    wp_enqueue_style( 'myCSS1', plugins_url( '/css/style.css', __FILE__ ) );

}
add_action('admin_print_styles', 'add_my_stylesheet1');

add_action('init', 'pasarcuandomedelagana');
add_action('importar_individual','importar_individual');
add_action('importar_todo','importar_todo');
function pasarcuandomedelagana(){
    
    if(@$_GET['conector']=='skukhji458esa'){
        
     // echo 'DANI MARICA2'; 
        
      //add_action("tabla_de_articulos","tabla_de_articulos");
        echo do_action('importar_todo');
        echo do_action('importar_individual');
    }
    
}

	
function add_theme_scripts() {

  wp_enqueue_script( 'script', plugins_url( '/js/llamaralcron.js', __FILE__ ), array ( 'jquery' ), 1.1, true);
    
}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );


add_action('wp_ajax_get_ajax_importar_todo', 'importar_todo');
add_action('wp_ajax_nopriv_get_ajax_importar_todo', 'importar_todo');








add_action( 'init', function() {
    register_post_status( 'wc-en-dolibar', array(
        'label'                     => 'En Dolibar',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'En dolibar <span class="count">(%s)</span>', 'En dolibar <span class="count">(%s)</span>'),
    ) );
}, 10 );
 
add_filter ( 'wc_order_statuses', function( $estados ) {
    $estados['wc-en-dolibar'] = 'En dolibar';
    return $estados;
}, 10, 1 );



add_action('admin_menu', 'test_plugin_setup_menu_conector');
 
function test_plugin_setup_menu_conector(){
   
add_menu_page( 'CONECTOR WPTODOLI', 'CONECTOR WPTODOLI', 'manage_options', 'menu-importador','menu_importador' );
    
add_submenu_page( 'menu-importador', 'Importar todo', 'Importar Todo', 'manage_options', 'importar_todo', 'importar_todo');
    
    add_submenu_page( 'menu-importador', 'Sincronizar Stock', 'Sincronizar Stock', 'manage_options', 'sincronizar_stock', 'sincronizar_stock');

        add_submenu_page( 'menu-importador', 'Sincronizar Individual', 'Sincronizar individual', 'manage_options', 'importar_individual', 'importar_individual');
	/*add_menu_page('Theme page title', 'Theme menu label', 'manage_options', 'theme-options', 'wps_theme_func');
  add_submenu_page( 'theme-options', 'Settings page title', 'Settings menu label', 'manage_options', 'theme-op-settings', 'wps_theme_func_settings');
  add_submenu_page( 'theme-options', 'FAQ page title', 'FAQ menu label', 'manage_options', 'theme-op-faq', 'wps_theme_func_faq');
	*/
} 

function importar_todo(){
    
    echo 'AQUI Voy a importar todo';
    
       $context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
    
    
    $data = file_get_contents("https://api.stressgijon.com/clientes?token=281aa9cccb7713d4f54bb675567bfe6c",false, $context);
    
    
    $data2=json_decode($data);
           global $wpdb;
    
    //echo count($data2);
    
    //print_r($data2);
    if($data2->status=='error'){
        
        echo '<h1>NO HAY PRODUCTOS PARA RASTREAR</h1>';
    }else{
    
    foreach($data2 as $dat){
        
        //print_r($dat);
        echo '<hr>';

$results = $wpdb->get_row( 'SELECT * FROM conector WHERE id_dolibar = '.$dat->rowid, OBJECT );     $total=$wpdb->num_rows;
        
        echo "SELECT * FROM conector WHERE id_dolibar = '.$dat->rowid.'";
        //echo $results($results);
       //print_r($results);
       echo $total;
        //echo count($results);
    if($total==0){
      $wpdb->insert("conector", array(
   "id_dolibar" => $dat->rowid,
   "fecha_update" => date('Y-m-d H:i:s'),
  
));  
        
        
        
    } else{
       
		echo 'Ya esta';
		$dbData['actualizar'] = 1;
$wpdb->update('conector', $dbData, array('id_dolibar' =>$dat->rowid));
        
    }   
  
        $context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
    
    
    $data = file_get_contents("https://api.stressgijon.com/actualizarproductosencola?id_producto=".$dat->rowid."&token=281aa9cccb7713d4f54bb675567bfe6c",false, $context);    
        
   
       

        
        
        
    }
    
    }
    
    
    //print_r($data);
    
    
    
    
    
}
function sincronizar_stock(){
    
    global $token;
	
	include 'views/tablastock.php';
	
	
    
}
function importar_individual(){
    
    /*https://api.stressgijon.com/productoindividual?id_producto=635&token=281aa9cccb7713d4f54bb675567bfe6c
    */
           $context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
     
	global $wpdb;
	$token="281aa9cccb7713d4f54bb675567bfe6c";
	
       if(!isset($_GET['id_producto'])){
           
           $consulta="actualizar=1";
           
       }else{
           $consulta="id_dolibar='".$_GET['id_producto']."'";
           
           
           
           
       }
    
    
    
    
    
    
	 $results = $wpdb->get_row( 'SELECT * FROM conector where '.$consulta.' order by id desc ' ,OBJECT );
	 $total=$wpdb->num_rows;
	//print_r($results);
	if($total==0){
		echo '<h1>NO HAY PRODUCTOS PARA ACTUALIZAR EN LA COLA O SE ESTA ACTUALIZANDO</h1>';
	}else{
	
	echo '<h1>'.$results->id_dolibar.'</h1>';
	  
	$iddolibar=$results->id_dolibar;
    
    
      $data = file_get_contents("https://api.stressgijon.com/productoindividual?id_producto=".$iddolibar."&token=".$token,false, $context);
   
    $data2=json_decode($data);
   //print_r($data2);
    
    //;
   
	if($results->id_wordpress==NULL){
		
	
    include 'updateconector.php';
    include 'importarnombrearticulo.php';
	include 'importarcategorias.php';
    include 'importarimagenes.php';
    include 'importarvariaciones.php';
	include 'actualizartablapropia.php';
	
	}else{
		
		
	$product = wc_get_product( $results->id_wordpress );

if($product==''){
	
	include 'updateconector.php';
    include 'importarnombrearticulo.php';
	include 'importarcategorias.php';
    include 'importarimagenes.php';
    include 'importarvariaciones.php';
	include 'actualizartablapropia.php';
	
	
}else{
	
    
    $post_id=$product->get_id();
    //print_r($product);
    echo 'ACTUALIZO';
    
    include 'updateconector.php';
    include 'actualizarnombrearticulo.php';
    include 'actualizarcategorias.php';
    include 'deletetodaslasvariaciones.php';
    include 'importarvariaciones.php';
    include 'actualizartablapropia.php';
	//print_r($product);

	
    
}
		
		
	}
    
	}
    
    
    
    
        
        
    
}

function menu_importador(){
     
//require_once('api.php');
    
    
require_once('views/portada.php');
    
    $context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
    
    
    $data = file_get_contents("https://api.stressgijon.com/clientes?token=281aa9cccb7713d4f54bb675567bfe6c",false, $context);
    
    
    
    print_r($data);
    
    
    
    
    
    //echo ''
    /*
    $api=sha1("danielesmarica");
   
    
    echo '<a href="https://wptodoli.com/stressgijon/index.php?seccion=mostrarpedidos&token='.$api.'">METER PEDIDO</a><br>';
    
   echo '<br>';
    
    echo '<a href="https://wptodoli.com/stressgijon/index.php?seccion=getproducts&token='.$api.'">METER PRODUCTOS</a>';
   
    echo '<br>';
    
        echo '<a href="https://wptodoli.com/stressgijon/index.php?seccion=getproducts">INSERTAR PRODUCTOS VARIABLES</a>';
    echo '<br>';
    
        echo '<a href="https://wptodoli.com/stressgijon/index.php?seccion=getproducts&simple=1">INSERTAR PRODUCTOS SIMPLES</a>';
    ?>

<form action="https://wptodoli.com/stressgijon/index.php?seccion=mostrarpedidos&token=348bba3e417f6f97a95f82cd424a214cc7899d2d" method="post">

    
    <input type="text" name="id_pedido" value="">
    
    <input type="submit">
    
    

</form>

<?php
    */
    
    
    
    
}

