<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";      // Par défaut sur XAMPP
$password = "";          // Par défaut sur XAMPP
$dbname = "reservationdb";  // Le nom de ta base

// Crée la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupère les données du formulaire
$nom = $_POST['nom'];
$email = $_POST['email'];
$date = $_POST['date'];
$heure = $_POST['heure'];
$personnes = $_POST['personnes'];

// Prépare la requête SQL pour éviter les injections SQL
$stmt = $conn->prepare("INSERT INTO reservations (nom, email, date_reservation, heure_reservation, nombre_personnes) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $nom, $email, $date, $heure, $personnes);

// Exécute la requête et vérifie
if ($stmt->execute()) {
    echo "Réservation enregistrée avec succès !";
} else {
    echo "Erreur : " . $stmt->error;
}

// Ferme la connexion
$stmt->close();
$conn->close();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservationdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$sql = "SELECT * FROM reservations ORDER BY date_reservation, heure_reservation";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Liste des réservations</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nom</th><th>Email</th><th>Date</th><th>Heure</th><th>Nombre de personnes</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_reservation']) . "</td>";
        echo "<td>" . htmlspecialchars($row['heure_reservation']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre_personnes']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucune réservation trouvée.";
}

$conn->close();
?>
