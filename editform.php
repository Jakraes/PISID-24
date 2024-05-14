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

// Fetch the current experience data
$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
    die("Conexão falhada: " . mysqli_connect_error());
}

$query = "SELECT * FROM experiencia WHERE Estado = 'A Decorrer'";
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
    echo "Nenhuma experiência 'A Decorrer' encontrada.";
}

mysqli_close($conn);
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
