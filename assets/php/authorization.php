<?php
   function adminAccess(){
        if ($_SESSION['user_role'] != 'A' || $_SESSION['user_role'] != 'S') {
            header("Location: login.php");
            exit();
        }
   }

   function customerAccess(){
        if ($_SESSION['user_role'] != 'C') {
            header("Location: login.php");
            exit();
        }
   }

?>