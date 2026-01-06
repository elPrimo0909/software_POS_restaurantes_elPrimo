
<script type="text/javascript">
$(document).ready(function(){
    $("#myModal").on('shown.bs.modal', function(){
        $(this).find('#q').focus();
    });
});
</script>
<?php



//SE LISTA PRODUCTOS PARA AGREGAR A LA FACTURA
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos


/* fetch perfil */
$stmt = mysqli_prepare($con, "SELECT * FROM perfil LIMIT 1");
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$rw_perfil2 = mysqli_fetch_array($res);
mysqli_stmt_close($stmt);



$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL)?$_REQUEST['action']:'';
if ($action == 'ajax') {
	// escaping, additionally removing everything that could be (html/javascript-) code
	$q        = strip_tags($_REQUEST['q'], ENT_QUOTES);
	$q_trim   = trim($q);
	$aColumns = array('id', 'codigo', 'descripcion');//Columnas de busqueda
	$sTable   = "lista7";
	$sWhere   = "";
	$use_like = false;
	if (!empty($q_trim)) {
		$use_like = true;
		$sWhere = "WHERE (".$aColumns[0]." LIKE ? OR ".$aColumns[1]." LIKE ? OR ".$aColumns[2]." LIKE ?)";
		$like = "%".$q_trim."%";
	}
	include 'pagination.php';//include pagination file
	//pagination variables
	$page      = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page  = 5;//how much records you want to show
	$adjacents = 4;//gap between pages after number of adjacents
	$offset    = ($page-1)*$per_page;
	//Count the total number of row in your table
	if ($use_like) {
		$stmt = mysqli_prepare($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
		mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
		mysqli_stmt_execute($stmt);
		$res_count = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($res_count);
		mysqli_stmt_close($stmt);
		$numrows = $row['numrows'];
	} else {
		$stmt = mysqli_prepare($con, "SELECT count(*) AS numrows FROM $sTable");
		mysqli_stmt_execute($stmt);
		$res_count = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($res_count);
		mysqli_stmt_close($stmt);
		$numrows = $row['numrows'];
	}
	$total_pages = ceil($numrows/$per_page);
	$reload      = './index.php';
	//main query to fetch the data
	if ($use_like) {
		$stmt = mysqli_prepare($con, "SELECT * FROM $sTable $sWhere ORDER BY id DESC LIMIT ?, ?");
		mysqli_stmt_bind_param($stmt, 'sssii', $like, $like, $like, $offset, $per_page);
		mysqli_stmt_execute($stmt);
		$query = mysqli_stmt_get_result($stmt);
		mysqli_stmt_close($stmt);
	} else {
		$stmt = mysqli_prepare($con, "SELECT * FROM $sTable ORDER BY id DESC LIMIT ?, ?");
		mysqli_stmt_bind_param($stmt, 'ii', $offset, $per_page);
		mysqli_stmt_execute($stmt);
		$query = mysqli_stmt_get_result($stmt);
		mysqli_stmt_close($stmt);
	}
	//loop through fetched data
	if ($numrows > 0) {

		?>
		<div class="table-responsive">
<table class="table">
<tr  class="">
<th>Código</th>
<th>Descripción</th>

<th><span class="pull-right">Cantidad</span></th>


<th><span class="pull-right">Valor Venta</span></th>

<?
if ($rw_perfil2['regimen']=="Comun") {

	?>
	
<th><span class="pull-right">Base</span></th>
<th><span class="pull-right">Impuesto</span></th>

<?
}

?>






<th style="width: 36px;"></th>
</tr>
		<?php while ($row = mysqli_fetch_array($query)) {
			$id_producto       = (int)$row['id'];
			$codigo            = htmlspecialchars($row['codigo'], ENT_QUOTES, 'UTF-8');
			$imp1              = (float)$row['impuesto'];
			$articulo          = htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8');
			$id_marca_producto = isset($row['id_marca_producto']) ? (int)$row['id_marca_producto'] : 0;
			$codigo_producto   = isset($row['codigo_producto']) ? htmlspecialchars($row['codigo_producto'], ENT_QUOTES, 'UTF-8') : '';
			$nombre_marca      = '';
			if ($id_marca_producto) {
				$stmt_m = mysqli_prepare($con, "SELECT nombre_marca FROM marcas WHERE id_marca = ? LIMIT 1");
				mysqli_stmt_bind_param($stmt_m, 'i', $id_marca_producto);
				mysqli_stmt_execute($stmt_m);
				$res_m = mysqli_stmt_get_result($stmt_m);
				$rw_marca = mysqli_fetch_array($res_m);
				if ($rw_marca) {
					$nombre_marca = htmlspecialchars($rw_marca['nombre_marca'], ENT_QUOTES, 'UTF-8');
				}
				mysqli_stmt_close($stmt_m);
			}
			$prec = (float)$row['precio_venta'];








			?>
			<tr>
			<td><?php echo $codigo;?></td>
			<td><?php echo $articulo;?></td>



			<td class='col-xs-1'>
			<div class="pull-right">
			<!--  enviar la variable a ajax-->
			<input type="hidden" class="form-control" style="text-align:right" id="codigo_<?php echo $id_producto;?>"  value="<?php echo ($codigo)?>" >

			<input type="hidden" class="form-control" style="text-align:right" id="articulo_<?php echo $id_producto;?>"  value="<?php echo ($articulo)?>" >
			<input type="hidden" class="form-control" style="text-align:right" id="prec88_<?php echo $id_producto;?>"  value="<?php echo ($prec)?>" >


			<input type="number" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto;?>"  value="1" >


			</div></td>
		
		
			<?php 





$ersa=($prec*$imp1)/100;

 $cel2aa= ceil($ersa);

$przzec=$prec+$cel2aa;

			 ?>


			</div></td>

	<td class='col-xs-2'><div class="pull-right">
			<input type="number" class="form-control" style="text-align:right" id="prec_<?php echo $id_producto;?>"  value="<?php

	



			echo  ($przzec)?>" >


			</div></td>





<?
if ($rw_perfil2['regimen']=="Comun") {

	?>
	
<td class='col-xs-1'><div class="pull-right" >
			<?php  echo "$"; echo number_format($prec) ; ?>


			</div></td>
			</div></td>


			<td class='col-xs-1'><div class="pull-right" >
			<?php echo ($imp1); echo "%"; ?>


<?
}

?>






			</div></td>
			<td ><span class="pull-right"><a href="#" onclick="agregar('<?php echo $id_producto?>')">Agregar</i></a></span></td>
			</tr>
			<?php
		}
		?>
		<tr>
		<td colspan=5><span class="pull-right"><?php
		echo paginate($reload, $page, $total_pages, $adjacents);
		?></span></td>
		</tr>
		</table>
		</div>
		<?php
	}
}
?>