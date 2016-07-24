<?php    
$url = "view/register.php";  
$message = "注册成功";
echo "<script type='text/javascript'>";  
echo "var tmp='$message' ;";
echo "alert(tmp);";
echo "window.location.href='$url';";  
echo "</script>";   
?> 