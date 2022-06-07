jQuery( document ).ready(function() {
			
		var token='4568962941062348156481';
		jQuery.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        dataType: "html", // add data type
        data: { action : 'get_ajax_importar_todo',token:token},
		 beforeSend: function() {
                jQuery('#mostrarprecio').html('Cargando');   
            // alert("estoy enviando");
            },   
        success: function( response ) {
            console.log( response );
//alert("respondio");
            jQuery( '#mostrarprecio' ).html( response ); 
        }
    });
				
						
		
		
		
	
	
});