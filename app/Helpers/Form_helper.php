<?php
  function formValidationError($validation){
    $error_text = '';
    foreach($validation as $rowValidation){
      $error_text .= $rowValidation."<br>";
    }
    return $error_text;
  }