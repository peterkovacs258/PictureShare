<?php
if(!isset($_SESSION['email']))
{
    header("location:".APPROOT."/main/index");
}
require APPROOT . '/views/includes/head.php';
?>

<div>
<?php
require APPROOT . '/views/includes/loggedNav.php';
?>

</div>
<div class="main">
<div class="menuProfile">
    <div id='profile-info'>
    <div class='imgholder'><img src="<?php echo $data['imgsrc'] ?>"></div>
    <div><span><?php echo $data['email']  ?></span></div>
    <div><span>Number of uploads=</span> <span><?php echo$data['uploads'];?></span></div>
    <div><span>Likes received=</span> <span><?php echo$data['likesReceived'];?></span></div>
    <div><span>Dislikes received=</span> <span><?php echo$data['dislikesReceived'];?></span></div>
    <div><span>Likes given=</span> <span><?php echo$data['likesGiven'];?></span></div>
    <div><span>Dislikes given=</span> <span><?php echo$data['dislikesGiven'];?></span></div>
</div>

<div id="uploaded-pictures"><br>

    <?php echo $data['mypictures'] ?>
</div>
</div>
</div>


