<?php
/**
 * DNS Module - Delete record
 * 
 * This file handles the deletion of DNS records.
 */

# verify that user is logged in
$User->check_user_session();

# initialize DNS class
$DNS = new DNS ($Database);

# get record ID
$record_id = $_GET['id'];

# verify record exists
$record = $DNS->get_record($record_id);
if ($record === false) {
    print "<div class='alert alert-danger'>"._('Record not found')."</div>";
    include("app/footer.php");
    exit;
}

# include header
include("app/header.php");

# print title
print "<h4>"._('Delete DNS record')."</h4>";
print "<hr>";

# process deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # verify confirmation
    if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
        # delete record
        if ($DNS->delete_record($record_id)) {
            print "<div class='alert alert-success'>"._('Record deleted successfully')."</div>";
            # redirect to index
            print "<script>window.location.href = '".create_link("dns")."'</script>";
        } else {
            print "<div class='alert alert-danger'>"._('Failed to delete record')."</div>";
        }
    } else {
        print "<div class='alert alert-danger'>"._('Please confirm deletion')."</div>";
    }
}

# print confirmation form
print "<form method='post' class='form-horizontal'>";
print "<div class='alert alert-warning'>";
print _('Are you sure you want to delete this DNS record?')."<br>";
print "<strong>"._('Hostname').":</strong> ".$record->hostname."<br>";
print "<strong>"._('IP Address').":</strong> ".$record->ip_addr."<br>";
print "<strong>"._('Record Type').":</strong> ".$record->record_type;
print "</div>";

print "<div class='form-group'>";
print "<div class='col-sm-offset-2 col-sm-10'>";
print "<input type='hidden' name='confirm' value='yes'>";
print "<button type='submit' class='btn btn-danger'>"._('Delete record')."</button>";
print "<a href='".create_link("dns")."' class='btn btn-default'>"._('Cancel')."</a>";
print "</div>";
print "</div>";

print "</form>";

# include footer
include("app/footer.php"); 