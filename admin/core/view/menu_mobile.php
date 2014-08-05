		<ul>
			<li class="headerMobile">
				<div class="titulo">
					<big>Menu</big>
				</div>
			</li>
			<a href="./admin" >
				<li>
					<div class=" titulo ">			
						<span style="font-size:20px;" class="glyphicon glyphicon-home"> </span> &nbsp;&nbsp;&nbsp;Painel do Usuário
					</div>
				</li>
			</a>
			<?php  foreach ($this->menu_left	 as $key => $item_menu): ?>
				<a href="./admin/?l=<?php echo $item_menu['nome']?>" title="<?php echo $item_menu['nome_menu']?>">
					<li class="linhaMenuMobile<?php if (isset($_GET['l']) && $_GET['l']==$item_menu['nome']) {echo "_selected";}?> ">
						<!-- <span class="glyphicon glyphicon-pushpin">&nbsp;</span> --> <?php echo $item_menu['nome_menu']?>
					</li>
				</a>
			<?php  endforeach ?>
			<a href="./admin">
				<li class="menuHeader">
					<div class=" titulo ">
						<span style="font-size:20px;" class="glyphicon glyphicon-user"> </span> &nbsp;&nbsp;&nbsp;Painel Administrativo
					</div>
				</li>
			</a>
			<?php  foreach ($this->menu_left_administrativo as $key => $item_menu): ?>
				<a href="./admin/?l=<?php echo $item_menu['nome']?>" title="<?php echo $item_menu['nome_menu']?>">
					<li class="linhaMenuMobile<?php if (isset($_GET['l']) && $_GET['l']==$item_menu['nome']) {echo "_selected";}?>">
						<!-- <span class="glyphicon glyphicon-pushpin">&nbsp;</span> --> <?php echo $item_menu['nome_menu']?>
					</li>
				</a>
			<?php  endforeach ?>

			<a href="./admin/?l=system" title="Configurações">
				<li class="linhaMenuMobile<?php if (isset($_GET['l']) && $_GET['l']=="system") {echo "_selected";}?>">
					<!-- <span class="glyphicon glyphicon-pushpin">&nbsp;</span> -->Configura&ccedil;&otilde;es
				</li>
			</a>

		</ul>
