<?php

header('Access-Control-Allow-Origin: *');




// $host="localhost"; // Host name 
// $username="root"; // Mysql username 
// $password="root"; // Mysql password 
// $db_name="lovster"; // Database name 
// $tbl_name="scores"; // Table name

// // Connect to server and select database.
// mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
// mysql_select_db("$db_name")or die("cannot select DB");

// // Retrieve data from database 
// $sql="SELECT * FROM scores ORDER BY score DESC LIMIT 10";
// $result=mysql_query($sql);
// echo '$current_user->display_name;';
// // Start looping rows in mysql database.
// while($rows=mysql_fetch_array($result)){
// echo $rows['name'] . "|" . $rows['score'] . "|";
echo do_shortcode( '[mycred_my_balance]' );
// // close while loop 
// }

// // close MySQL connection 
// mysql_close();
// ?>