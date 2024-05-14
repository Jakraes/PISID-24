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

        $data_hora = date('Y-m-d H:i:s');
        $estado = 'Aguarda Início';

        $sql = "INSERT INTO experiencia (Descricao, ID_Utilizador, Data_Hora, Numero_Ratos, Limite_Ratos_Sala, Segundos_Sem_Movimento, Temperatura_Ideal, Variacao_Temperatura_Maxima, Estado) 
                VALUES ('$descricao', '$user_id', '$data_hora', '$numero_ratos', '$limite_ratos_sala', '$segundos_sem_movimento', '$temperatura_ideal', '$variacao_temperatura_maxima', '$estado')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Experiência gravada com sucesso!');</script>";
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
    <title>Registrar Experiência - TECH MICE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="general-container">
        <div class="logo-container">
            <img src="logo.jpeg" alt="Logotipo TECH MICE" class="imagem-circular">
        </div>
        <h1>Registar Experiência</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirm('Tem certeza que deseja gravar a experiência?')" class="form-container">
            <input type="text" name="descricao" placeholder="Descrição da experiência" required>
            <input type="number" name="numero_ratos" placeholder="Número de Ratos" required>
            <input type="number" name="limite_ratos_sala" placeholder="Limite de Ratos por Sala" required>
            <input type="number" name="segundos_sem_movimento" placeholder="Segundos Sem Movimento" required>
            <input type="text" name="temperatura_ideal" placeholder="Temperatura Ideal" required>
            <input type="text" name="variacao_temperatura_maxima" placeholder="Variação de Temperatura Máxima" required>
            <div class="action-buttons">
                <button type="submit">Gravar Experiência</button>
                <button type="button" onclick="window.location.href='addodor.html'">Adicionar Odor</button>
                <button type="button" onclick="window.location.href='addsub.html'">Adicionar Substância</button>
            </div>
        </form>
    </div>
</body>
</html>
