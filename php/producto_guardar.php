<?php
	require_once "../inc/session_star.php";

	require_once "main.php";

	/*== Almacenando datos ==*/
	$nombre=filtro_seguridad($_POST['producto_nombre']);
    $referencia=filtro_seguridad($_POST['referencia']);
	$precio=filtro_seguridad($_POST['precio']);
    $peso=filtro_seguridad($_POST['peso']);
	$stock=filtro_seguridad($_POST['stock']);
	$categoria=filtro_seguridad($_POST['producto_categoria']);


	/*== Verificando campos obligatorios ==*/
    if($nombre=="" || $referencia=="" || $precio=="" || $peso=="" || $stock=="" || $categoria==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$referencia)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA REFERENCIA no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9.]{1,25}",$precio)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRECIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9.]{1,25}",$peso)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PESO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9]{1,25}",$stock)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El STOCK no coincide con el formato solicitado
            </div>
        ';
        exit();
    }


    /*== Verificando nombre ==*/
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre='$nombre'");
    if($check_nombre->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_nombre=null;

    /*== Verificando referencia ==*/
    $check_referencia=conexion();
    $check_referencia=$check_referencia->query("SELECT referencia FROM producto WHERE referencia='$referencia'");
    if($check_referencia->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La Referencia ingresada ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_referencia=null;


    /*== Verificando categoria ==*/
    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
    if($check_categoria->rowCount()<=0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoría seleccionada no existe
            </div>
        ';
        exit();
    }
    $check_categoria=null;



	/*== Guardando datos ==*/
    $guardar_producto=conexion();
    $guardar_producto=$guardar_producto->prepare("INSERT INTO producto(producto_nombre,referencia,precio,peso,stock,categoria_id) VALUES(:nombre,:referencia,:precio,:peso,:stock,:categoria)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":referencia"=>$referencia,
        ":precio"=>$precio,
        ":peso"=>$peso,
        ":stock"=>$stock,
        ":categoria"=>$categoria,
    ];

    $guardar_producto->execute($marcadores);

    if($guardar_producto->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO REGISTRADO!</strong><br>
                El producto se registro con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el producto, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_producto=null;