		<li>
			<a href="./"><i class="fa fa-dashboard fa-fw"></i> <b><small>Painel Inicial</small></b></a>
		</li>

		<?
		
		if ($this->menu_left) {
			foreach ($this->menu_left	 as $key => $item_menu){
				if ( $item_menu['fkaccess_tool'] == null || $_SESSION['admin']['access'][$item_menu['code']] ) {
					?>
					<li class="item">
						<a href="<?php echo $item_menu['link']?>" title="<?php echo $item_menu['mask']?>" >
							<i class="fa fa-<?php echo ($item_menu['icon']!="")? $item_menu['icon'] : "paperclip" ?> fa-fw"></i> <?php echo $item_menu['mask']?>
						</a>
						<?php  if (isset($_GET['l']) && "".$_GET['l']==$item_menu['link']) {echo "<div class='arrow-left'></div>";}?>
					</li>
					<?php 
				} 
			}
		}?>
		<?php  
			// Kint::dump($this->menu_left);
			// Kint::dump($_SESSION['admin']);
		?>
		<li class="item ">
			<a href="javascript:event.preventDefault();" title="Configurações">
				<i  class="fa fa-gears fa-fw"></i>	<b><small> Ferramentas Administrativas </small></b>
			</a>
		</li>
		<?php   
		if ($_SESSION['admin']['access']['_use']) {?>
		<li class=" item<?php if (isset($_GET['l']) && $_GET['l']=="user") {echo "_selected";}?>">
			<a href="user" title="Configurações">
				<i class="fa fa-users fa-fw"></i> Usuários
			</a>
			<?php if (isset($_GET['l']) && $_GET['l']=="user") {echo "<div class='arrow-left'></div>";}?>
		</li>
		<?php  }
		?>
		<?php   
		if ($_SESSION['admin']['access']['_his']) {?>
		<li class=" item<?php if (isset($_GET['l']) && $_GET['l']=="history") {echo "_selected";}?>">
			<a href="history" title="Configurações">
				<i class="fa fa-save fa-fw"></i> Histórico
			</a>
			<?php if (isset($_GET['l']) && $_GET['l']=="history") {echo "<div class='arrow-left'></div>";}?>
		</li>
		<?php  }
		?>

		<li class=" item<?php if (isset($_GET['l']) && $_GET['l']=="settings") {echo "_selected";}?>">
			<a href="settings" title="Configurações">
				<i class="fa fa-cog fa-fw"></i> Configurações
			</a>
			<?php if (isset($_GET['l']) && $_GET['l']=="settings") {echo "<div class='arrow-left'></div>";}?>
		</li>
