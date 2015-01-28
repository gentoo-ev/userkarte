<?php
isset( $_POST["admin"] ) ? $step = $_POST["admin"] : $step = $_GET["admin"];
if(( isset( $_POST["N_grad"] )) && ( isset( $_POST["N_gradminuten"] )) &&
   ( isset( $_POST["O_grad"] )) && ( isset( $_POST["O_gradminuten"] ))) {
  $values = array( "N_grad"=>$_POST["N_grad"],
		   "N_gradminuten"=>$_POST["N_gradminuten"],
		   "O_grad"=>$_POST["O_grad"],
		   "O_gradminuten"=>$_POST["O_gradminuten"] );
}

if(( isset( $_GET["N_grad"] )) && ( isset( $_GET["N_gradminuten"] )) &&
   ( isset( $_GET["O_grad"] )) && ( isset( $_GET["O_gradminuten"] ))) {
  $values = array( "N_grad"=>$_GET["N_grad"],
		   "N_gradminuten"=>$_GET["N_gradminuten"],
		   "O_grad"=>$_GET["O_grad"],
		   "O_gradminuten"=>$_GET["O_gradminuten"] );
}

if( isset( $_POST["name"] )) {
  $name = $_POST["name"];
  #$name = strip_tags( $name );
  #$name = strip_tags($name);
  #$name = str_replace( "\"", "/", $name );
  $name = str_replace("\"","&quot;",$name);
  $name = str_replace("\'","&quot;",$name);
  $name = str_replace("\\","",$name);

}

if( isset( $_POST["email"] )) {
  $email = str_replace( "\"","",$_POST["email"] );
  $email = str_replace( "\'","",$email);
  $email = str_replace("\\","",$email);
  
}
?>
