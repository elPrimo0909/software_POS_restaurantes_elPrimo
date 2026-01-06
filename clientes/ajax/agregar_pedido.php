
<?php

////LISTA DE PRODUCTOS AGREGADOS A LA FACTURA

/*-------------------------
Autor: Adrian Monsalve
---------------------------*/
session_start();
$session_id                               = session_id();
if (isset($_POST['id'])) {$id             = $_POST['id'];}
if (isset($_POST['cantidad'])) {$cantidad = $_POST['cantidad'];}
if (isset($_POST['codigo'])) {$codigo     = $_POST['codigo'];}
if (isset($_POST['prec'])) {$prec         = $_POST['prec'];}
if (isset($_POST['articulo'])) {$articulo = $_POST['articulo'];}

/// testing recogida POST
//echo $_POST['id'];

/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

//GURDANDO DATOS POST EN LA DB

if (!empty($id) and !empty($cantidad) and !empty($prec)) {
	$id_i = intval($id);
	$cantidad_i = intval($cantidad);
	$num_val = floatval($prec);
	$stmt = mysqli_prepare($con, "INSERT INTO ostemporal (id,titulo,come,celu,num,estado) VALUES (?,?,?,?,?,?)");
	mysqli_stmt_bind_param($stmt, 'isidss', $id_i, $articulo, $cantidad_i, $session_id, $num_val, $codigo);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}
if (isset($_GET['id'])) // codigo elimina un elemento del array
{
	$id_del = intval($_GET['id']);
	$stmt = mysqli_prepare($con, "DELETE FROM ostemporal WHERE id = ?");
	mysqli_stmt_bind_param($stmt, 'i', $id_del);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

///TESTING pasar id correctamente
//echo $_POST['cantidad'];

?>
<table class="table">
<tr>
	<th class="visible-md visible-lg" >Código</th>
	<th>Cantidad</th>
	<th>Descripción</th>
	<th><span class="pull-right">PRECIO UNIT.</span></th>
	<th class="visible-md visible-lg" ><span class="pull-right">TOTAL</span></th>
	<th></th>
</tr>
<?php
 $sumador_total = 0;
 $suma = 0;

///testing/////echo $session_id;
///CONSULTA UTILIZANDO LA SESSION

/* Use prepared statement to fetch session items */
$stmt = mysqli_prepare($con, "SELECT * FROM ostemporal WHERE celu = ?");
mysqli_stmt_bind_param($stmt, 's', $session_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_array($res))

/*
$sql=mysqli_query($con, "select * from lista7 where id ='".$_POST['id']."'");
while ($row=mysqli_fetch_array($sql))
 */

{
	$id_tmp          = $row["id"];
	$codigo_producto = htmlspecialchars($row['estado'], ENT_QUOTES, 'UTF-8');
	$cantidad        = intval($row['come']);
	$nombre_producto = htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8');
	$precio          = floatval($row['num']);
	if (!empty($id_marca_producto)) {
		$sql_marca              = mysqli_query($con, "select nombre_marca from marcas where id_marca='$id_marca_producto'");
		$rw_marca               = mysqli_fetch_array($sql_marca);
		$nombre_marca           = $rw_marca['nombre_marca'];
		$marca_producto         = " ".strtoupper($nombre_marca);
	} else { $marca_producto = '';}
	$precio_venta            = $row['precio_tmp'];
	$precio_venta_f          = number_format($precio_venta, 2);//Formateo variables
	$precio_venta_r          = str_replace(",", "", $precio_venta_f);//Reemplazo las comas
	$precio_total            = $precio_venta_r*$cantidad;
	$precio_total_f          = number_format($precio_total, 2);//Precio total formateado
	$precio_total_r          = str_replace(",", "", $precio_total_f);//Reemplazo las comas
	$sumador_total += $precio_total_r;//Sumador

	$subto = $precio*$cantidad;

	$suma = $suma+$subto;

	?>
											<tr>
												<td class="visible-md visible-lg" ><?php echo $codigo_producto;?></td>
												<td><?php echo $cantidad;?></td>
												<td><?php echo $nombre_producto . htmlspecialchars($marca_producto, ENT_QUOTES, 'UTF-8');?></td>
												<td><span class="pull-right"><?php

	echo "$";

	echo number_format($precio);?></span></td>
												<td class="visible-md visible-lg" ><span class="pull-right"><?php echo "$" . number_format($subto);?></span></td>
												<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo htmlspecialchars($id_tmp, ENT_QUOTES, 'UTF-8');?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
											</tr>
	<?php
}

?>
<tr>
	<td colspan=4><span class="pull-right">SUB TOTAL: <?php echo " $";
echo number_format($suma);
?></span></td>
	
	<td></td>
</tr>
</table>
