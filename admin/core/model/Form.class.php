<?php   
	/**
	* Classe geradora de Formulários dinâmicos
	*/

	class Form
	{
		public $tableName,$db, $colunas, $inputs,$Util;
		private $control_names,$custom_inputs; 

		// step to decimal values
		public $step ="0.25";

		function __construct($table,$registro=""){
			$this->tableName = $table;
			$this->init();
		}

		public function setInputs($registro="")
		{
			$this->inputs = $this->getInputs($registro);
		}

		public function init()
		{
			$control_names = array('id','publicado'); 
			$this->Util = new Util();
			if ($this->tableName != 'user' ) 
				$this->colunas = $this->Util->getColumnsFromTable($this->tableName);
			else 
				$this->colunas = $this->getColumns();


			if ($this->colunas) {
				foreach ($this->colunas as $key => $coluna) {
					if (!in_array($coluna['Field'], $control_names)) {
						// try to get the default fkmask = register[1] - [0] generally is 'id'
						$this->custom_inputs[$coluna['Field']]['fkmask'] = 1;
					}
				}
			}
		}

		public function getColumns($columns = array(-1)){
			$colunas = array();
			$sql = "show columns from $this->tableName";
			$dbStatment = Conexao::getInstance()->prepare($sql);
			$dbStatment->execute();
			$all_columns  =  $dbStatment->fetchAll();

			if ($columns[0] == "-1") {
				return $all_columns;
			}
			else{
				foreach ($all_columns as $key => $coluna) {
					if($coluna["Field"]!="id" && in_array($coluna["Field"], $columns) ){
						$colunas[] = $coluna;
					}
				}
				return $colunas;
			}
		}

		/**  
		*	getInputs();
		*	List the inputs of the current table;
		*	
		*/
		public function getInputs($registro=""){
			$inputs = array();			
			// types that accept default values 
			$types_set_default_value = array('varchar','int','decimal','text');
			foreach ($this->colunas as $key => $coluna) {
				//trata registros inexistentes ( para sempre poder usar  registros[coluna])
				if(!isset($registro[$coluna["Field"]])){
					if (in_array($coluna['Type'], $types_set_default_value)) {
						$registro[$coluna["Field"]]=$coluna['COLUMN_DEFAULT']; 
					} else {
						$registro[$coluna["Field"]] = "";
					}
				}
				/*initializes input fields -- helps Album Tool */
				$inputs[$coluna['Field']]['label'] = "";
				$inputs[$coluna['Field']]['input'] = "";
				$inputs[$coluna['Field']]['print'] = true;

				if(($coluna["Field"]!="id") && ($coluna['Field']!= "fkAlbum")){
					$mask = isset($this->custom_inputs[$coluna['Field']]['mask'])? $this->custom_inputs[$coluna['Field']]['mask']: "";
					$tipo = explode("(",$coluna["Type"]);
						$tipo = $tipo[0];
						//select.
						$aux = str_split($coluna['Field'],2);
						if (($aux[0]=="fk")  && ($coluna['Field'] != "fkAlbum")) {
							if ($coluna['Field'] == "fkAlbum") {?>
							
							<?}
							$aux_1 = explode('k', $coluna['Field']);
							$field = $coluna['Field'];


							$tabela_fk = strtolower($aux_1[1]);
								//echo "$tabela_fk <br>";
							$sql = " SELECT * from $tabela_fk ";
							$dbStatment = Conexao::getInstance()->prepare($sql);
							$dbStatment->execute();
							$select_fill = $dbStatment->fetchAll();


							$inputs[$coluna['Field']]['label']  = "<label for='$this->tableName:{$coluna['Field']}' class='capital'>";

							$inputs[$coluna['Field']]['label'] .= ($mask!="") ? $mask : $coluna['Field'];

							$inputs[$coluna['Field']]['label'] .= "</label>";
							$inputs[$coluna['Field']]['input']  ="<select class='form-control default_input' name='{$coluna['Field']}' id='$this->tableName:{$coluna['Field']}'> 
							";

							$show_fk = $this->custom_inputs[$coluna['Field']]['fkmask'];

							foreach ($select_fill as $key => $option) {
								$inputs[$coluna['Field']]['input'] .= "<option value='{$option['id']}' ";
								if($option['id']==$registro[$coluna['Field']]) {
									$inputs[$coluna['Field']]['input'] .= "selected";
								} 
								$inputs[$coluna['Field']]['input'] .= ">";

								$inputs[$coluna['Field']]['input'] .="{$option[$show_fk]}</option>";
							}
							$inputs[$coluna['Field']]['input'] .= "</select>";
								// exit("rotulofk = ".$this->rotuloFk."<br>". var_dump($inputs[$coluna['Field']]) );
							?>

							<?

						}
						else{
							switch ($tipo) {
								case 'text':
								case 'mediumtext':
								case 'longtext':
								$inputs[$coluna['Field']]['label']  = "<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
								$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
								$inputs[$coluna['Field']]['label'] .= "</label> ";
								$inputs[$coluna['Field']]['input']  ="<textarea class='ckeditor form-control default_input' placeholder='{$coluna['Field']}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}'>{$registro[$coluna['Field']]}</textarea>"; 
								break;

								case 'tinyint':
								$inputs[$coluna['Field']]['label']   = "<label  class='capital'>";
								$inputs[$coluna['Field']]['label']  .= ($mask!="")? $mask : $coluna['Field'];
								$inputs[$coluna['Field']]['label']  .="</label><br>";
								$inputs[$coluna['Field']]['input']   = "<label  class='capital'>";
								$inputs[$coluna['Field']]['input'] .="<input type='radio'  id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' value='1' ";
								$inputs[$coluna['Field']]['input'] .= (($registro[$coluna['Field']] == "1") || ($registro[$coluna['Field']]=="") ) ?"checked":"";
								$inputs[$coluna['Field']]['input'] .= "/>";
								$inputs[$coluna['Field']]['input'] .= ' Sim </label>';
								$inputs[$coluna['Field']]['input'] .=" <label  class='capital'>";
								$inputs[$coluna['Field']]['input'] .="<input type='radio'  id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' value='0'";
								$inputs[$coluna['Field']]['input'] .= (($registro[$coluna['Field']] == "0") || ($registro[$coluna['Field']]=="") ) ?"checked":"";
								$inputs[$coluna['Field']]['input'] .= "/>";
								$inputs[$coluna['Field']]['input'] .= ' Não </label>';
								break;

								case 'date':
									$inputs[$coluna['Field']]['label'] ="<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
									$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
									$inputs[$coluna['Field']]['label'] .="</label>";
									$inputs[$coluna['Field']]['input'] = "<input type='text' class='datepicker form-control default_input' placeholder='{$coluna['Field']}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' value='";
									$inputs[$coluna['Field']]['input'] .= ($registro[$coluna['Field']] != "0000-00-00" ) ?$registro[$coluna['Field']] : date('Y-m-d'); 
									$inputs[$coluna['Field']]['input'] .="'/><small class='info text-muted'></small>";
								break;

								case 'timestamp':
								$inputs[$coluna['Field']]['label']  ="<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
								$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
								$inputs[$coluna['Field']]['label'] .="</label>";
								$inputs[$coluna['Field']]['input']  ="<input type='text' class='datetimepicker form-control default_input' placeholder='{$coluna['Field']}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' value='";
								$inputs[$coluna['Field']]['input'] .= ($registro[$coluna['Field']] != "0000-00-00 00:00:00" ) ?$registro[$coluna['Field']] : date('Y-m-d'); 
								$inputs[$coluna['Field']]['input'] .="'/><small class='info text-muted'></small>";
								break;

								case 'time':
								$inputs[$coluna['Field']]['label'] ="<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
								$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
								$inputs[$coluna['Field']]['label'] .="</label>";
								$inputs[$coluna['Field']]['input'] = "<input type='text' class='timepicker form-control default_input' placeholder='{$coluna['Field']}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' value='";
								$inputs[$coluna['Field']]['input'] .= ( $registro[$coluna['Field']]!=""  ) ? $registro[$coluna['Field']] : date('H:i'); 
								$inputs[$coluna['Field']]['input'] .="'/><small class='info text-muted'></small>";
								break;

								case 'int':
								$inputs[$coluna['Field']]['label']  = "<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
								$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
								$inputs[$coluna['Field']]['label'] .="</label>";
								$inputs[$coluna['Field']]['input']  ="<input class='form-control default_input' type='number' value='{$registro[$coluna['Field']]}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}'  placeholder='{$coluna['Field']}'>";
								break;

								case 'decimal':
								$inputs[$coluna['Field']]['label']  = "<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
								$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
								$inputs[$coluna['Field']]['label'] .="</label>";
								$inputs[$coluna['Field']]['input']  = "<input class='form-control default_input' type='number' step='$this->step' min='0' value='{$registro[$coluna['Field']]}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}'  placeholder='{$coluna['Field']}'>";
								break;

								case 'varchar':
								switch ($coluna['Field']) {
									case 'email':
									$inputs[$coluna['Field']]['label']  = "<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
									$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
									$inputs[$coluna['Field']]['label'] .= "</label>";
									$inputs[$coluna['Field']]['input']  = "<input class='form-control default_input' type='email' value='{$registro[$coluna['Field']]} ' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' placeholder='{$coluna['Field']}'>";
									break;
									case 'password':
									case 'senha':
									$inputs[$coluna['Field']]['label']  = "<label for='$this->tableName:{$coluna['Field']}' class='capital'>";
									$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
									$inputs[$coluna['Field']]['label'] .= "</label>";
									$inputs[$coluna['Field']]['input']  = "<input class='form-control default_input' type='password' value='{$registro[$coluna['Field']]}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' placeholder='{$coluna['Field']}'>";
									break;
									default:
									$campo = $registro[$coluna["Field"]];
									if ($this->Util->isFileInput($coluna['Field'])) {
										$existe_arquivo = (file_exists("./$this->tableName/uploads/{$registro[$coluna['Field']]}") && $registro[$coluna['Field']]!= null);

									 // ($existe_arquivo == true) ? "sim":"nao";
										$inputs[$coluna['Field']]['label']  = "<label  for='$this->tableName:{$coluna['Field']}' class='capital'>";
										$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
										$inputs[$coluna['Field']]['label'] .= "</label>";
										$inputs[$coluna["Field"]]['input'] ="";
										if ($existe_arquivo){
											$extensao = strtolower($this->Util->getFileExtension($campo));
											$inputs[$coluna["Field"]]['input'] .="<div >";
											if (in_array($extensao, Util::$array_img_extensions)) {
												$inputs[$coluna["Field"]]['input'] .="
												<div class='table-central'>
													<div class='excluir-inset' title='excluir imagem' onclick=\"mostra_input_file_form(this,'$this->tableName','{$coluna['Field']}');\" >
														<i class='fa fa-fw fa-times pull-right text-danger'></i>
													</div>
													<img style='max-height:300px; max-width:300px;' src='./$this->tableName/uploads/{$registro[$coluna['Field']]}' alt='img'>
												</div>";
												$inputs[$coluna["Field"]]['input'] .="<div id='div_{$this->tableName}_{$coluna['Field']}'";
												$inputs[$coluna["Field"]]['input'] .= $existe_arquivo==1 ?  "style='display:none'>" : " >";
												$inputs[$coluna["Field"]]['input'] .="</div>";
											}else{
												$inputs[$coluna["Field"]]['input'] .="File : <b><?php echo $campo?></b></div>";
											}
										} else{
											$inputs[$coluna["Field"]]['input'] .= "<input class='form-control default_input' type='file'  id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' placeholder='{$coluna['Field']}'>";
										}
									} else {
										$inputs[$coluna["Field"]]['label']  = "<label  for='$this->tableName:{$coluna['Field']}' class='capital'>";
										$inputs[$coluna['Field']]['label'] .= ($mask!="")? $mask : $coluna['Field'];
										$inputs[$coluna['Field']]['label'] .= "</label>";
										$inputs[$coluna['Field']]['input']  = "<input class='form-control default_input' type='text' value='{$registro[$coluna['Field']]}' id='$this->tableName:{$coluna['Field']}' name='{$coluna['Field']}' placeholder='{$coluna['Field']}'>";
									}
								}
							}
							if (isset($this->custom_inputs[$coluna['Field']]['default'])) {
								$inputs[$coluna['Field']]['input'] = $this->getInputDefaultValues($coluna['Field'],$this->custom_inputs[$coluna['Field']]['default'],$registro[$coluna['Field']],$this->custom_inputs[$coluna['Field']]['default']['_type']);

							}
						}
					}
				}
				return $inputs;
			}

			public function setInputOrder($array_order)
			{
				$new_inputs = array();
				foreach ($array_order as $key => $value) {
					$new_inputs[$value] = $this->custom_inputs[$value];
				}
				$this->custom_inputs = $new_inputs;
			}

			public function setMasks($array_masks)
			{
				foreach ($array_masks as $key => $mask) {
					$this->custom_inputs[$key]["mask"] = $mask;
				}
			}

			public function getCustomInputs()
			{
				return $this->custom_inputs;
			}

			public function setRotuloFk($inputName,$rotulo)
			{
				$this->custom_inputs[$inputName]["fkmask"] = $rotulo;
			}


			/**
			*	This method print the inputs , if $this->order is set  it will print in order. 
			*/	

			public function defineInput($html,$field)
			{
				$this->custom_inputs[$field]['input'] = $html;
			}

			/**
			*	function hideInput
			*
			*	This method prints the inputs , if $this->order is set  it will print in order. 
			*/	

			public function hideInput($field)
			{
				$this->inputs[$field]['print'] = false;
			}

			public function getInputDefaultValues($field,$defaultValues,$existingValue, $type="select")
			{

				if (!is_array($defaultValues))
					die("You must provide an array of the Default Values - setSelectInput(field,defaultValues)");
				else {
					switch ($type) {
						case 'select':
						$options="";
						$select = "<select class='form-control default_input' name='{$this->tableName}:{$field}' id='{$this->tableName}:{$field}'>";
						foreach ($defaultValues as $valor => $rotulo) {
							if ($valor !== '_type') {
								$options .= "<option value='$valor'";
								$options .= ($valor == $existingValue)?" selected" :"";
								$options .= ">$rotulo</option>";
							}
						}
						$select .= $options;
						$select  .=" </select>";
						return $select;
						break;
						
						default:
						break;
					}

				}
			}
			public function setDefaultValues($field,$defaultValues,$type="select")
			{
				$this->custom_inputs[$field]['default'] = $defaultValues;
				$this->custom_inputs[$field]['default']['_type'] = $type;

			}

			public function print_inputs($registro="", $html_before="",$html_after="")
			{
				// var_dump($this->colunas);
				// var_dump($this->inputs);
				// var_dump($this->custom_inputs);

				if ($this->custom_inputs != null) {
					foreach ($this->custom_inputs as $key => $value) {
						if ($this->inputs[$key]['print'] == true) {
							echo "<div class='ctInputGroup'>$html_before";
							echo $this->inputs[$key]['label'];
							echo (isset($this->custom_inputs[$key]['input']))? $this->custom_inputs[$key]['input'] : $this->inputs[$key]['input'];
							echo "$html_after </div>";
						}
					}
				} else {
					foreach ($this->inputs as $key => $input) {
						if ($this->inputs[$key]['print'] == true) {
							echo "<div class='ctInputGroup'>$html_before";
							echo $this->inputs[$key]['label'];
							echo $this->inputs[$key]['input'];
							echo "$html_after </div>";
						} 
					}
				}
				// var_dump($this->custom_inputs);
			}
			
			public function hasColumn($column)
			{
				if ($this->colunas) {
					foreach ($this->colunas as $key => $coluna) {
						if ($coluna['Field'] == $column) return true;
					}
				}
				return false;
			}
		}
?>
