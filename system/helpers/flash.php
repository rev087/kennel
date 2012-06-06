<?php
  class flash
  {
    static function set($slot, $message)
    {
			if(!session_id()) session_start();
      $app_id = Kennel::getSetting('application', 'id');
      
      $_SESSION["{$app_id}-flash"][$slot] = $message;
    }
    
    static function get($slot)
    {
			if(!session_id()) session_start();
      $app_id = Kennel::getSetting('application', 'id');
      
      if ( isset($_SESSION["{$app_id}-flash"][$slot]) ) {
        $message = $_SESSION["{$app_id}-flash"][$slot];
        unset($_SESSION["{$app_id}-flash"][$slot]);
        return $message;
      } else {
        return null;
      }
    }
    
    static function has($slot)
    {
			if(!session_id()) session_start();
      $app_id = Kennel::getSetting('application', 'id');
      
      if ( isset($_SESSION["{$app_id}-flash"][$slot]) && count($_SESSION["{$app_id}-flash"][$slot]) > 0 )
        return true;
      else
        return false;
    }
    
  }
?>