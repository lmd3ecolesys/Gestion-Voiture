<?php
include 'functions2.php';
$pdo = pdo_connect_mysql();
$msg = '';
class AddNewRental {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function rentCar($rentalID, $locDate, $rentalType, $carId, $clientId, $startDate, $endDate) {
        // Vérifier si la voiture est EXISTE
        $query = "
            SELECT COUNT(*) as count
            FROM car
            WHERE immat = :carId
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':carId', $carId, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {
            return "La voiture n'existe pas.";
        }

        $query = "
            SELECT COUNT(*) as count
            FROM client
            WHERE idClient = :clientId
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':clientId', $clientId, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {
            return "Le client n'existe pas.";
        }

        // Vérifier si la voiture est déjà louée entre les dates spécifiées
        $query = "
            SELECT COUNT(*) as count
            FROM rental
            WHERE immat = :carId
            AND (
                (sDate <= :startDate AND eDate >= :startDate) OR
                (sDate <= :endDate AND eDate >= :endDate) OR
                (sDate >= :startDate AND eDate <= :endDate)
            )
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':carId', $carId, PDO::PARAM_INT);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            return "La voiture est déjà louée entre ces dates.";
        }
        else 
        {
        // Ajouter la nouvelle location
        $query = "
            INSERT INTO rental (rentalID, locDate, rentalType, immat, idClient, sDate, eDate)
            VALUES (:rentalID, :locDate, :rentalType, :carId, :clientId, :startDate, :endDate)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rentalID', $rentalID, PDO::PARAM_INT);
        $stmt->bindParam(':locDate', $locDate, PDO::PARAM_STR);
        $stmt->bindParam(':rentalType', $rentalType, PDO::PARAM_STR);
        $stmt->bindParam(':carId', $carId, PDO::PARAM_STR);
        $stmt->bindParam(':clientId', $clientId, PDO::PARAM_STR);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "Location ajoutée avec succès.";
        } else {
            return "Erreur lors de l'ajout de la location.";
        }
      }
    }
}



// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty, insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist, if not we can default them to blank
    $rentalID = isset($_POST['rentalID']) && !empty($_POST['rentalID']) && $_POST['rentalID'] != 'auto' ? $_POST['rentalID'] : NULL;
    $locDate = isset($_POST['locDate']) ? $_POST['locDate'] : '';
    $sDate = isset($_POST['sDate']) ? $_POST['sDate'] : '';
    $eDate = isset($_POST['eDate']) ? $_POST['eDate'] : '';
    $rentalType = isset($_POST['rentalType']) ? $_POST['rentalType'] : '';
    $immat = isset($_POST['immat']) ? $_POST['immat'] : '';
    $idClient = isset($_POST['idClient']) ? $_POST['idClient'] : '';
    
    // Exemple d'utilisation
  if (strlen($immat) != 6) {
      $msg = "L'immatriculation doit contenir exactement 6 caractères.";
  } elseif (strlen($idClient) != 6) {
      $msg = "L'ID client doit contenir exactement 6 caractères.";
    } elseif (strlen($rentalType) != 3) {
      $msg = "rentalType doit contenir exactement 3 caractères.";
  } elseif ($sDate >= $eDate) {
      $msg = "La date de début doit être avant la date de fin.";
  } else {
    try {
      $db = $pdo;
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
      $rental = new AddNewRental($db);
      $msg= $rental->rentCar($rentalID, $locDate, $rentalType, $immat, $idClient, $sDate, $eDate);
    } catch (PDOException $e) {
      $msg= "Connection failed: " . $e->getMessage();
    }

  }   
}
?>
<?=template_header('Rental')?>

<div class="content update">
  <h2> Insert New Rental</h2>
  <form action="RentalCreate.php" method="POST">
    <label for="rentalID">Rental ID</label>

    <input type="text" name="rentalID" placeholder="99" id="rentalID">

    <label for="locDate">locaction Date :</label>
    <input type="date" name="locDate"  id="locDate">

    <label for="sDate">Starting Date</label>
    <input type="date" name="sDate"  id="sDate">

    <label for="eDate">Ending Date : </label>
    <input type="date" name="eDate"  id="eDate">

    <label for="rentalType">Type of the rental </label>
    <input type="text" name="rentalType" placeholder="WD OR ND" id="rentalType">

    <label for="immat">immat of the car </label>
    <input type="text" name="immat" placeholder="000099" id="immat">

    <label for="idClient">Client ID</label>
    <input type="text" name="idClient" placeholder="000099" id="idClient">

    

    <input type="submit" value="Insert a new Rental">

  </form>

  <?php if ($msg): ?>
  <p><?=$msg?></p>
  <?php endif; ?>

</div>

<?=template_footer()?>