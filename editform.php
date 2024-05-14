<?php
session_start();

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pisid';

// Check if the techmice_user cookie is set
if (!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current experience data if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Conexão falhada: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM experiencia WHERE ID = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $descricao = $row['Descricao'];
        $numero_ratos = $row['Numero_Ratos'];
        $limite_ratos_sala = $row['Limite_Ratos_Sala'];
        $segundos_sem_movimento = $row['Segundos_Sem_Movimento'];
        $temperatura_ideal = $row['Temperatura_Ideal'];
        $variacao_temperatura_maxima = $row['Variacao_Temperatura_Maxima'];
    } else {
        echo "Nenhuma experiência encontrada com o ID fornecido.";
        exit();
    }

    mysqli_close($conn);
} else {
    echo "ID da experiência não fornecido.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Conexão falhada: " . mysqli_connect_error());
    }

    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $numero_ratos = $_POST['numero-ratos'];
    $limite_ratos_sala = $_POST['limite-ratos-sala'];
    $segundos_sem_movimento = $_POST['segundos-sem-movimento'];
    $temperatura_ideal = $_POST['temperatura-ideal'];
    $variacao_temperatura_maxima = $_POST['variacao-temperatura-maxima'];

    $update_query = "UPDATE experiencia SET Descricao = '$descricao', Numero_Ratos = '$numero_ratos', Limite_Ratos_Sala = '$limite_ratos_sala', 
                    Segundos_Sem_Movimento = '$segundos_sem_movimento', Temperatura_Ideal = '$temperatura_ideal', Variacao_Temperatura_Maxima = '$variacao_temperatura_maxima' 
                    WHERE ID = $id";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Experiência atualizada com sucesso!');</script>";
    } else {
        echo "Erro ao atualizar a experiência: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Experiência - TECH MICE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="general-container">
        <div class="logo-container">
            <img src="logo.jpeg" alt="Logotipo TECH MICE" class="imagem-circular">
        </div>
        <h1>Editar Experiência</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirm('Tem certeza que deseja gravar a experiência?')" class="form-container">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" id="descricao" name="descricao" placeholder="Descrição da experiência" required value="<?php echo isset($descricao) ? $descricao : ''; ?>">
            <input type="number" id="numero-ratos" name="numero-ratos" placeholder="Número de Ratos" required value="<?php echo isset($numero_ratos) ? $numero_ratos : ''; ?>">
            <input type="number" id="limite-ratos-sala" name="limite-ratos-sala" placeholder="Limite de Ratos por Sala" required value="<?php echo isset($limite_ratos_sala) ? $limite_ratos_sala : ''; ?>">
            <input type="number" id="segundos-sem-movimento" name="segundos-sem-movimento" placeholder="Segundos Sem Movimento" required value="<?php echo isset($segundos_sem_movimento) ? $segundos_sem_movimento : ''; ?>">
            <input type="text" id="temperatura-ideal" name="temperatura-ideal" placeholder="Temperatura Ideal" required value="<?php echo isset($temperatura_ideal) ? $temperatura_ideal : ''; ?>">
            <input type="text" id="variacao-temperatura-maxima" name="variacao-temperatura-maxima" placeholder="Variação de Temperatura Máxima" required value="<?php echo isset($variacao_temperatura_maxima) ? $variacao_temperatura_maxima : ''; ?>">
            <div class="action-buttons">
                <button type="submit">Gravar Experiência</button>
                <button type="button" onclick="window.location.href='addodor.html'">Adicionar Odor</button>
                <button type="button" onclick="window.location.href='addsub.html'">Adicionar Substância</button>
            </div>
        </form>
    </div>
</body>
</html>
