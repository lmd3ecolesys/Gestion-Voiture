<?php
include 'functions2.php';
$pdo = pdo_connect_mysql();
$msg = '';

// this code is composed of two parts , selectinf the data and based on the data table an update can be made 

if (isset($_GET['immat'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $immat = isset($_POST['immat']) ? $_POST['immat'] : NULL;
        $brand = isset($_POST['brand']) ? $_POST['brand'] : '';
        $model = isset($_POST['model']) ? $_POST['model'] : '';
        $priceByDay = isset($_POST['priceByDay']) ? $_POST['priceByDay'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE car SET immat = ?, brand = ?, model = ?, priceByDay = ? WHERE immat = ?');
        $stmt->execute([$immat, $brand, $model, $priceByDay, $_GET['immat']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM car WHERE immat = ?');
    $stmt->execute([$_GET['immat']]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$car) {
        exit('car doesn\'t exist with that immat!');
    }
} else {
    exit('No immat specified!');
}
?>
<?=template_header('Car')?>

<div class="content update">
  <h2>Update car #<?=$car['immat']?></h2>

  <form action="CarUpdate.php?immat=<?=$car['immat']?>" method="post">

    <label for="immat">immat</label>
    <input type="text" name="immat" placeholder="000016" value="<?=$car['immat']?>" id="immat">

    <label for="brand">brand</label>
    <input type="text" name="brand" placeholder="MERCEDES" value="<?=$car['brand']?>" id="brand">

    <label for="model">model</label>
    <input type="text" name="model" placeholder="E213" value="<?=$car['model']?>" id="model">
    
    <label for="priceByDay">priceByDay</label>
    <input type="text" name="priceByDay" placeholder="9999" value="<?=$car['priceByDay']?>" id="priceByDay">
    
    <input type="submit" value="Update">
  </form>


  <?php if ($msg): ?>
  <p><?=$msg?></p>
  <?php endif; ?>

</div>

<?=template_footer()?>