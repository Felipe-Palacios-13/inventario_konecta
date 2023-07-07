<?php
	$categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;
	
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	$campos="producto.id,producto.referencia,producto.producto_nombre,producto.precio,producto.peso,producto.stock,producto.categoria_id,producto.usuario_id,categoria.categoria_id,categoria.categoria_nombre";

	if(isset($busqueda) && $busqueda!=""){

		$consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id WHERE producto.referencia LIKE '%$busqueda%' OR producto.producto_nombre LIKE '%$busqueda%' ORDER BY producto.stock DESC $inicio,$registros";

		$consulta_total="SELECT COUNT(id) FROM producto WHERE referencia LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";

	}elseif($categoria_id>0){

		$consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id WHERE producto.categoria_id = '$categoria_id' ORDER BY producto.stock DESC $inicio,$registros";

		$consulta_total="SELECT COUNT(id) FROM producto WHERE categoria_id='$categoria_id'";

	}else{

		$consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id ORDER BY producto.stock DESC  $inicio,$registros";

		$consulta_total="SELECT COUNT(id) FROM producto";

	}

	$conexion=conexion();

	$datos = $conexion->query($consulta_datos);
	$datos = $datos->fetchAll();

	$total = $conexion->query($consulta_total);
	$total = (int) $total->fetchColumn();

	$Npaginas =ceil($total/$registros);

	if($total>=1 && $pagina<=$Npaginas){
		$contador=$inicio+1;
		$pag_inicio=$inicio+1;
		foreach($datos as $rows){
			
			   $tabla.='</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>'.$contador.' - '.$rows['producto_nombre'].'</strong><br>
			                <strong>REFERENCIA:</strong> '.$rows['referencia'].', <strong>PRECIO:</strong> $'.$rows['precio'].', <strong>PESO:</strong> $'.$rows['peso'].',<strong>STOCK:</strong> '.$rows['stock'].', <strong>CATEGORIA:</strong> '.$rows['categoria_nombre'].'
			              </p>
			            </div>
			            <div class="has-text-right">
			                <a href="index.php?vista=product_update&product_id_up='.$rows['id'].'" class="button is-success is-rounded is-small">Actualizar</a>
			                <a href="'.$url.$pagina.'&product_id_del='.$rows['id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
			            </div>
			        </div>
			    </article>

			    <hr>
            ';
            $contador++;
		}
		$pag_final=$contador-1;
	}else{
		if($total>=1){
			$tabla.='
				<p class="has-text-centered" >
					<a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
						Haga clic acá para recargar el listado
					</a>
				</p>
			';
		}else{
			$tabla.='
				<p class="has-text-centered" >No hay registros en el sistema</p>
			';
		}
	}

	if($total>0 && $pagina<=$Npaginas){
		$tabla.='<p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}