


<h2></h2>
	<table class="wp-list-table widefat fixed striped table-view-list posts">
	<thead>
	<tr>

		
		
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>Id Dolibar</span><span class="sorting-indicator"></span></a></th>
		
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>Id WP</span><span class="sorting-indicator"></span></a></th>
        
        
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>STOCK DOLIBAR</span><span class="sorting-indicator"></span></a></th>
        
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>STOCK WP</span><span class="sorting-indicator"></span></a></th>
		
		
	</tr>
	</thead>

	<tbody id="the-list">
		
		<?php 
 global $wpdb;
    $result = $wpdb->get_results( "SELECT * FROM  conector WHERE id_wordpress is NOT NULL"  );

 
		
    foreach ($result as $page) {?>
		<tr id="post-1" class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-sin-categoria">
		
					<td class="author column-author" data-colname="Autor">
					
					<?php //echo print_r($page);?>
						<?php echo $page->id_dolibar?>
						
						
					</td>
					<td class="author column-author" data-colname="Autor">
					
			<?php echo $page->id_wordpress?>
                        
                        
						
						
					</td>
						<td class="author column-author" data-colname="Autor">
					
                            
                            
			<?php echo $page->id_wordpress?>
                    dfsdf        
                  <?php           
                    
		
		$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
    
    
    $data = file_get_contents("https://api.stressgijon.com/productovariaciones?id_producto=".$page->id_dolibar."&token=".$token,false, $context);
							
	//echo "https://api.stressgijon.com/productovariaciones?id_producto=".$page->id_dolibar."&token=".$token;
								
	//print_r($data);			
								
	$decodificador=json_decode($data);							
		
	//print_r($decodificador);
								
								
	foreach($decodificador as $tallas){
		//print_r($tallas);
		//echo $tallas->fk_product_child;
		
		 $data_stock = file_get_contents("https://api.stressgijon.com/stock?id_producto=".@$tallas->fk_product_child."&token=".$token,false, $context);
		
		//print_r($data_stock);
		
		
		
	};
								
								
								
								
							
							?>
							
							
						
						
					</td>
            <td class="author column-author" data-colname="Autor">
					
			<?php echo $page->id_wordpress?>
						
						
					</td>
					
					
					</tr><?php }?>
			</tbody>

	<tfoot>
		<tr>
		
		
		
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>Id Dolibar</span><span class="sorting-indicator"></span></a></th>
		
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>Id WP</span><span class="sorting-indicator"></span></a></th>
		
            
        <th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>STOCK DOLIBAR</span><span class="sorting-indicator"></span></a></th>
		
            
             <th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href=""><span>STOCK WP</span><span class="sorting-indicator"></span></a></th>
	</tr>
	</tfoot>

</table>
		
		
