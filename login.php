<?php
if (!isset($_POST['login'])) {
?>
<script>
    location.replace("reglog.php")
</script>

<?php
}
include_once('db.php');

if (isset($_SESSION['uname'])) {
?>
<script>
    alert('YOU ARE ALREADY LOGGED IN');
    location.replace("index.php")
</script>

<?php
} else {

    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($db, $_POST["email"]);
        $pass = mysqli_real_escape_string($db, $_POST["pass"]);

        $email_search = "SELECT * FROM register where email='$email' and verified=1";
        $query = mysqli_query($db, $email_search);
        $emailcount = mysqli_num_rows($query);
        if ($emailcount === 1) {
            $email_pass = mysqli_fetch_assoc($query);
            $db_pass = $email_pass['pass'];
            $db_mail = $email_pass['email'];
            $pass_decode = password_verify($pass, $db_pass);

            if ($pass_decode) {
                $admincheck = "SELECT * FROM register where email='$email' and admin='active'";
                $adquery = mysqli_query($db, $admincheck);
                $run = mysqli_num_rows($adquery);
                if ($run) {
                    $admindata = mysqli_fetch_assoc($adquery);
                    $_SESSION['adname'] = true;
                    $_SESSION['uname'] = $admindata['name'];
                    $_SESSION['umail'] = $admindata['email'];
                    $_SESSION['login'] = "YOU ARE LOGGED IN AS ADMIN";
                } else {
                    $_SESSION['adname'] = false;
                    $_SESSION['uname'] = $email_pass['name'];
                    $_SESSION['umail'] = $db_mail;
                    $_SESSION['login'] = "YOU ARE LOGGED IN AS USER";
                }

?>
<script>
    //  alert('YOU ARE NOW LOGGED IN');
    location.replace("index.php")
</script>
<?php

            } else {

                $_SESSION['logmsg'] = "INCORRECT PASSWORD";
?>
<script>
    //  alert('incorrect Password or mail');
    location.replace("login.php")
</script>
<?php
            }
        } else {

            $_SESSION['logmsg'] = "ENTER VALID EMAIL";
?>
<script>
    //  alert('no');
    location.replace("login.php")
</script>
<?php
        }
    }
}

?>