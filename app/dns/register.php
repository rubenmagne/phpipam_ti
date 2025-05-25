<?php
/**
 * DNS Module - Registration
 */

# verify that user is logged in
$User->check_user_session();

# register module in database
$query = "INSERT INTO `modules` (`name`, `version`, `description`, `menu_order`, `menu_icon`, `menu_name`, `menu_href`, `menu_visible`, `menu_parent`, `menu_position`, `menu_show`) 
          VALUES ('DNS', '1.0', 'DNS records management module', 50, 'fa-server', 'DNS', 'dns', 1, NULL, 'left', 1)
          ON DUPLICATE KEY UPDATE 
          `version` = '1.0',
          `description` = 'DNS records management module',
          `menu_order` = 50,
          `menu_icon` = 'fa-server',
          `menu_name` = 'DNS',
          `menu_href` = 'dns',
          `menu_visible` = 1,
          `menu_parent` = NULL,
          `menu_position` = 'left',
          `menu_show` = 1;";

# execute query
try {
    $Database->runQuery($query);
    $Result->show("success", _("DNS module registered successfully"));
} catch (Exception $e) {
    $Result->show("danger", _("Error registering DNS module: ").$e->getMessage());
} 