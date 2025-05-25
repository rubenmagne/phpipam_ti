<?php
/**
 * DNS Module - Initialization
 */

# verify that user is logged in
$User->check_user_session();

# include required files
require_once('app/dns/config.php');
require_once('app/dns/menu.php');
require_once('app/dns/register.php');

# check if module is enabled
if ($Tools->is_module_enabled("DNS")) {
    # include module files
    if (isset($_GET['page']) && $_GET['page'] == "dns") {
        # set action
        $action = isset($_GET['action']) ? $_GET['action'] : null;
        
        # include appropriate file
        switch ($action) {
            case 'add':
                include('app/dns/add.php');
                break;
            case 'edit':
                include('app/dns/edit.php');
                break;
            case 'delete':
                include('app/dns/delete.php');
                break;
            case 'search':
                include('app/dns/search.php');
                break;
            default:
                include('app/dns/index.php');
                break;
        }
    }
} 