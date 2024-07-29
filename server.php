<?php
$conn = new mysqli("localhost", "root", "", "impiccato_db");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //Prendo una parola dal DB
    $sql = "SELECT parola FROM parole ORDER BY RAND()";
    $result = $conn->query($sql);

    $ritorno;

    if ($result->num_rows > 0)
        $ritorno = $result->fetch_assoc();
    else
        $ritorno = ["parola" => "nessuna parola"];

    echo json_encode($ritorno);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Aggiungo parola al DB
    if (isset($_POST['parola'])) {
        $parola = strtolower($_POST['parola']);
        $sql = "INSERT INTO parole (parola) VALUES ('$parola')";

        if ($conn->query($sql))
            echo "Nuova parola aggiunta con successo";
        else
            echo "Errore: " . $sql . "<br>" . $conn->error;
    }

    //Caricamento file.txt
    if (isset($_FILES['file'])) {
        $file = $_FILES['file']['tmp_name'];
        $fileContent = file_get_contents($file);

        $sql = "INSERT INTO parole (parola) VALUES ";
        $i = 1
        ;
        foreach (explode("\n", $fileContent) as $parola) {
            $parola = trim($parola);

            if ($parola != "")
                $sql .= "('" . $conn->real_escape_string($parola) . "'),";

            if ($i % 100 == 0) {
                $sql = substr($sql, 0, - 1);
                $conn->query($sql);
                $sql = "INSERT INTO parole (parola) VALUES ";
            }

            $i++;
        }

        if ($sql != "") {
            $sql = substr($sql, 0, - 1);
            $conn->query($sql);
        }

        echo "File caricato con successo";
    }
}

$conn->close();