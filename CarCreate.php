



<?php
// functions php to make connection and the footer and the header of the html page 
include 'functions2.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty and assign the values from post method to php variables
if (!empty($_POST)) {
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $immat = isset($_POST['immat']) && !empty($_POST['immat']) && $_POST['immat'] != 'auto' ? $_POST['immat'] : NULL;
    // Check if POST variable "immat" exists, if not default the value to blank for all variables
    $brand = isset($_POST['brand']) ? $_POST['brand'] : '';
    $model = isset($_POST['model']) ? $_POST['model'] : '';
    $priceByDay = isset($_POST['priceByDay']) ? $_POST['priceByDay'] : '';
    
    
    // Insert new record into the contacts table using prepared statment (positioned statement not named)
    $stmt = $pdo->prepare('INSERT INTO car VALUES (?, ?, ?, ?);');
    $stmt->execute([$immat, $brand, $model, $priceByDay]);
   
   
    // Output message
    $msg = 'Created Successfully!';
}
?>
<?=template_header('Car')?>

<div class="content update">
  <h2> Insert New Car</h2>
  <!-- Form for the client user to post the required field  -->
  <!-- name attribute is the one sending varible to post method  -->
  <form action="CarCreate.php" method="post">

    <label for="immat">immat of the car : </label>
    <input type="text" name="immat" placeholder="000099" id="immat">

    <label for="brand">Brand :</label>
    <input type="text" name="brand" placeholder="For example : MERCEDES or AUDI " id="brand">

    <label for="model">Model : </label>
    <input type="text" name="model" placeholder="For example : X1 or Q3 " id="model">

    <label for="priceByDay">Price By Day : </label>
    <input type="text" name="priceByDay" placeholder="00000" id="priceByDay">

    <input type="submit" value="Add new Car">
  </form>

  <!-- Print successful messgae when the query is executed -->
  <?php if ($msg): ?>
  <p><?=$msg?></p>
  <?php endif; ?>

</div>

<?=template_footer()?>