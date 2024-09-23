<head>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="img/logoGreen.png">
</head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script>
  if (localStorage.getItem("isDark") == null) {
    localStorage.setItem("isDark", "false");
  }
</script>
<div class="navbar-fixed">
  <nav>
    <div class="nav-wrapper">
      <a href="index.php" style="margin-left: 1.75rem;" class="brand-logo"><img
          style="width: 2rem; position: relative; right: 0.7rem; top: 0.25rem;" src="img/logoGreen.png">GradePlus</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down" style="margin-right: 0.5rem;">
        <li><a class="theme"><i class="material-icons left theme"></i><span class="theme"></span></a></li>
        <li><a href="login.php"><i class="material-icons left">person</i><?php echo $_SESSION['dname'] ?? "Login"?></a>
        </li>
      </ul>
    </div>
  </nav>
</div>
<style>
  body {
    transition: background-color 0.5s, color 0.5s;
  }

  ::-webkit-scrollbar {
    display: none;
  }

  .nav-wrapper {
    transition: background-color 0.5s, color 0.5s;
  }

  ul#nav-mobile li a i {
    margin-right: 0.5rem;
    margin-top: 0.05rem;
  }
</style>