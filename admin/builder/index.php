
<?php
	include_once '../core/config/Conexao.class.php';
		$sql = " show tables ";
		
		$db = Conexao::getInstance();
		$system_tables = array("ferramenta","historico","menul","nivel_usuarios","access",'access_tool','access_level',"usuarios","nivel");
		$dbStatment = $db->prepare($sql);
		$dbStatment->execute();
		$tables=null;
		$tables = $dbStatment->fetchAll();

		?>

	<div style="width:1280px; min-height:600px; margin:50px auto; ">
		<h1>
			LitePHP Builder
		</h1>
		<table class="conteudo" >
			<tr>
				<td>
					Create an specific structure
				</td>
				<td>
					Admin : <input id="adminInput" type="text"><button type="button" onclick="cria_estrutura_admin_especial($('#adminInput').val());"> OK </button>
				</td>
				<td>
					Root : <input id="rootInput" type="text"><button type="button" onclick="cria_estrutura_root_especial($('#rootInput').val())" > OK </button>
				</td>
			</tr>
			<?php  foreach ($tables as $key => $table){
				if ( !in_array($table[0], $system_tables,true) ) {
					?>
					<tr>
						<td width="300px" >
							<div style=" text-align:center; padding: 25px 0;">
								<?php echo $table[0]?>
							</div>	
						</td>
						<td >
							<?php  if (!file_exists("../{$table[0]}/control/{$table[0]}_control.php")): ?>
								<div class="ctControls">
								<?php  if (!file_exists("../{$table[0]}/model/{$table[0]}.class.php")){ ?>
									<button type="button" table_name="<?php echo $table[0]?>" onclick="cria_model(this);" >
										Create Model  <br> <b> <?php echo $table[0]?></b>
									</button>
								<?php }else  echo "<b> Model criado </b>"; ?>
									<button type="button" table_name="<?php echo $table[0]?>" onclick="cria_estrutura_admin(this);" >
										Create Admin Control/Model/View of  <br> <b> <?php echo $table[0]?></b>
									</button>
									<div> Tool :</div>
									<small>
										<input type="checkbox" id="chk_<?php echo $table[0]?>" checked >Create / Link to  Tool	
									</small>
									<input type="text" id="<?php echo $table[0]?>" value="<?php  echo substr($table[0],0,4); ?>" >
								</div>
							<?php  else: 
							$sql = "SELECT ml.id FROM 
										menul ml join access_tool at ON ml.fkaccess_tool  = at.id 
										WHERE at.table_name = '{$table[0]}' " ;
							$dbStatment = $db->prepare($sql);
							if($dbStatment->execute() && $dbStatment->rowCount() != 0){
								$row = $dbStatment->fetch();
								?>
								<div class="success">
									ADMIN Structure done.
									<button onclick="removeFromMenu('<?php echo $row['id'] ?>')"> Remove from Menu</button>
								</div> 
								<?
							}
							else{?>
							<button type="button" onclick="addToMenuLeft('<?php echo $table[0]?>')"> ADD CRUD to ADMIN left menu  </button>
							<?}
							?>
						<?php  endif ?>
					</td>
					<td>

						<?php  if (!file_exists("../../{$table[0]}/control/{$table[0]}_control.php")): ?>
							<button type="button" table_name="<?php echo $table[0]?>" onclick="cria_estrutura_root(this);" >
								Create ROOT Control/View/css/js of  <br> <b> <?php echo $table[0]?></b>
							</button>
						<?php  else: ?>
							<div class="success">
								ROOT Structure done.
							</div> 
						<?php  endif ?>
					</td>
				</tr>
				<?php 
			}
		} ?>
	</table>
	</div>

	<script type="text/javascript" src="../core/js/jquery.min.js" ></script>
	<script type="text/javascript">
		function cria_estrutura_admin (element) {
			var table 			= element.getAttribute('table_name');
			var chk_ferramenta 	= document.querySelector('#chk_'+table);
			var ferramenta 		= document.querySelector("#"+table);
			if (chk_ferramenta.checked) {
				$.get('cria_estrutura.php',{ table:table, ferramenta : ferramenta.value }, function(data) {
					alert(data);
					location.reload();
				})
			}else {
				// $.get('cria_estrutura.php',{ table:table }, function(data) {
					// location.reload();
				// })
			}
		}

		function cria_model (element) {
			var table 			= element.getAttribute('table_name');
			$.get('cria_model.php',{ table:table }, function(data) {
				alert(data);
				location.reload();
			});
		}

		function cria_ferramenta (element) {
			var table = $(element).attr('table_name');
			var ferramenta = $(element).parent.find('input').prop('checked');
			alert(ferramenta);
			// $.get('cria_estrutura.php',{ table:table }, function(data) {
				// location.reload();
			// });
		}
		function cria_estrutura_admin_especial (nome) {
			var table = nome;
			$.get('cria_estrutura.php',{ table:table }, function(data) {
				// alert(data);
				location.reload();
			});
		}
		function cria_estrutura_root (element) {
			var table = $(element).attr('table_name');
			$.get('cria_estrutura_root.php',{ table:table }, function(data) {
				// alert(data);
				location.reload();
			});
		}
		function cria_estrutura_root_especial (nome) {
			var table = nome;
			$.get('cria_estrutura_root.php',{ table:table, static:true }, function(data) {
				// alert(data);
				location.reload();
			});
		}

		function addToMenuLeft (nome) {
			var table = nome;
			$.get('add_menu_left.php',{ table:table }, function(data) {
				alert(data);
				location.reload();
			});
		}
		function removeFromMenu(_id) {
			var id = _id;
			$.get('remove_from_menu.php',{ id:id }, function(data) {
				// alert(data);
				location.reload();
			});
		}
	</script>
 ?>