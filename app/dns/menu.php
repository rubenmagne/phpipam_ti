<?php
/**
 * DNS Module - Menu
 */

# verify that user is logged in
$User->check_user_session();

# print menu
print "<div class='menu'>";
print "<div class='menu-title'>"._('DNS Management')."</div>";
print "<div class='menu-items'>";

# menu items
$menu_items = array(
    array(
        "title" => _("DNS Records"),
        "icon" => "fa-server",
        "href" => create_link("dns"),
        "active" => ($_GET['page'] == "dns" && !isset($_GET['action'])) ? true : false
    ),
    array(
        "title" => _("Add Record"),
        "icon" => "fa-plus",
        "href" => create_link("dns", "add"),
        "active" => ($_GET['page'] == "dns" && $_GET['action'] == "add") ? true : false
    ),
    array(
        "title" => _("Search"),
        "icon" => "fa-search",
        "href" => create_link("dns", "search"),
        "active" => ($_GET['page'] == "dns" && $_GET['action'] == "search") ? true : false
    )
);

# print menu items
foreach ($menu_items as $item) {
    print "<a href='".$item['href']."' class='menu-item ".($item['active'] ? "active" : "")."'>";
    print "<i class='fa ".$item['icon']."'></i> ".$item['title'];
    print "</a>";
}

print "</div>";
print "</div>"; 