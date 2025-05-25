<?php
/**
 * DNS Module - Search
 */

# verify that user is logged in
$User->check_user_session();

# initialize DNS class
$DNS = new DNS ($Database);

# include header
include("app/header.php");

# print title
print "<h4>"._('Search DNS Records')."</h4>";
print "<hr>";

# process search
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # get search term
    $search_term = $_POST['search_term'];
    
    # search records
    $records = $DNS->search_records($search_term);
    
    # print results
    if ($records === false) {
        print "<div class='alert alert-info'>"._('No records found')."</div>";
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
        foreach ($records as $record) {
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
}

# print search form
print "<form method='post' class='form-horizontal'>";
print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('Search Term')."</label>";
print "<div class='col-sm-10'>";
print "<input type='text' class='form-control' name='search_term' required>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<div class='col-sm-offset-2 col-sm-10'>";
print "<button type='submit' class='btn btn-default'>"._('Search')."</button>";
print "</div>";
print "</div>";

print "</form>";

# include footer
include("app/footer.php"); 