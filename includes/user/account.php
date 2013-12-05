<?php
  
  switch(get("account")) {
    case "settings":
      $require = "account/settings.php";
      break;
    case "messages":
      $require = "account/messages.php";
      break;
    case "favorites":
      $require = "account/favorites.php";
      break;
    case "profile":
      $require = "view.php";
      break;
    default:
      break;
	}
  
  if (!empty($require) && user()->loggedin)
    require_once($require);
  else throw new Exception("Invalid account get");
  ?>