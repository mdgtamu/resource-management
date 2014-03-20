<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Login | Department of Chemistry | Texas A&amp;M University</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/custom-styles.css" rel="stylesheet">
    
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
    <?php include("inc/header.php"); ?>
    
    <!-- Static navbar -->
    <?php include("inc/navbar.php"); ?>
    
    <div class="container container-main">   
      
      <!-- Main content -->      
      <div class="row">
      	<div class="col-md-6">
                  
          <h1 class="text-center">Texas A&amp;M Members</h1>
          <p>
          <form method="link" action="home">
          	<button type="submit" class="btn btn-primary btn-lg btn-block">NetID Login</button>
          </form>
            </p>
          <p class="text-center">Texas A&amp;M University members must login with their NetID to manage Department of Chemistry IT resources.</p>
          
        </div> <!-- End col-md-6 -->

      	<div class="col-md-6">
                    
          <h1 class="text-center">Non-Texas A&amp;M Members</h1>
          <p>
          <form method="link" action="register.php">
          	<button type="submit" class="btn btn-primary btn-lg btn-block">Register</button>
          </form>
          </p>
		  <p class="text-center">Non-Texas A&amp;M members must register with the department in order to gain access to Department of Chemistry IT resources.</p>

        </div> <!-- End col-md-6 -->
      </div> 
      <!-- END main content --> 
      
      <!-- footer content -->
      <?php include("inc/footer-content.php"); ?>
 
    
    <!-- footer page -->
    <?php include("inc/footer-page.php"); ?>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>
