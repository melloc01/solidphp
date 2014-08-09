<?php  
require(ADMIN."core/view/titulo_pagina.php");
?>
<div class="col-md-12">
	<form action="" method="post" role="form" id="formeditar"  accept-charset="utf-8" enctype="multipart/form-data" >
		<input type="hidden" name="upd" 	value="<?php echo $registro['id']?>" 	>
		<?php  
			foreach ($tools as $key => $tool) {?>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="tools[]" value="<?php echo $tool['id'] ?>">
						<small>(<b> <?php echo $tool['code'] ?> </b>)</small>
						<?php echo $tool['name'] ?> <br>
						<?php echo $tool['description'] ?>
					</label>
				</div>
				<br>
			<?}
		?>
		<div>
			<button class="btn btn-primary  mt15 pull-right" type="submit">Editar</button>
		</div>
	</form>
</div>
</div>
