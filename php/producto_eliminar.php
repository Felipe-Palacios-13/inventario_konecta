<?php
	/*== Almacenando datos ==*/
    $product_id_del=filtro_seguridad($_GET['product_id_del']);

    /*== Verificando producto ==*/
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM producto WHERE id='$product_id_del'");

    if($check_producto->rowCount()==1){

    	$datos=$check_producto->fetch();

    	$eliminar_producto=conexion();
    	$eliminar_producto=$eliminar_producto->prepare("DELETE FROM producto WHERE id=:id");

    	$eliminar_producto->execute([":id"=>$product_id_del]);

    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRODUCTO que intenta eliminar no existe
            </div>
        ';
    }
    $check_producto=null;