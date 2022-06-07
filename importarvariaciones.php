<?php 



$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);

$data = file_get_contents("https://api.stressgijon.com/productovariaciones?id_producto=".$iddolibar."&token=".$token,false, $context);
    

    $data2=json_decode($data);


if($data2->status=='error'){
    
    
}else{



$product = wc_get_product( $post_id );  
wp_remove_object_terms( $post_id, 'simple', 'product_type' );
wp_set_object_terms( $post_id, 'variable', 'product_type', true );
$product->save();


//print_r($data2);
foreach($data2 as $dat=>$datito){
  
    
    
    
    //BUSCAMOS EL PRODUCTO INDIVIDUAL
    $data_interno = file_get_contents("https://api.stressgijon.com/productoindividual?id_producto=".$datito->fk_product_child."&token=".$token,false, $context);
    

    $midata=json_decode($data_interno);


     $data_interno2 = file_get_contents("https://api.stressgijon.com/variaciones?id_producto=".$datito->fk_product_child."&token=".$token,false, $context);
    
    $midata_tallasycolores=json_decode($data_interno2);
    
    
   // https://api.stressgijon.com/variaciones?id_producto=812&token=281aa9cccb7713d4f54bb675567bfe6c
    
    
    
    
    
 //print_r($midata);
    
    $referencia_atributo=$midata[0]->ref;
    
    $miprecioproducto=$midata[0]->price_ttc;
    echo '<h1>'.$referencia_atributo.'</h1>';
	

    //TALLA: 
    
    
    
    echo '<h1>'.$midata[0]->description.'</h1>';
    
    
    print_r($midata_tallasycolores);
    
    $talla=0;
    $color=0;
 foreach($midata_tallasycolores as $tallasycolores){
     
     if($tallasycolores->fk_product_attribute=='2'){
         
         $talla=1;
         $valortalla=$tallasycolores->VALUE;
     }
    if($tallasycolores->fk_product_attribute=='1'){
         
         $color=1;
         $valorcolor=$tallasycolores->VALUE;
     }
     
     
   
     
     
 }
    
    //print_r($colorytalla);
    
    
    
    
    
 
    
    
    

    
    
    //print_r($dat);

    echo $datito->fk_product_child;
    
    $product_new = wc_get_product( $post_id );  
	
	$product = new WC_Product_Variable($post_id);


    
    if($talla==1){
$attribute = new WC_Product_Attribute();
    //HAY QUE CONFIGURAR LOS IDS DE LAS TALLAS
 /*   
$colores = get_terms("pa_colores");
$tallas=get_terms("pa_tallas");
//print_r($colores);    
 $totalcolores=array();
foreach($colores as $tallitaterm){
 /*   echo $tallitaterm->term_id;
    echo '<br>';
}*/    
    
    
$options = [196,197,198,199,200,201,208,209,210,211,212,213,214,215,216,217];			
$attribute->set_name('pa_tallas');
$attribute->set_options($options);
$attribute->set_visible(1);
$attribute->set_variation(1);
$attribute->set_id( 1 );
$attributes['pa_tallas'] = $attribute;
$product->set_attributes( $attributes );
$product->save();
    
    }
    
if($color==1){
    
   
//COLORES
    
$attribute = new WC_Product_Attribute();
    //Aquyi los colores
$options = [144,77,82,78,126,63,131,62,127,55,76,175,70,53,81,112,178,111,83,136,137,64,54,174,141,79,167,176,142,84,73];		
    
$attribute->set_name('pa_colores');
$attribute->set_options($options);
$attribute->set_visible(1);
$attribute->set_variation(1);
$attribute->set_id( 1 );
$attributes['pa_colores'] = $attribute;
$product->set_attributes( $attributes );
$product->save();  
  

}   
    
$variation = new WC_Product_Variation();//creamos la variación

//creamos una descripción para la misma
$descripccion_total = "";

//Es importante asignarle la id de el producto al que pertenece
$variation->set_parent_id($post_id);

//Aquí le añadimos los valores del atributo que hereda del producto, si nuestro producto tiene dos atributos
//nuestra variación tendrá que tener un valor que pertenezca dicho producto
//si tienes dudas sobre esto te recomiendo que mires el siguiente post que habla sobre ello
//$variation->set_attributes(array('pa_tallas' => $nombretalla['tll'][0]['name']));

 /*   
foreach($midata_tallasycolores as $midatatalla){
    
    
    print_r($);
    
}
*/


if($talla==1 && $color==1){    
    
$variation->set_attributes(array('pa_tallas' => strtolower($valortalla),'pa_colores'=>strtolower($valorcolor)));
}else{
    
    if($talla==1){
        
        $variation->set_attributes(array('pa_tallas' => strtolower($valortalla)));
    }
    if($color==1){
        
         $variation->set_attributes(array('pa_colores' => strtolower($valorcolor)));
    }   
    
    
    
    
}
    
    
   
    
//$variation->set_attributes(array('pa_tallas' => strtolower($tallaproducto)));    
//$variation->set_attributes(array('pa_colores' => strtolower($colorproducto)));        
    
$variation->set_status( 'publish' );

/*$variation->set_regular_price( $productito['pvp_tpv'] );

$variation->set_price( $productito['pvp_tpv'] );
			
$variation->get_sale_price( $productito['pvp_tpv'] );
	*/		
$variation->set_regular_price( $miprecioproducto );
//$variation->set_sale_price( $productito['pvp_r1'] );
 
$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
    
$data_interno = file_get_contents("https://api.stressgijon.com/stock?id_producto=".$datito->fk_product_child."&token=".$token,false, $context);
    
//echo "https://api.stressgijon.com/stock?id_producto=".$datito->fk_product_child."&token=".$token;
    
    
$midatastock=json_decode($data_interno);
    
   
    
    if($midatastock->status=='error'){
        
     
        $stock=0;
    }else{

        $stock=$midatastock[0]->reel;
    }
/*    if(!isset($midatastock[0])){
$stock=10;
    
    }else{
  $stock=$midatastock[0]->reel;      
        
    }
  */     
    
    
$variation->set_stock_quantity($stock);
//$variation->set_stock_quantity(4);
			
			
//pvp_r1	79.95			
//$variation->set_sale_price( $saleprice );

$variation->set_manage_stock( true );

$variation_id = $variation->get_id();

$variation->set_sku($midata[0]->barcode);

//$variation-> set_description( $descripccion_total );

$variation->save();
			
 echo '<hr>';
    
    
    
   // print_r()M;
    
   // print_r($dat);
    
   // echo $dat->fk_product_child;
    
    
}
}





?>

