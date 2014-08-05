<?php  
	require(ADMIN."core/view/titulo_pagina.php");
?>
		<div class="col-md-12">
			<form action="" method="post" role="form" id="formNovo"  accept-charset="utf-8" enctype="multipart/form-data" >
				<input type="hidden" name="ins" value="1">
				<?php  
					$this->Form->print_inputs();
				?>
				<button class="btn btn-primary btn-lg mt15 pull-right" type="submit">Cadastrar</button>
			</form>
		</div>
	</div>
	<?
	// var_dump($this->Form->getInputs());
	// foreach ($this->Form->getInputs() as $key => $input) {
	// 	// echo $input;
	// }
	?>
