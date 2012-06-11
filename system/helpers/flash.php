<?php
  /**
   * The `flash` helper allows setting and getting "flash" messages that
   * are displayed only once to the user
   */
  class flash
  {
    /**
     * Sets a flash message in a given slot
     * 
     * @param string $slot the slot to set the message to; used to retrieve
     * the message later
     * @param string $message the message to display
     */
    static function set($slot, $message)
    {
			if(!session_id()) session_start();
      $app_id = Kennel::getSetting('application', 'id');
      
      $_SESSION["{$app_id}-flash"][$slot] = $message;
    }
    
    /**
     * Gets a flash message from a given slot
     * 
     * @param string $slot the slot to retrieve the message from
     */
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
    
    /**
     * Checks whether there are a message set to a given slot
     * 
     * @param string $slot the slot to check
     */
    static function has($slot)
    {
			if(!session_id()) session_start();
      $app_id = Kennel::getSetting('application', 'id');
      
      if ( isset($_SESSION["{$app_id}-flash"][$slot]) && count($_SESSION["{$app_id}-flash"][$slot]) > 0 )
        return true;
      else
        return false;
    }
    
    /**
     * Renders a message from a given slot with boilerplate HTML
     * 
     * <code>
     *  <?php
     *    flash::set('login-error', 'Invalid username or password');
     *    echo flash::render('login-error', 'error-message');
     *    // prints:
     *    // <div class="error-message">Invalid username or password</div>
     *  ?>
     * </code>
     * 
     * @param string $slot the slot to retrieve the message from
     * @param string $class optional CSS class to be used
     * 
     * @return XMLElement
     */
    static function render($slot, $class="")
    {
      if ( self::has($slot) ) {
        $div = XML::element('div', null, null, self::get($slot));
        if ( $class ) $div->set('class', $class);
        return $div;
      }
      return null;
    }
  }
?>