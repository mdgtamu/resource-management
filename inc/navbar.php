    <div class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/manage/home">Resource Management</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="#">research description</a></li>
            <li><a href="#">profile</a></li>
            <li><a href="#">sponsorship</a></li>
            <li><a href="#">sponsorships</a></li>
            <li><a href="#">admin</a></li>
          </ul>
                   
          <?php if($_SESSION["givenName"]): ?>
          <p class="navbar-text navbar-right hidden-xs"><strong><?php echo "Welcome: " . $_SESSION["givenName"] . " " . $_SESSION["sn"] ?></strong></p>
          <?php endif; ?>
          
        </div><!--/.nav-collapse -->
      </div>
    </div>