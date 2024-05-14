<?php
session_start();

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

                        // Check if the experience is ongoing and add buttons accordingly
                        if ($row['Estado'] == 'A Decorrer') {
                            echo "<button onclick='editarExperiencia(" . $row['ID'] . ")'>Editar</button>";
                            echo "<button onclick='confirmarEIniciarExperiencia(" . $row['ID'] . ")'>Iniciar Experiência</button>";
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
    </div>

    <script>
        function editarExperiencia(id) {
            window.location.href = 'editform.php?id=' + id;
        }

        function confirmarEIniciarExperiencia(id) {
            if (confirm('Deseja iniciar esta experiência?')) {
                window.location.href = 'exand.php?id=' + id;
            } else {
                window.location.href = 'painelex.php';
            }
        }
    </script>
</body>
</html>
