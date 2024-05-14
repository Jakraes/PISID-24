<?php
session_start();

if(!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit();
}

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pisid';

$user_id = $_COOKIE['user_id'];

// Check if the ID parameter is set in the URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the experience details from the database
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Conexão falhada: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM experiencia WHERE ID = $id";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $descricao = $row['Descricao'];
        $numero_ratos = $row['Numero_Ratos'];
        $limite_ratos_sala = $row['Limite_Ratos_Sala'];
        $segundos_sem_movimento = $row['Segundos_Sem_Movimento'];
        $temperatura_ideal = $row['Temperatura_Ideal'];
        $variacao_temperatura_maxima = $row['Variacao_Temperatura_Maxima'];
    } else {
        echo "Experiência não encontrada.";
    }

    mysqli_close($conn);
} else {
    echo "ID da experiência não especificado.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect($hostname, $username, $password, $database);

    if (!$conn) {
        die("Conexão falhada: " . mysqli_connect_error());
    }

    if(isset($_POST['descricao'])){
        $descricao = $_POST['descricao'];
        $numero_ratos = $_POST['numero_ratos'];
        $limite_ratos_sala = $_POST['limite_ratos_sala'];
        $segundos_sem_movimento = $_POST['segundos_sem_movimento'];
        $temperatura_ideal = $_POST['temperatura_ideal'];
        $variacao_temperatura_maxima = $_POST['variacao_temperatura_maxima'];
        $estado = 'Aguarda Início';

        $sql = "INSERT INTO experiencia (Descricao, ID_Utilizador, Numero_Ratos, Limite_Ratos_Sala, Segundos_Sem_Movimento, Temperatura_Ideal, Variacao_Temperatura_Maxima, Estado) 
                VALUES ('$descricao', '$user_id', '$numero_ratos', '$limite_ratos_sala', '$segundos_sem_movimento', '$temperatura_ideal', '$variacao_temperatura_maxima', '$estado')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Experiência gravada com sucesso!');</script>";
            header("Location: painelex.php");
            exit(); // Redirect after submitting
        } else {
            echo "Erro ao gravar a experiência: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Repetir Experiência - TECH MICE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="general-container">
        <div class="logo-container">
            <img src="logo.jpeg" alt="Logotipo TECH MICE" class="imagem-circular">
        </div>
        <h1>Repetir Experiência</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirm('Tem certeza que deseja repetir a experiência?')" class="form-container">
            <input type="text" name="descricao" placeholder="Descrição da experiência" required value="<?php echo isset($descricao) ? $descricao : ''; ?>">
            <input type="number" name="numero_ratos" placeholder="Número de Ratos" required value="<?php echo isset($numero_ratos) ? $numero_ratos : ''; ?>">
            <input type="number" name="limite_ratos_sala" placeholder="Limite de Ratos por Sala" required value="<?php echo isset($limite_ratos_sala) ? $limite_ratos_sala : ''; ?>">
            <input type="number" name="segundos_sem_movimento" placeholder="Segundos Sem Movimento" required value="<?php echo isset($segundos_sem_movimento) ? $segundos_sem_movimento : ''; ?>">
            <input type="text" name="temperatura_ideal" placeholder="Temperatura Ideal" required value="<?php echo isset($temperatura_ideal) ? $temperatura_ideal : ''; ?>">
            <input type="text" name="variacao_temperatura_maxima" placeholder="Variação de Temperatura Máxima" required value="<?php echo isset($variacao_temperatura_maxima) ? $variacao_temperatura_maxima : ''; ?>">
            <div class="action-buttons">
                <button type="submit">Repetir Experiência</button>
            </div>
        </form>
    </div>
</body>
</html>
