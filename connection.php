<?php 

include('config.php');

return mysqli_connect(DB_HOST.":".DB_PORT, DB_USER, DB_PASSWORD, DB_NAME);
