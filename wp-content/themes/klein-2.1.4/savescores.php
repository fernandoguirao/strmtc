
<?php
echo 'iiiddd'.get_current_user_id();

if(isset($_GET['score'])){

     $name =  $current_user->display_name;
     $score = strip_tags(mysql_real_escape_string($_GET['score']));
     
     $user_id = get_current_user_id();

     if ( !mycred_exclude_user( $user_id ) ) {
          // Add points and save the current year as ref_id
          mycred_add( 'nombrejuego', $user_id, $score, 'nombrejuegomas', date( 'y' ) );
     }
     
}else{
     echo 'Your name or score wasnt passed in the request. Make sure you add ?name=NAME_HERE&score=1337 to the tags.';
}

// mysql_close($dblink);//Close off the MySQL connection to save resources.
?>