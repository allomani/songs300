<?
 unset($letters_groups,$letters_groups_names);
 //-------- letters groups ----------
 $letters_groups[0] = array('ا','أ','إ','آ');
    $letters_groups[1] = array('ب','ت','ث');
    $letters_groups[2] = array('ج','ح','خ');
   $letters_groups[3] = array('د','ذ','ر','ز');
   $letters_groups[4] = array('س','ش');
   $letters_groups[5] = array('ص','ض','ط','ظ');
   $letters_groups[6] = array('ع','غ');
   $letters_groups[7] = array('ف','ق');
   $letters_groups[8] = array('ك','ل');
   $letters_groups[9] = array('م','ن');
   $letters_groups[10] = array('ه');
   $letters_groups[11] = array('و','ي');
   $letters_groups[12] = array('A','B','C');
   $letters_groups[13] = array('D','E','F');
   $letters_groups[14] = array('G','H','I');
   $letters_groups[15] = array('J','K','L');
   $letters_groups[16] = array('M','N');
   $letters_groups[17] = array('O','P','Q');
   $letters_groups[18] = array('R','S','T');
   $letters_groups[19] = array('U','V','W');
   $letters_groups[20] = array('X','Y','Z');
   $letters_groups[21] = array('0','1','2','3','4','5','6','7','8','9');
 //-------- letter groups names ------------
 $letters_groups_names[21] = "0-9";
 //--------- fix unnamed groups ------------
  for($i=0;$i<count($letters_groups);$i++){
  if(!isset($letters_groups_names[$i])){
      $letters_groups_names[$i] = implode(" ",$letters_groups[$i]);
  }
  }
  
 //--------------------------------------------
 ?>