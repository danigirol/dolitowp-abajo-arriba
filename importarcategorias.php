<?php 

//http://api.stressgijon.com/categorias?id_producto=515&token=281aa9cccb7713d4f54bb675567bfe6c

/*$term = get_term_by('name', 'Tree', 'product_cat');

wp_set_object_terms($product_ID, $term->term_id, 'product_cat');
*/
$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);

$data_categorias = file_get_contents("https://api.stressgijon.com/categorias?id_producto=".$iddolibar."&token=".$token,false, $context);

$todaslascat=json_decode($data_categorias);

$todaslascat[0]->label;


wp_set_object_terms($post_id, $todaslascat[0]->label, 'product_cat', true);

?>