<?php
function dbconnect() {
        $link = @mysql_pconnect(DB_HOST, DB_USER, DB_PW)
                or die("Datenbankserver spricht nicht mit uns.\n");
        @mysql_select_db( DATABASE )
	  or createDatabase();
}

function createDatabase() {
  mysql_query( "CREATE DATABASE ".DATABASE );
  echo mysql_error();
  mysql_query( "USE ".DATABASE );
  echo mysql_error();
  mysql_query( "CREATE TABLE ".TABLE."( 
                  user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                  user_name VARCHAR(50) NOT NULL UNIQUE, 
                  user_n_grad INT NOT NULL,
                  user_n_gradminuten INT NOT NULL,
                  user_o_grad INT NOT NULL,
                  user_o_gradminuten INT NOT NULL)" );
  echo mysql_error();
}

function pointsMatch($o,$p) {
    $north_divergence = abs(( $o->user_n_grad + $o->user_n_gradminuten/60 ) - ( $p->user_n_grad + $p->user_n_gradminuten/60 ));
    $east_divergence = abs(( $o->user_o_grad + $o->user_o_gradminuten/60 ) - ( $p->user_o_grad + $p->user_o_gradminuten/60 ));
    if( $north_divergence > NORTH_DIVERGENCE || $east_divergence > EAST_DIVERGENCE ) 
      return FALSE;
    else
      return TRUE;
}

function calcPixel( $n_grad,$n_gradminuten,$o_grad,$o_gradminuten ) {
  // convert RAD to DEC
  $dec = array( "north"=>( $n_grad + $n_gradminuten/60 ),
		"east"=>( $o_grad + $o_gradminuten/60 ));

  // calculation of the coordinates (x and y)
  $coordinates = array( "pointX"=>(int)( ( $dec["east"] - UPPER_LEFT_X ) * MULTIPLIER_X ),
			"pointY"=>(int)( ( $dec["north"] - UPPER_LEFT_Y ) * MULTIPLIER_Y ));
  return $coordinates;
}
?>
