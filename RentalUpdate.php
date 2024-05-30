<?php
include 'functions2.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['rentalID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $rentalID = isset($_POST['rentalID']) ? $_POST['rentalID'] : NULL;
        $locDate = isset($_POST['locDate']) ? $_POST['locDate'] : '';
        $sDate = isset($_POST['sDate']) ? $_POST['sDate'] : '';
        $eDate = isset($_POST['eDate']) ? $_POST['eDate'] : '';
        $rentalType = isset($_POST['rentalType']) ? $_POST['rentalType'] : '';
        $immat = isset($_POST['immat']) ? $_POST['immat'] : '';
        $idClient = isset($_POST['idClient']) ? $_POST['idClient'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE rental SET rentalID = ?, locDate = ?, sDate = ?, eDate = ?, rentalType = ?, immat = ?, idClient = ? WHERE rentalID = ?');
        $stmt->execute([$rentalID, $locDate, $sDate, $eDate, $rentalType, $immat, $idClient, $_GET['rentalID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM rental WHERE rentalID = ?');
    $stmt->execute([$_GET['rentalID']]);
    $rental = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$rental) {
        exit('Rental doesn\'t exist with that rentalID!');
    }
} else {
    exit('No rentalID specified!');
}
?>
<?=template_header('Rental')?>

<div class="content update">
  <h2>Update rental #<?=$rental['rentalID']?></h2>
  <form action="CarUpdate.php?rentalID=<?=$rental['rentalID']?>" method="post">
    <label for="rentalID">rentalID</label>
    <input type="text" name="rentalID" placeholder="000016" value="<?=$rental['rentalID']?>" id="rentalID">

    <label for="locDate">locDate</label>
    <input type="text" name="locDate" placeholder="01/01/2001" value="<?=$rental['locDate']?>" id="locDate">

    <label for="sDate">sDate</label>
    <input type="text" name="sDate" placeholder="01/01/2001" value="<?=$rental['sDate']?>" id="sDate">

    <label for="eDate">eDate</label>
    <input type="text" name="eDate" placeholder="01/01/2001" value="<?=$rental['eDate']?>" id="eDate">

    <label for="rentalType">rentalType</label>
    <input type="text" name="rentalType" placeholder="WD OR VD" value="<?=$rental['rentalType']?>" id="rentalType">

    <label for="idClient">idClient</label>
    <input type="text" name="idClient" placeholder="00001" value="<?=$rental['idClient']?>" id="idClient">

    <label for="idClient">idClient</label>
    <input type="text" name="idClient" placeholder="9999" value="<?=$rental['idClient']?>" id="idClient">

    <input type="submit" value="Update">
  </form>
  <?php if ($msg): ?>
  <p><?=$msg?></p>
  <?php endif; ?>
</div>

<?=template_footer()?>