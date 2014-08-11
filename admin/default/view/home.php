<?php  
require(ADMIN."core/view/titulo_pagina.php");
?>
<div class="col-sm-12 bs3-panel ">
	<div class="row" style=" height:500px; overflow:auto;">
		<div  class="col-sm-12  ">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Folha de São Paulo </h3>
				</div>
				<div class="panel-body">
					<div class="ajax_news" id="folhaNews"></div>
				</div>
			</div>
		</div>
		<div  class="col-sm-12  ">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">O Estado de São Paulo </h3>
				</div>
				<div class="panel-body">
					<div class="ajax_news" id="estadaoNews"></div>
				</div>
			</div>
		</div>
		<div  class="col-sm-12  ">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Época Idéias & Inovações</h3>
				</div>
				<div class="panel-body">
					<div class="ajax_news" id="epocaNegociosNews"></div>
				</div>
			</div>
		</div>
		<div  class="col-sm-12  ">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Época Brasil</h3>
				</div>
				<div class="panel-body">
					<div class="ajax_news" id="epocaNews"></div>
				</div>
			</div>
		</div>
	</div>
</div>
