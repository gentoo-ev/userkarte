<?php
isset( $HTTP_POST_VARS["admin"] ) ? $step = $HTTP_POST_VARS["admin"] : $step = $HTTP_GET_VARS["admin"];
if(( isset( $HTTP_POST_VARS["N_grad"] )) && ( isset( $HTTP_POST_VARS["N_gradminuten"] )) &&
   ( isset( $HTTP_POST_VARS["O_grad"] )) && ( isset( $HTTP_POST_VARS["O_gradminuten"] ))) {
  $values = array( "N_grad"=>$HTTP_POST_VARS["N_grad"],
		   "N_gradminuten"=>$HTTP_POST_VARS["N_gradminuten"],
		   "O_grad"=>$HTTP_POST_VARS["O_grad"],
		   "O_gradminuten"=>$HTTP_POST_VARS["O_gradminuten"] );
}

if(( isset( $HTTP_GET_VARS["N_grad"] )) && ( isset( $HTTP_GET_VARS["N_gradminuten"] )) &&
   ( isset( $HTTP_GET_VARS["O_grad"] )) && ( isset( $HTTP_GET_VARS["O_gradminuten"] ))) {
  $values = array( "N_grad"=>$HTTP_GET_VARS["N_grad"],
		   "N_gradminuten"=>$HTTP_GET_VARS["N_gradminuten"],
		   "O_grad"=>$HTTP_GET_VARS["O_grad"],
		   "O_gradminuten"=>$HTTP_GET_VARS["O_gradminuten"] );
}

if( isset( $HTTP_POST_VARS["name"] )) {
  $name = $HTTP_POST_VARS["name"];
  #$name = strip_tags( $name );
  #$name = strip_tags($name);
  #$name = str_replace( "\"", "/", $name );
  $name = str_replace("\"","&quot;",$name);
  $name = str_replace("\'","&quot;",$name);
  $name = str_replace("\\","",$name);

}

if( isset( $HTTP_POST_VARS["email"] )) {
  $email = str_replace( "\"","",$HTTP_POST_VARS["email"] );
  $email = str_replace( "\'","",$email);
  $email = str_replace("\\","",$email);
  
}
?>
