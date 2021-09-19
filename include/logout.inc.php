<?php
session_start();
session_destroy();
?>
    <script type = 'text/javascript'>
    alert('User successfully Logout!')
    window.location.href = '../login.php'
    </script>
<?php
exit();