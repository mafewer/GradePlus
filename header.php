<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="img/logoGreen.png">
</head>
<script>
  if (localStorage.getItem("isDark") == null) {
    localStorage.setItem("isDark", "false");
  }
</script>
<div class="navbar-fixed">
  <ul id="dropdown1" class="dropdown-content bwcolornotext">
    <li><a class="logout"><i class="material-icons left">logout</i>Logout</a></li>
    <li><a class="accountdashboard"><i class="material-icons left">account_circle</i>Dashboard</a></li>
    <?php if($_SESSION['username'] != 'admin'): ?>
    <li><a class="accountservice"><i class="material-icons left">settings</i>Account Settings</a></li>
    <?php endif;?>
  </ul>
  <nav>
    <div class="nav-wrapper">
      <a href="index.php" style="margin-left: 1.75rem;" class="brand-logo"><img
          style="width: 2.4rem; position: relative; right: 0.7rem; top: 0.4rem;" src="img/logoGreen.png">GradePlus</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down" style="margin-right: 0.5rem;">
        <li><a class="theme"><i class="material-icons left theme"></i><span class="theme"></span></a></li>
        <li><a href="index.php"><i class="material-icons left">home</i>Home</a></li>
        <?php if(isset($_SESSION['dname'])): ?>
        <li><a class="dropdown-trigger" data-target="dropdown1"><i
              class="material-icons left">person</i><?php echo $_SESSION['dname']?><i
              class="material-icons right">arrow_drop_down</i></a>
        </li>
        <?php else: ?>
        <li><a href="login.php"><i class="material-icons left">person</i>Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
</div>
<style>
  body {
    transition: background-color 0.5s, color 0.5s;
    background-color: black;
  }

  ::-webkit-scrollbar {
    display: none;
  }

  .nav-wrapper {
    transition: background-color 0.5s, color 0.5s;
    user-select: none;
  }

  ul#nav-mobile li a i {
    margin-right: 0.5rem;
    margin-top: 0.05rem;
  }

  ul.dropdown-content {
    position: fixed;
    top: -4.5rem;
    text-wrap: nowrap;
    width: 16rem !important;
  }
</style>
<script>
  $(".dropdown-trigger").dropdown();
  $("a.logout").click(() => {
    $.ajax({
      url: "services/logout.php",
      type: "POST",
      data: {
        authorize: "gradeplus"
      },
      success: (response) => {
        if (response["success"] == 1) {
          window.location.href = "login.php";
        }
      }
    });
  });
  $("a.accountdashboard").click(() => {
    window.location.href = "login.php";
  });
</script>