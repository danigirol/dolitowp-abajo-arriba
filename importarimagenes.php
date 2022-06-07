<?php 

//https://api.stressgijon.com/imagen?id_producto=491&token=dd864d395f4ec8ad539c1e650467bdfa


   $context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
    
    
    $data = file_get_contents("https://api.stressgijon.com/imagen?id_producto=".$iddolibar."&token=".$token,false, $context);
    
    
    $data2=json_decode($data);

print_r($data2);

$id_producto=491;

$Base64Img = $data2[0]->imagen;

 
//eliminamos data:image/png; y base64, de la cadena que tenemos
//hay otras formas de hacerlo                   
list(, $Base64Img) = explode(';', $Base64Img);
list(, $Base64Img) = explode(',', $Base64Img);
//Decodificamos $Base64Img codificada en base64.
$Base64Img = base64_decode($Base64Img);
//escribimos la información obtenida en un archivo llamado 
//unodepiera.png para que se cree la imagen correctamente
file_put_contents('./imagenes/'.$id_producto.'-2.png', $Base64Img);    
echo "<img src='./imagenes/".$id_producto."-2.png' width='150px' />";

echo 'Se ha insertado correctamente';
	
	
	
	
	
	
$upload_dir       = wp_upload_dir();
  $image_url        = './imagenes/'.$id_producto.'-2.png'; // Define the image URL here

			
    $image_name       = $id_producto.'-2.png';
     // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name );	
			
    copy('./imagenes/'.$id_producto.'-2.png',$upload_dir['path'].'/'.$unique_file_name);
			
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

/*
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
             wp_update_attachment_metadata($attach_id, $attach_data);
*/
/*
  foreach (array('_wp_attachment_metadata', '_wp_attached_file', '_wp_attachment_image_alt') as $key) {
         if ($meta = get_post_meta($post_id, $key, true)) {
             add_post_meta($tr_id, $key, $meta);
         }
     }


*/


    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		// Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );
	
	//update_post_meta( $post_id, '_product_image_gallery', $attach_id );	

set_post_thumbnail( $post_id, $attach_id );		

//unlink('../imagenes/'.$id_producto.'-2.png');



?>
