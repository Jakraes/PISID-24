<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Experience List</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <?php

/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'root';

/*** mysql password ***/
$password = '';

$database = 'pisid';

// Establishing the connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
}

// SQL query to retrieve data
$sql = "SELECT * FROM experiencia";

// Executing the query and storing the result
$result = mysqli_query($conn, $sql);

// Checking if the query was successful
if (mysqli_num_rows($result) > 0) {
    // Outputting the data
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row["ID"] 
            . " - Descricão: " . $row["Descricao"] 
            . " - ID_Utilizador: " . $row["ID_Utilizador"] 
            . " - Data_Hora: " . $row["Data_Hora"]
            . " - Numero_Ratos: " . $row["Numero_Ratos"]
            . " - Limite_Ratos_Sala: " . $row["Limite_Ratos_Sala"]
            . " - Segundos_Sem_Movimento: " . $row["Segundos_Sem_Movimento"]
            . " - Temperatura_Ideal: " . $row["Temperatura_Ideal"]
            . " - Variacao_Temperatura_Maxima: " . $row["Variacao_Temperatura_Maxima"]
            . " - Estado: " . $row["Estado"] . "<br>";  
    }
} else {
    echo "0 results";
}

// Close the connection
mysqli_close($conn);

?>
    <body>

    </body>
</html>