
<script type="text/javascript">
$(document).ready(function(){
    $("#myModal").on('shown.bs.modal', function(){
        $(this).find('#q').focus();
    });
});
</script>



<?php

error_reporting(E_ERROR | E_PARSE); ////no listar Warnings


//SE LISTA PRODUCTOS PARA AGREGAR A LA FACTURA
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos


$perfil2    = mysqli_query($con, "select * from perfil limit 0,1");
$rw_perfil2 = mysqli_fetch_array($perfil2);



$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL)?$_REQUEST['action']:'';
if ($action == 'ajax') {
	// escaping and prepare for LIKE search
	$q        = trim(strip_tags($_REQUEST['q'] ?? ''));
	$sTable   = "clientes2";
	$sWhere   = "";
	$params = [];
	if ($q !== "") {
		// use prepared statements with three LIKE placeholders (id as string fallback)
		$like = "%" . $q . "%";
		$sWhere = "WHERE (id LIKE ? OR empresa LIKE ? OR identificacion LIKE ? )";
		$params = [$like, $like, $like];
	}
	include 'pagination.php';//include pagination file
	//pagination variables
	$page      = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page  = 5;//how much records you want to show
	$adjacents = 4;//gap between pages after number of adjacents
	$offset    = ($page-1)*$per_page;
	//Count the total number of row in your table*/
	// prepare count query
	if ($sWhere === "") {
		$count_sql = "SELECT count(*) AS numrows FROM $sTable";
		$count_stmt = mysqli_prepare($con, $count_sql);
		mysqli_stmt_execute($count_stmt);
		$count_res = mysqli_stmt_get_result($count_stmt);
		$row = mysqli_fetch_array($count_res);
		$numrows = $row['numrows'];
		mysqli_stmt_close($count_stmt);
	} else {
		$count_sql = "SELECT count(*) AS numrows FROM $sTable $sWhere";
		$count_stmt = mysqli_prepare($con, $count_sql);
		mysqli_stmt_bind_param($count_stmt, 'sss', $params[0], $params[1], $params[2]);
		mysqli_stmt_execute($count_stmt);
		$count_res = mysqli_stmt_get_result($count_stmt);
		$row = mysqli_fetch_array($count_res);
		$numrows = $row['numrows'];
		mysqli_stmt_close($count_stmt);
	}
	$total_pages = ceil($numrows/$per_page);
	$reload      = './index.php';
	//main query to fetch the data
	// fetch results with prepared statement and pagination
	$sql = "SELECT * FROM $sTable " . ($sWhere ? $sWhere : '') . " ORDER by id DESC LIMIT ?, ?";
	$stmt = mysqli_prepare($con, $sql);
	if ($sWhere === "") {
		mysqli_stmt_bind_param($stmt, 'ii', $offset, $per_page);
	} else {
		// bind three like params plus offset and limit
		mysqli_stmt_bind_param($stmt, 'sssii', $params[0], $params[1], $params[2], $offset, $per_page);
	}
	mysqli_stmt_execute($stmt);
	$query = mysqli_stmt_get_result($stmt);
	//loop through fetched data
	if ($numrows > 0) {

		?>
		<div class="table-responsive">
<table class="table">
<tr  class="">
<th>Identificacion</th>
<th>Nombres</th>
<th>Email</th>
<th>Dirección</th>
<th>Teléfono</th>




<?
}

?>






<th></th>
</tr>
		<?php while ($row = mysqli_fetch_array($query)) {
			$id_producto       = $row['id'];
			$codigo            = $row['empresa'];
			$email            = $row['email'];
			$telefono          = $row['telefono'];

			$identificacion          = $row['identificacion'];
            $direccion          = $row['direccion'];

			?>
			
                
			<td><?php echo $identificacion;?></td>
			<td><?php echo $codigo?></td>
            <td><?php echo $email;?></td>

            <td><?php echo $direccion?></td>

			<td><?php echo $telefono?></td>




			</div></td>
			<td >
			
            <button type="button" class="btn btn-sample" class="btn pull-right" data-toggle="modal" data-target="#dataUpdate"  data-id="<?php echo $row['id']?>" data-codigo="<?php echo $row['identificacion']?>" data-nombre="<?php echo $row['empresa']?>" data-moneda="<?php echo $row['direccion']?>" data-capital="<?php echo $row['telefono']?>" data-continente="<?php echo $row['email']?>" ><i class=''></i>Detalles</button>
       
            <button type="button" class="btn btn-sample" class="btn pull-right" data-toggle="modal" data-target="#dataDelete"   data-id="<?php echo $row['id']?>"  ><i class=''></i>Eliminar</button>
       	
			
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