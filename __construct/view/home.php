<link rel='stylesheet'  href='./__construct/css/__construct.css'>
<script src='./__construct/js/__construct.js'></script>

<?php  
	$this->printMessage();
?>
<div style="width:1280px; min-height:600px; margin:50px auto; ">


<h1 class="text-center ">LitePHP Builder</h1>
<table class="table table-bordered table-hover" >
	<tr class="info">
		<td>
			Create an specific structure 
		</td>
		<td></td>
		<td>
			Admin : <input id="adminInput" type="text"><button type="button" onclick="cria_estrutura_admin_especial($('#adminInput').val());"> OK </button>
		</td>
		<td>
			Root : <input id="rootInput" type="text"><button type="button" onclick="cria_estrutura_root_especial($('#rootInput').val())" > OK </button>
		</td>
	</tr>
	<?php  foreach ($this->db_tables as $key => $table): ?>
			<tr>
				<td width="300px" >
					<div style=" text-align:center; padding: 25px 0;">
						<?php echo $table?>
					</div>	
				</td>
				<td>
					
						<?php  if (!file_exists(ADMIN."{$table}/model/{$table}.class.php")){ ?>
							<button type="button" table_name="<?php echo $table?>" onclick="" >
								Create Model  <br> <b> <?php echo $table?></b>
							</button>
						<?php }else  echo "<b> Model created </b>"; ?>
				</td>
				<td >
					<?php  if (!file_exists(ADMIN."{$table}/control/{$table}_control.php")): ?>
						<div class="ctControls">
							<button type="button" table_name="<?php echo $table?>" onclick="cria_estrutura_admin(this);" >
								Create Admin Control/Model/View of  <br> <b> <?php echo $table?></b>
							</button>
							<div> Tool :</div>
							<small>
								<input type="checkbox" id="chk_<?php echo $table?>" checked >Create / Link to  Tool	
							</small>
							<input type="text" id="<?php echo $table?>" value="<?php  echo substr($table,0,4); ?>" >
						</div>
					<?php  else: 
						$sql = "SELECT ml.id FROM 
									menul ml join access_tool at ON ml.fkaccess_tool  = at.id 
									WHERE at.table_name = '{$table}' " ;
						$row = $dummy_model->runSQL($sql);
						if (!empty($row)) {?>
							<div class="success">
								ADMIN Structure done.
								<!-- <button onclick="removeFromMenu('<?php echo $row['id'] ?>')"> Remove from Menu</button> -->
							</div> 
						<?}
						else{?>
							<a  href="./__construct/addToMenuLeft/<?php echo $table ?>"> ADD CRUD to ADMIN left menu  </a>
						<?}?>
					<?php endif ?>
			</td>
			<td>
				<?php  if (!file_exists(ROOT."{$table}/control/{$table}_control.php")): ?>
					<button type="button" table_name="<?php echo $table?>" onclick="cria_estrutura_root(this);" >
						Create ROOT Control/View/css/js of  <br> <b> <?php echo $table?></b>
					</button>
				<?php  else: ?>
					<div class="success">
						ROOT Structure done.
					</div> 
				<?php  endif ?>
			</td>
				<?php  endforeach; ?>
		</tr>
</table>
</div>

<script type="text/javascript">

function cria_estrutura_admin (element) {
	var table 			= element.getAttribute('table_name');
	var chk_ferramenta 	= document.querySelector('#chk_'+table);
	var tool 		= document.querySelector("#"+table);
		$.ajax({
			url: './__construct/buildAdminStructure',
			type: 'post',
			dataType: 'html',
			data: { table:table, createTool : chk_ferramenta.checked, tool:tool.value },
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function(data) {
			console.log("complete");
		});
}

function cria_model (element) {
	var table 			= element.getAttribute('table_name');
	$.get('./?',{ table:table }, function(data) {
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
		$.ajax({
			url: './__construct/buildAdminStructure',
			type: 'post',
			dataType: 'html',
			data: { table:table, createTool : false },
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function(data) {
			location.reload();
			console.log("complete");
		});
}

function cria_estrutura_root (element) {
	var table = element.getAttribute('table_name');
		$.ajax({
			url: './__construct/buildRootStructure',
			type: 'post',
			dataType: 'html',
			data: { table:table },
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function(data) {
			location.reload();
			console.log("complete");
		});
}

function cria_estrutura_root_especial (nome) {
	var table = nome;
		$.ajax({
			url: './__construct/buildRootStructure',
			type: 'post',
			dataType: 'html',
			data: { table:table },
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function(data) {
			location.reload();
			console.log("complete");
		});
}



</script>