<?php
session_start();

// Check if the techmice_user cookie is set
if(!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit();
}

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pisid';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect($hostname, $username, $password, $database);

    if (!$conn) {
        die("Conexão falhada: " . mysqli_connect_error());
    }

    if(isset($_POST['nome']) && isset($_POST['sala'])){
        $nome = $_POST['nome'];
        $sala = $_POST['sala'];

        // Fetch the ID of the experience with Estado 'A decorrer'
        $query = "SELECT ID FROM experiencia WHERE Estado = 'A Decorrer' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $id_experiencia = $row['ID'];

            // Insert the odor with the fetched experience ID
            $sql = "INSERT INTO odor (Nome, Sala, ID_Experiencia) VALUES ('$nome', '$sala', '$id_experiencia')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Odor gravado com sucesso!');</script>";
            } else {
                echo "Erro ao gravar o odor: " . mysqli_error($conn);
            }
        } else {
            echo "Nenhuma experiência 'A Decorrer' encontrada.";
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Odor - TECH MICE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="general-container">
        <div class="logo-container">
            <img src="logo.jpeg" alt="Logotipo TECH MICE" class="imagem-circular">
        </div>
        <h1>Adicionar Odor</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirmarGravacao()" class="form-container">
            <input type="text" id="nome" name="nome" placeholder="Nome do Odor" required>
            <input type="number" id="sala" name="sala" placeholder="Número da Sala" required>
            <div class="action-buttons">
                <button type="button" onclick="voltar()">Voltar</button>
                <button type="submit" name="action" value="Gravar Odor">Gravar Odor</button>
            </div>
        </form>
    </div>

    <script>
        function voltar() {
            window.history.back();
        }

        function confirmarGravacao() {
            var confirmado = confirm('Você confirma a gravação deste odor?');
            if (confirmado) {
                alert('Gravação confirmada!');
            }
            return confirmado;
        }
    </script>
</body>
</html>
