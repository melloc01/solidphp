<?php  
require(ADMIN."core/view/titulo_pagina.php");
?>
<div class="col-md-12">
	<form action="" method="post" role="form" id="formeditar"  accept-charset="utf-8" enctype="multipart/form-data" >
		<input type="hidden" name="upd" 	value="<?php echo $registro['id']?>" 	>
		<?php  
		$this->Form->print_inputs($registro);
		?>
		<div>
			<button class="btn btn-primary  mt15 pull-right" type="submit">Editar</button>
		</div>
	</form>
</div>
</div>
<?
	// var_dump($this->Form->getInputs());
	// foreach ($this->Form->getInputs() as $key => $input) {
	// 	// echo $input;
	// }
?>
