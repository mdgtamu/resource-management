<?php
session_start();
require_once "../inc/functions/function_repo.php";

///////////////////////////////////////////////////////////////////////
// save shibboleth environment variables 
///////////////////////////////////////////////////////////////////////
$_SESSION["eppn"] = $_SERVER["eppn"]; //eduPersonPrincipalName - ex. NetID@tamu.edu
$_SESSION["givenName"] = $_SERVER["givenName"]; // givenName
$_SESSION["sn"] = $_SERVER["sn"]; //surname
$_SESSION["uin"] = $_SERVER["uin"]; //tamuEduPersonUIN
$_SESSION["affiliation"] = $_SERVER["affiliation"]; //eduPersonScopedAffiliation - ex. member@tamu.edu;employee@tamu.edu;staff@tamu.edu;student@tamu.edu
$_SESSION["unscoped-affiliation"] = $_SERVER["unscoped-affiliation"]; //eduPersonAffiliation - ex. member;employee;staff;student
$_SESSION["entitlement"] = $_SERVER["entitlement"]; //eduPersonEntitlement 
$_SESSION["tamuEduPersonDepartmentName"] = strtoupper($_SERVER["tamuEduPersonDepartmentName"]); //tamuEduPersonDepartmentName - ex. CHEMISTRY, CHEMISTRY DEPARTMENT, BIOLOGY
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>Resources | Department of Chemistry | Texas A&amp;M University</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/custom-styles.css" rel="stylesheet">
    
    <!-- Custom Fonts -->
    <!-- For Body -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- For Headings -->
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
      <script src="assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- Header -->
    <?php include("../inc/header.php"); ?>
    
    <!-- Static navbar -->
    <?php include("../inc/navbar.php"); ?>
    
    <div class="container container-main">
    
      <!-- Alerts -->
      <?php 
	  	$_SESSION["alerts"]=1; 
		if (isset($_SESSION["alerts"])){
		  include("../inc/alerts.php");
		}
       unset($_SESSION["alerts"]); 
	 ?>
	  
      
      
      <!-- Main content -->      
      <div class="row">
      	<div class="col-lg-12">
          <h1 class="page-header"><span class="text-center">Resources</span></h1>
          <?php
			foreach($_SESSION as $value) {
			  print $value."<br>";
			}
		  ?>
          
          <h2>Your Approved Resources</h2>
          <table class="table table-responsive">
          	<thead>
              <tr>
              	<td><strong>Resource Name</strong></td>
                <td><strong>Status</strong></td>
              </tr>
            </thead>
          	<tr class="success">
              <td>Medusa</td>
              <td><span data-toggle="tooltip" data-placement="left" class="status text-success" title="description of ACTIVE status will come from Status.status_desc in database.">Active</span></td>
            </tr>
            <tr class="success">
              <td>Orion</td>
              <td><span data-toggle="tooltip" data-placement="left" class="status text-success" title="description of ACTIVE status will come from Status.status_desc in database.">Active</span></td>
            </tr>
            <tr class="info">
              <td>Rocks Cluster</td>
              <td><span data-toggle="tooltip" data-placement="left" class="status text-info" title="description of PENDING status will come from Status.status_desc in database.">Pending</span></td>
            </tr>
            <tr class="warning">
              <td>BOINC</td>
              <td><span data-toggle="tooltip" data-placement="left" class="status text-warning" title="description of WARNING status will come from Status.status_desc in database.">Warning</span></td>
            </tr>
            <tr class="danger">
              <td>Sun 6048</td>
              <td><span data-toggle="tooltip" data-placement="left" class="status text-danger" title="description of EXPIRED status will come from Status.status_desc in database.">Expired</span></td>
            </tr>
          </table> 
        </div> <!-- End col-lg-12 -->
      </div> 
      
      <div class="row">
      	<div class="col-lg-12">
          <h2 class="page-header">Request Access</h2>
          <h3>Chemistry Resources <small>for chemistry members only</small></h3>
          
          <form class="form-horizontal" role="form">
            <div class="form-group">
              <div class="col-sm-12">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="" disabled><span data-toggle="tooltip" data-placement="right" class="host-desc" title="You have already requested access to Medusa. Status is Active.">Medusa</span>
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value=""><span data-toggle="tooltip" data-placement="right" class="host-desc" title="Description of SUN 6048 status will come from Hosts.host_description in database.">Sun 6048</span>
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="" disabled><span data-toggle="tooltip" data-placement="right" class="host-desc" title="You have already requested access to Rocks Cluster. Status is Pending.">Rocks Cluster</span>
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="" disabled><span data-toggle="tooltip" data-placement="right" class="host-desc" title="You have already requested access to BOINC. Status is Warning.">BOINC</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-default">Request Chemistry Resources</button>
              </div>
            </div>
          </form> 
          
        </div> <!-- End col-lg-12 -->
      </div> 
      
      <div class="row">
      	<div class="col-lg-12">
          <h3>Chemistry/Aerospace Resources <small>for chemistry &amp; Aerospace members only</small></h3>
        </div> <!-- End col-lg-12 -->
      </div>   
          
      <div class="row">
      	<div class="col-lg-12">
          <h3>Texas A&amp;M University Resources <small>for all Texas A&amp;M members</small></h3>
        </div> <!-- End col-lg-12 -->
      </div>        
          
      <div class="row">
      	<div class="col-lg-12">
          <h3>Groups <small>request access to special groups</small></h3>
        </div> <!-- End col-lg-12 -->
      </div> 
          
      <div class="row">
      	<div class="col-lg-12">
          <h3>Software <small>request access to software</small></h3>
        </div> <!-- End col-lg-12 -->
      </div>   
      <!-- END main content --> 
      
      <!-- footer content -->
      <?php include("../inc/footer-content.php"); ?>
 
    
    <!-- footer page -->
    <?php include("../inc/footer-page.php"); ?>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.status').tooltip();
		$('.host-desc').tooltip();
      });
    </script> 
  </body>
</html>
