<?php
require APPROOT . '/views/includes/head.php';
?>

<div>
<?php
require APPROOT . '/views/includes/loggedNav.php';
?>
</div>

<div class="main">
<div class="menuUpload">
    <form action="<?php echo URLROOT?>/pictures/uploadPic" method='post' enctype="multipart/form-data">
    <div id="preview"></div>
    <input type="text" placeholder="Title" name='title'>
<input type="file" name="filepic">
<input type="submit" value="Upload">
</form>


</div>
</div>



