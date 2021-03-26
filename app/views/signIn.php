<?php
require APPROOT . '/views/includes/head.php';
?>

<div>
<?php
require APPROOT . '/views/includes/nav.php';
?>

</div>
<div class="main">
<div class="regMenu">
    <form action="<?php echo URLROOT?>/users/addUser" method="post">
<input type="email" placeholder="Email address" name="email">
<input type="password" placeholder="Password" name="password">
<input type="password" placeholder="Password again" name="passwordC">
<input type="text" placeholder="UserName" name="username">

<input type="submit" value="Sign in now!">
</form>
</div>
</div>


