<?php
	/*-------------------------
	Autor: Adrian Monsalve
	---------------------------*/
session_start();
$session_id = session_id();
$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : null;
$precio_venta = isset($_POST['titulo']) ? floatval($_POST['titulo']) : 0.0;

require_once ("../config/db.php");
require_once ("../config/conexion.php");

if (!empty($id) && !empty($cantidad) && $precio_venta > 0) {
	$stmt = mysqli_prepare($con, "INSERT INTO tmp (id_producto,cantidad_tmp,precio_tmp,session_id) VALUES (?,?,?,?)");
	mysqli_stmt_bind_param($stmt, 'iids', $id, $cantidad, $precio_venta, $session_id);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

if (isset($_GET['id'])) {
	$id_del = intval($_GET['id']);
	$stmt = mysqli_prepare($con, "DELETE FROM tmp WHERE id_tmp = ?");
	mysqli_stmt_bind_param($stmt, 'i', $id_del);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

?>
<table class="table">
<tr>
	<th>CODxxxIGO</th>
	<th>CANT.</th>
	<th>DESCRIPCION</th>
	<th><span class="pull-right">PRECIO UNIT.</span></th>
	<th><span class="pull-right">PRECIO TOTAL</span></th>
	<th></th>
</tr>
<?php
	$sumador_total = 0;
	$stmt = mysqli_prepare($con, "SELECT * FROM tmp WHERE session_id = ?");
	mysqli_stmt_bind_param($stmt, 's', $session_id);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	while ($tmp = mysqli_fetch_assoc($res)) {
		$id_tmp = htmlspecialchars($tmp['id_tmp'], ENT_QUOTES, 'UTF-8');
		$id_producto = intval($tmp['id_producto']);
		$cantidad = intval($tmp['cantidad_tmp']);
		$precio_venta = floatval($tmp['precio_tmp']);

		// fetch product info
		$producto_nombre = '';
		$marca_producto = '';
		$stmt2 = mysqli_prepare($con, "SELECT descripcion, id_marca_producto, titulo FROM lista7 WHERE id = ? LIMIT 1");
		if ($stmt2) {
			mysqli_stmt_bind_param($stmt2, 'i', $id_producto);
			mysqli_stmt_execute($stmt2);
			$res2 = mysqli_stmt_get_result($stmt2);
			if ($rowp = mysqli_fetch_assoc($res2)) {
				$producto_nombre = $rowp['descripcion'] ?? $rowp['titulo'] ?? '';
				$id_marca_producto = $rowp['id_marca_producto'] ?? null;
				if (!empty($id_marca_producto)) {
					$stmtm = mysqli_prepare($con, "SELECT nombre_marca FROM marcas WHERE id_marca = ? LIMIT 1");
					if ($stmtm) {
						mysqli_stmt_bind_param($stmtm, 'i', $id_marca_producto);
						mysqli_stmt_execute($stmtm);
						$resm = mysqli_stmt_get_result($stmtm);
						if ($rm = mysqli_fetch_assoc($resm)) {
							$marca_producto = ' ' . strtoupper($rm['nombre_marca']);
						}
						mysqli_stmt_close($stmtm);
					}
				}
			}
			mysqli_stmt_close($stmt2);
		}

		$precio_venta_f = number_format($precio_venta, 2);
		$precio_total = $precio_venta * $cantidad;
		$precio_total_f = number_format($precio_total, 2);
		$sumador_total += $precio_total;
		?>
		<tr>
			<td><?php echo htmlspecialchars($id_producto, ENT_QUOTES, 'UTF-8'); ?></td>
			<td><?php echo $cantidad; ?></td>
			<td><?php echo htmlspecialchars($producto_nombre . $marca_producto, ENT_QUOTES, 'UTF-8'); ?></td>
			<td><span class="pull-right"><?php echo $precio_venta_f; ?></span></td>
			<td><span class="pull-right"><?php echo $precio_total_f; ?></span></td>
			<td><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $id_tmp; ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
		</tr>
		<?php
	}

?>
<tr>
	<td colspan=4><span class="pull-right">TOTAL $</span></td>
	<td><span class="pull-right"><?php echo number_format($sumador_total,2);?></span></td>
	<td></td>
</tr>
</table>
			