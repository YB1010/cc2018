<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Weather Recommendation</title>
  	<link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="css/cc2018.css">
	<script
	  src="https://code.jquery.com/jquery-3.1.1.min.js"
	  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
	  crossorigin="anonymous"></script>
</head>
<body>
<div class="page-login">
<div class="ui placeholder segment">
  <div class="ui two column very relaxed stackable grid">
    <div class="column">
	
  <form method="post" action="register.php">
      <div class="ui form">
        <div class="field">
  		<label>Username</label>
          <div class="ui left icon input">
            <input name ="username" type="text" placeholder="Username" required>
            <i class="user icon"></i>
          </div>
        </div>
		<div class="field">
  		<label>Email</label>
          <div class="ui left icon input">
            <input name ="email" type="email" required>
            <i class="mail icon"></i>
          </div>
        </div>
        <div class="field">
  		<label>Password</label>
          <div class="ui left icon input">
            <input name ="password_1" type="password" required>
            <i class="lock icon"></i>
          </div>
        </div>
		<div class="field">
  		<label>Confirm Password</label>
          <div class="ui left icon input">
            <input name ="password_2" type="password" required>
            <i class="lock icon"></i>
          </div>
        </div>
		
  		<button type="submit" class="ui primary button" name="reg_user">Register</button>
      </div>
	  
  </form>
    </div>
    <div class="middle aligned column">
	<a href="userlogin.php">
	<div class="ui big button">
        Login
	</div>
	<a/>
    </div>
  </div>
  <div class="ui vertical divider">
    Or
  </div>
</div>
  	<?php include('errors.php'); ?>
</div>
</body>

</html>