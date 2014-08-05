<link rel="stylesheet" href="login/css/login.css">

	<?php  
		$this->printMessage();
	?>
<div class="container">
	<div class="login-container">
		<div id="output">
		</div>
		<div class="avatar">
			<i class='fa fa-user'></i>
		</div>
		<div>
			<h4>Control Panel</h4>
		</div>
		<div class="form-box">
			<form  role="form" action="./?l=login&sl=auth" method="post"   accept-charset="utf-8">
				<input type="text" autofocus name="user" class="form-control" id="userLogin" placeholder="e-mail">
				<input type="password" name="pass" class="form-control"  placeholder="senha">
				<button class="btn btn-info btn-block login" type="submit">Login</button>
			</form>
		</div>
	</div>
</div>