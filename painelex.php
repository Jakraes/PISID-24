<?php
session_start();

if(isset($_POST['logout'])) {
    setcookie('username', '', time() - 3600); // Remove username cookie
    header("Location: login.php");
    exit();
}

if(!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit();
}

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pisid';

// Fetch the experiences from the database
$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
    die("Conexão falhada: " . mysqli_connect_error());
}

$query = "SELECT * FROM experiencia";
$result = mysqli_query($conn, $query);

// Check if there are ongoing experiences
$noRunningExperiences = true;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['Estado'] === 'A Decorrer') {
            $noRunningExperiences = false;
            break;
        }
    }
}

mysqli_data_seek($result, 0); // Reset pointer to the beginning

// Handle interromper action if ID is provided
if (isset($_GET['action']) && $_GET['action'] === 'interromper' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE experiencia SET Estado = 'Interrompida' WHERE ID = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: painelex.php");
        exit(); // Terminate script after redirect
    } else {
        echo "Erro ao interromper a experiência: " . mysqli_error($conn);
    }
}

// Handle iniciar action if ID is provided
if (isset($_GET['action']) && $_GET['action'] === 'iniciar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE experiencia SET Estado = 'A Decorrer' WHERE ID = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: painelex.php");
        exit(); // Terminate script after redirect
    } else {
        echo "Erro ao iniciar a experiência: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Experiências - TECH MICE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="general-container">
        <div class="logo-container">
            <img src="logo.jpeg" alt="Logotipo TECH MICE" class="imagem-circular">
        </div>
        <h1>Painel de Experiências</h1>
        <form method="post" style="position: absolute; top: 10px; left: 10px;">
            <button type="submit" name="logout">Logout</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Ratos</th>
                    <th>Limite Ratos por Sala</th>
                    <th>Segundos Sem Movimento</th>
                    <th>Temperatura Ideal</th>
                    <th>Variação de Temperatura</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>" . $row['Numero_Ratos'] . "</td>";
                        echo "<td>" . $row['Limite_Ratos_Sala'] . "</td>";
                        echo "<td>" . $row['Segundos_Sem_Movimento'] . "</td>";
                        echo "<td>" . $row['Temperatura_Ideal'] . "</td>";
                        echo "<td>" . $row['Variacao_Temperatura_Maxima'] . "</td>";
                        echo "<td style='display: flex; gap: 1em;'>";

                        // Determine which buttons to display based on the Estado
                        switch ($row['Estado']) {
                            case 'Aguarda Início':
                                // Edit button for Aguarda Início
                                echo "<form method='get' action='editform.php'>";
                                echo "<input type='hidden' name='id' value='" . $row['ID'] . "'>";
                                echo "<button type='submit'>Editar</button>";
                                echo "</form>";
                                
                                // Iniciar Experiência button
                                if ($noRunningExperiences) {
                                    echo "<button onclick='confirmarEIniciarExperiencia(" . $row['ID'] . ")' style=\"background-color: #49DF00\">Iniciar Experiência</button>";
                                } else {
                                    echo "<button disabled style=\"background-color: #FFD205\">A aguardar...</button>";
                                }
                                break;
                            case 'A Decorrer':
                                echo "<button onclick='interromperExperiencia(" . $row['ID'] . ")' style=\"background-color: #DF0000\">Interromper</button>";
                                // Detalhes button for experiences with Estado = A Decorrer
                                echo "<button onclick='viewDetails(" . $row['ID'] . ")'>Detalhes</button>";
                                break;
                            case 'Interrompida':
                            case 'Finalizada':
                                // Repetir button for Interrompida and Finalizada
                                echo "<form method='get' action='repeat.php'>";
                                echo "<input type='hidden' name='id' value='" . $row['ID'] . "'>";
                                echo "<button type='submit'>Repetir</button>";
                                echo "</form>";
                                break;
                            default:
                                // Do nothing or add specific actions for other states
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhuma experiência encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <!-- Button to redirect to formex.php -->
        <button onclick="window.location.href = 'formex.php';">Criar Experiência</button>
    </div>

    <script>
        function confirmarEIniciarExperiencia(id) {
            if (confirm('Deseja iniciar esta experiência?')) {
                window.location.href = '<?php echo $_SERVER["PHP_SELF"] . "?action=iniciar&id="; ?>' + id;
            } else {
                window.location.href = 'painelex.php';
            }
        }

        function interromperExperiencia(id) {
            if (confirm('Deseja interromper esta experiência?')) {
                window.location.href = '<?php echo $_SERVER["PHP_SELF"] . "?action=interromper&id="; ?>' + id;
            }
        }

        function viewDetails(id) {
            window.location.href = 'exand.php?id=' + id;
        }
    </script>
</body>
</html>
