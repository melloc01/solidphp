<?php   
	/**
	 * Lista.php
	 * Needs the below variables :: initialized by control
	 *  $this->titulo
	 *	$this->no_controls_lista  -- things you don't wan t to output
	 * 	$registros = registers of the current scope
	 */
	?>
	<?php  
	require(ADMIN."core/view/titulo_pagina.php");
	?>
	<div class=" ctInnerContent">
		<div class="col-md-12">
			<table class="table table-hover  " >
				<thead>
					<?php 
					foreach ($this->list_headers as $key => $value) {?>
					<th><?php echo $value ?></th>
					<?}
					?>

					<?php if (!in_array("editar", $this->no_controls_lista) ){?>
					<th>Editar</th>
					<?php } ?>

					<?php if (!in_array("publicar", $this->no_controls_lista) && $this->Form->hasColumn('publicado') ){?>
					<th>Publicar</th>
					<?php } ?>
					<?php if (!in_array("remover", $this->no_controls_lista) ){?>
					<th>Remover</th>
					<?php } ?>
				</thead>
				<?php  
				if ($this->registros) {
					foreach ($this->registros as $key => $registro): ?>
					<tbody>
						<tr>
							<?php  
							foreach ($this->list_cells as $key => $cell) {?>
							<td>
								<?php echo $this->Util->TEMPLATE_ENGINE_mustache_like($cell,$registro) ?>
							</td>
							<?}
							?>
							<?php if (!in_array("editar", $this->no_controls_lista) ){?>
							<td class="text-center opcaoLista" title="editar <?php echo $_GET['l']?>" onclick='window.location="./?l=<?php echo $_GET["l"]?>&sl=editar&id=<?php echo $registro['id']?>"'>
								<div >
									<button type="button" class="btn  btn-info " ><small class="esconde_mobile"><!-- Editar --> </small><i class="fa fa-edit fa-fw"></i></button>
								</div>
							</td>
							<?}?>
							<?php if (!in_array("publicar", $this->no_controls_lista) && isset($registro['publicado']) ){?>

							<form id="pub_<?php echo $registro['id']?>" method="post" accept-charset="utf-8">
								<td class="text-center opcaoLista" title="<?php echo ($registro['publicado']==0)?"publicar ":"despublicar "; echo $_GET['l'] ?>" onclick='togglePublicado()'>
									<div >
										<input type="hidden" name="id" value="<?php echo $registro['id']?>">
										<input type="hidden" name="pub" value="<?php echo $registro['publicado']?>">
										<button type="button" onclick="togglePublicado(<?php echo $registro['id']?>,<?php echo $registro['publicado']?>);" class="btn  btn-<?php echo ($registro['publicado']==1)?"success":"default";?>  ">
											<small class="esconde_mobile">
												<?php 
										// echo ($registro['publicado']==1)?"Despublicar":"Publicar";
												?> 
											</small>
											<i class="fa fa-<?php echo ($registro['publicado']==1)?"eye":"eye-slash";?> fa-fw"></i>
										</button>
									</div>
								</td>
							</form>
							<?}?>
							<?php if (!in_array("remover", $this->no_controls_lista) ){?>
							<form id="rem_<?php echo $registro['id']?>" method="post" accept-charset="utf-8">
								<td class="text-center opcaoLista" title="deletar <?php echo $_GET['l']?>">
									<div >
										<input type="hidden" name="del" value="<?php echo $registro['id']?>">
										<button type="button" onclick="confirmDelete(<?php echo $registro['id']?>);" class="btn  btn-danger  "><small class="esconde_mobile"><!-- Remover --> </small><i class="fa fa-times fa-fw"></i></button>
									</div>
								</td>								
							</form>
							<?}?>
						</tr>
					<?php  endforeach; ?>

					<?php }?>

				</tbody>
			</table>
			<?php  
			if (!$this->registros) {?>
			<div class='text-muted'> Sem registros inseridos. </div> 
			<?}
			?>
		</div>
	</div>
</div>
</div>
</div>