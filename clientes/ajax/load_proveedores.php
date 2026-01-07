<?php
// connect to database
include ("../config/db.php");
$con    = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$search = isset($_GET['q']) ? strip_tags(trim($_GET['q'])) : '';
$data = array();
if ($search !== '') {
	$like = "%" . $search . "%";
	$stmt = mysqli_prepare($con, "SELECT id, empresa FROM clientes2 WHERE empresa LIKE ? LIMIT 40");
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 's', $like);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_assoc($res)) {
			$data[] = array('id' => (int)$row['id'], 'text' => htmlspecialchars($row['empresa'], ENT_QUOTES, 'UTF-8'));
		}
		mysqli_stmt_close($stmt);
	}
}
// return the result in json
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>