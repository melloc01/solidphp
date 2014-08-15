<div class="topoPaginaInterna">
<div class="  col-sm-8" >
	<h1 class="tituloPagina"> <i class='fa fa-fw <?php echo $this->icon?>'></i> <?php  echo "$this->titulo" ?></h1>
</div>
	<div class="col-sm-4 text-right ctButtonsTopo">
		<ul class="list-unstyled list-inline"  >
			<?php  
			if ( isset($this->no_controls_lista) && is_array($this->no_controls_lista) ) {
				if (!in_array("novo", $this->no_controls_lista) ): ?>
				<li>
					<a  class="btn btn-primary "  href='/admin/<?php echo  $this->getControllerName()?>/create'>Novo <i class="fa fa-file-o fa-fw"></i></a>
				</li>
			<?php  
				endif;
			} 
			 if ( $this->getActionName() == 'create' || $this->getActionName() == 'edit'  ){?>
				<li>
					<a  class="btn btn-default"  href='javascript:history.back()'><span class="glyphicon glyphicon-backward"></span> <span class="esconde_mobile">Voltar</span></a>					
				</li> 
				<?}?>
		</ul>
	</div>
</div>

