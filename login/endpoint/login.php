<?php
session_start(); 
include ('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT `password` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username );
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];

        // Verify password using password_verify. Support legacy plaintext by upgrading hash on first login.
        if (password_verify($password, $stored_password)) {
            $_SESSION["user"] = $username;
            echo "<script>window.location.href = 'http://localhost/SM-RESTAURANTES/pos/';</script>";
        } elseif ($password === $stored_password) {
            // Legacy: stored password is plaintext. Re-hash and update DB.
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE `tbl_user` SET `password` = :ph WHERE `username` = :username");
            $upd->execute([':ph' => $newHash, ':username' => $username]);
            $_SESSION["user"] = $username;
            echo "<script>window.location.href = 'http://localhost/SM-RESTAURANTES/pos/';</script>";
        } else {
            echo "<script>window.location.href = 'http://localhost/SM-RESTAURANTES/login/';</script>";
        }
    } else {
        echo "
            <script>
               alert('Usuario o Contraseña Invalida!'); 
                window.location.href = 'http://localhost/SM-RESTAURANTES/login/';
            </script>
            ";
    }
}

?>