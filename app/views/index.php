<?php
if(isset($_SESSION['email']))
{
    header("location:".APPROOT."/main/menu");
}
require APPROOT . '/views/includes/head.php';
?>

<div>
<?php
require APPROOT . '/views/includes/nav.php';
?>

</div>
<div class="main">
<div class="log-in-menu">
    <form action="<?php echo URLROOT?>/users/login" method="post">
<input type="email" placeholder="Email address" name="email">
<input type="password" placeholder="Password" name="password">
<input type="submit" value="login">
</form>
<a href="<?php echo URLROOT ?>/main/signInPage"><span>Have no account yet? Sign in!</span></a>
</div>
</div>


