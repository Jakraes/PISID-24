<?php
session_start();

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pisid';

$username_not_found = false;

if(isset($_COOKIE['username'])) {
    header("Location: painelex.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect($hostname, $username, $password, $database);

    if (!$conn) {
        die("Conexão falhada: " . mysqli_connect_error());
    }

    if(isset($_POST['username'])){
        $username = $_POST['username'];

        $query = "SELECT ID, Nome FROM utilizador WHERE Nome = '$username'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $user_id = $row['ID'];

                setcookie("username", $username);
                setcookie("user_id", $user_id);

                header("Location: experience_list.php");
                exit();
            } else {
                $username_not_found = true;
            }
        } else {
            echo "Erro ao executar a consulta: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - TECH MICE</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        <?php if($username_not_found): ?>
        window.onload = function() {
            alert("User não existe!");
        };
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="general-container">
        <div class="logo-container">
            <img src="logo.jpeg" alt="Logotipo TECH MICE" class="imagem-circular">
        </div>
        <h1>TECH MICE</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-container">
            <input type="text" id="username" name="username" placeholder="Nome de utilizador" required>
            <div class="checkbox-container" style="align-items: left;">
                <label for="remember-me">Lembrar de mim?</label>
                <input type="checkbox" id="remember-me" name="remember_me">
            </div>
            <button type="submit">Entrar</button>
            <a href="#" class="forgot-password">Esqueceu a palavra-passe?</a>
        </form>
    </div>
</body>
</html>
