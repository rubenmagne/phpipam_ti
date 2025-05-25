<?php
/**
 * DNS Module - Edit record
 * 
 * This file handles the form for editing existing DNS records.
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
print "<h4>"._('Edit DNS record')."</h4>";
print "<hr>";

# process form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # validate input
    $errors = array();
    
    # required fields
    if (empty($_POST['hostname'])) $errors[] = _('Hostname is required');
    if (empty($_POST['ip_addr'])) $errors[] = _('IP Address is required');
    if (empty($_POST['record_type'])) $errors[] = _('Record type is required');
    
    # validate IP address
    if (!filter_var($_POST['ip_addr'], FILTER_VALIDATE_IP)) {
        $errors[] = _('Invalid IP address format');
    }
    
    # if no errors, update record
    if (empty($errors)) {
        # prepare values
        $values = array(
            "hostname" => $_POST['hostname'],
            "ip_addr" => $_POST['ip_addr'],
            "record_type" => $_POST['record_type'],
            "ttl" => $_POST['ttl'],
            "description" => $_POST['description']
        );
        
        # update record
        if ($DNS->update_record($record_id, $values)) {
            print "<div class='alert alert-success'>"._('Record updated successfully')."</div>";
            # refresh record data
            $record = $DNS->get_record($record_id);
        } else {
            print "<div class='alert alert-danger'>"._('Failed to update record')."</div>";
        }
    } else {
        print "<div class='alert alert-danger'>";
        print "<ul>";
        foreach ($errors as $error) {
            print "<li>$error</li>";
        }
        print "</ul>";
        print "</div>";
    }
}

# print form
print "<form method='post' class='form-horizontal'>";
print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('Hostname')."</label>";
print "<div class='col-sm-10'>";
print "<input type='text' class='form-control' name='hostname' value='".$record->hostname."' required>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('IP Address')."</label>";
print "<div class='col-sm-10'>";
print "<input type='text' class='form-control' name='ip_addr' value='".$record->ip_addr."' required>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('Record Type')."</label>";
print "<div class='col-sm-10'>";
print "<select name='record_type' class='form-control' required>";
$record_types = array('A', 'AAAA', 'PTR', 'CNAME', 'MX', 'TXT');
foreach ($record_types as $type) {
    $selected = ($type == $record->record_type) ? "selected" : "";
    print "<option value='$type' $selected>$type</option>";
}
print "</select>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('TTL')."</label>";
print "<div class='col-sm-10'>";
print "<input type='number' class='form-control' name='ttl' value='".$record->ttl."'>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('Description')."</label>";
print "<div class='col-sm-10'>";
print "<input type='text' class='form-control' name='description' value='".$record->description."'>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<div class='col-sm-offset-2 col-sm-10'>";
print "<button type='submit' class='btn btn-default'>"._('Update record')."</button>";
print "</div>";
print "</div>";

print "</form>";

# include footer
include("app/footer.php"); 