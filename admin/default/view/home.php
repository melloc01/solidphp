<?php  
	require(ADMIN."core/view/titulo_pagina.php");
?>

<div class="col-sm-12 bs3-panel ">
	<div class="row">
		<div  class="col-sm-6  ">
			<div class="bloco_loop">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Notícias</h3>
					</div>
					<div class="panel-body">
						<div class="row" >
							<div class=" col-md-12 rss_source"> Folha news : Em cima da hora </div>
						</div>
						<div class="ajax_news" id="folhaNews">
						</div>

						<div class="row">
							<div class=" col-md-12 rss_source"> O Estado de São Paulo news </div>
						</div>
						<div class="ajax_news" id="estadaoNews">
						</div>

						<div class="row">
							<div class=" col-md-12 rss_source"> Galileu news </div>
						</div>
						<div class="ajax_news" id="galileuNews">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
