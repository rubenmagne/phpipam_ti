<?php
/**
 * DNS Module - Add new record
 * 
 * This file handles the form for adding new DNS records.
 */

# verify that user is logged in
$User->check_user_session();

# initialize DNS class
$DNS = new DNS ($Database);

# include header
include("app/header.php");

# print title
print "<h4>"._('Add new DNS record')."</h4>";
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
    
    # if no errors, add record
    if (empty($errors)) {
        # prepare values
        $values = array(
            "hostname" => $_POST['hostname'],
            "ip_addr" => $_POST['ip_addr'],
            "record_type" => $_POST['record_type'],
            "ttl" => $_POST['ttl'],
            "description" => $_POST['description']
        );
        
        # add record
        if ($DNS->add_record($values)) {
            print "<div class='alert alert-success'>"._('Record added successfully')."</div>";
        } else {
            print "<div class='alert alert-danger'>"._('Failed to add record')."</div>";
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
print "<input type='text' class='form-control' name='hostname' required>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('IP Address')."</label>";
print "<div class='col-sm-10'>";
print "<input type='text' class='form-control' name='ip_addr' required>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('Record Type')."</label>";
print "<div class='col-sm-10'>";
print "<select name='record_type' class='form-control' required>";
print "<option value='A'>A</option>";
print "<option value='AAAA'>AAAA</option>";
print "<option value='PTR'>PTR</option>";
print "<option value='CNAME'>CNAME</option>";
print "<option value='MX'>MX</option>";
print "<option value='TXT'>TXT</option>";
print "</select>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('TTL')."</label>";
print "<div class='col-sm-10'>";
print "<input type='number' class='form-control' name='ttl' value='3600'>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<label class='col-sm-2 control-label'>"._('Description')."</label>";
print "<div class='col-sm-10'>";
print "<input type='text' class='form-control' name='description'>";
print "</div>";
print "</div>";

print "<div class='form-group'>";
print "<div class='col-sm-offset-2 col-sm-10'>";
print "<button type='submit' class='btn btn-default'>"._('Add record')."</button>";
print "</div>";
print "</div>";

print "</form>";

# include footer
include("app/footer.php"); 