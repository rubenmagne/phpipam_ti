<?php
/**
 * DNS Module - Main page
 * 
 * This file handles the main DNS records listing and management interface.
 * It provides functionality to view, add, edit, and delete DNS records.
 */

# verify that user is logged in
$User->check_user_session();

# initialize DNS class
$DNS = new DNS ($Database);

# get all DNS records
$dns_records = $DNS->get_all_records();

# include header
include("app/header.php");

# print title
print "<h4>"._('DNS Records')."</h4>";
print "<hr>";

# print content
if ($dns_records === false) {
    print "<div class='alert alert-info'>"._('No DNS records found')."</div>";
} else {
    # print table
    print "<table class='table table-striped table-top'>";
    # headers
    print "<tr>";
    print "<th>"._('Hostname')."</th>";
    print "<th>"._('IP Address')."</th>";
    print "<th>"._('Record Type')."</th>";
    print "<th>"._('TTL')."</th>";
    print "<th>"._('Description')."</th>";
    print "<th>"._('Actions')."</th>";
    print "</tr>";
    
    # records
    foreach ($dns_records as $record) {
        print "<tr>";
        print "<td>".$record->hostname."</td>";
        print "<td>".$record->ip_addr."</td>";
        print "<td>".$record->record_type."</td>";
        print "<td>".$record->ttl."</td>";
        print "<td>".$record->description."</td>";
        print "<td>";
        print "<div class='btn-group'>";
        print "<a href='".create_link("dns","edit",$record->id)."' class='btn btn-xs btn-default'><i class='fa fa-pencil'></i></a>";
        print "<a href='".create_link("dns","delete",$record->id)."' class='btn btn-xs btn-danger'><i class='fa fa-times'></i></a>";
        print "</div>";
        print "</td>";
        print "</tr>";
    }
    print "</table>";
}

# add new record button
print "<div class='btn-group'>";
print "<a href='".create_link("dns","add")."' class='btn btn-sm btn-default'><i class='fa fa-plus'></i> "._('Add new record')."</a>";
print "</div>";

# include footer
include("app/footer.php"); 