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

// Handle "Terminar Experiência" button click
if(isset($_POST['terminar_experiencia'])) {
    // Update Estado to "Finalizada" in the database
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Conexão falhada: " . mysqli_connect_error());
    }

    $update_query = "UPDATE experiencia SET Estado = 'Finalizada' WHERE ID = $id";
    if(mysqli_query($conn, $update_query)) {
        // Redirect to painelex.php after updating Estado
        header("Location: painelex.php");
        exit();
    } else {
        echo "Erro ao terminar a experiência: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Execução da Experiência - TECH MICE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="general-container">
        <div class="logo-container">
            <img src="logo.jpeg" alt="Logotipo TECH MICE" class="imagem-circular">
        </div>
        <h1>Detalhes da Experiência em Execução</h1>
        <div class="form-container">
            <p><strong>Descrição:</strong> <?php echo isset($descricao) ? $descricao : ''; ?></p>
            <p><strong>Número de Ratos:</strong> <?php echo isset($numero_ratos) ? $numero_ratos : ''; ?></p>
            <p><strong>Limite de Ratos por Sala:</strong> <?php echo isset($limite_ratos_sala) ? $limite_ratos_sala : ''; ?></p>
            <p><strong>Segundos Sem Movimento:</strong> <?php echo isset($segundos_sem_movimento) ? $segundos_sem_movimento : ''; ?></p>
            <p><strong>Temperatura Ideal:</strong> <?php echo isset($temperatura_ideal) ? $temperatura_ideal : ''; ?></p>
            <p><strong>Variação de Temperatura Máxima:</strong> <?php echo isset($variacao_temperatura_maxima) ? $variacao_temperatura_maxima : ''; ?></p>
        </div>
        <h2>Alertas da Experiência</h2>
        <div class="alerts-container">
            <div class="alert yellow">Alerta: Temperatura excedeu o limite máximo!</div>
            <div class="alert ">Informação: Sistema de ventilação ativado.</div>
            <div class="alert red">Alerta Crítico: Um dos ratos não está se movendo.</div>
            <div class="alert ">Informação: Dados sendo coletados.</div>
        </div>
        <div class="action-buttons">
            <form method="post">
                <button type="submit" name="terminar_experiencia">Terminar Experiência</button>
            </form>
        </div>
    </div>
</body>
</html>
