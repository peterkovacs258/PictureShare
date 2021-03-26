<?php
require APPROOT . '/views/includes/head.php';
?>

<div>
<?php
require APPROOT . '/views/includes/loggedNav.php';
?>
</div>

<div class="main">
<div class="menuPictures">
<div><a href="<?php echo URLROOT;?>/pictures/allPicture"><span class='btn btn-light btn-showAllPictures'>All pictures</span></a>
<span class='btn btn-light btn-showHallOfFame'>Hall of fame</span>
</div>
<div class='cardholder'>
<?php echo $data['cards']; ?>
</div>
<div class='pageselector'><?php echo $data['pageselect']; ?></div>
</div>
</div>



