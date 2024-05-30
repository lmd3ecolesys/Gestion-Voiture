<?php
include 'functions2.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['immat'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM car WHERE immat = ?;');
    $stmt->execute([$_GET['immat']]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$car) {
        exit('car doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM car WHERE immat = ?;');
            $stmt->execute([$_GET['immat']]);
            $msg = 'You have deleted the car!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: CarRead.php');
            exit;
        }
    }
} else {
    exit('No immat specified!');
}
?>
<?=template_header('Car')?>

<div class="content delete">
  <h2>Delete car #<?=$car['immat']?></h2>
  <?php if ($msg): ?>
  <p><?=$msg?></p>
  <?php else: ?>
  <p>Are you sure you want to delete car #<?=$car['immat']?>?</p>
  <div class="yesno">
    <a href="CarDelete.php?immat=<?=$car['immat']?>&confirm=yes">Yes</a>
    <a href="CarDelete.php?immat=<?=$car['immat']?>&confirm=no">No</a>
  </div>
  <?php endif; ?>
</div>

<?=template_footer()?>