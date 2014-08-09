<link rel="stylesheet" href="login/css/login.css">

	<?php  
		$this->printMessage();
	?>
<div class="container">
	<div class="login-container">
		<div id="output">
		</div>
		<div class="avatar ">
			<span class='glyphicon glyphicon-user ' style='color:#eee; text-shadow: 0 0 1px #ccc; font-size:40px; margin-top:25px;'></span>
		</div>
		<div>
			<h4>Painel de Controle</h4>
		</div>
		<div class="form-box">
			<form  role="form" action="./login/auth" method="post"   accept-charset="utf-8">
				<input type="text" autofocus name="user" class="form-control" id="userLogin" placeholder="e-mail">
				<input type="password" name="pass" class="form-control"  placeholder="senha">
				<button class="btn btn-info btn-block login" type="submit">Login</button>
			</form>
		</div>
	</div>
</div>