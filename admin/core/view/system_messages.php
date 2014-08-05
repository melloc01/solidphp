<div id="ctSysMsg" class="ctSysMsg">
	<?php  
	if ( isset($_SESSION['mysql_error'])) {?>
	<div class="alert text-center alert-warning   " > 
		<small>	<?php echo "Errcode : <b>{$_SESSION["mysql_error"][1]}</b> <br> {$_SESSION["mysql_error"][2]}"?></small>
	</div>
	<?	} else {
		if (isset($_SESSION["system_info"])) {?>
			<div class="alert text-center   alert-info" > 
				<?php echo $_SESSION["system_info"] ?>
			</div>
		<? 
			unset($_SESSION['system_info']);
		}

		if (isset($_SESSION["system_warning"])) {?>
			<div class="alert text-center   alert-warning" > 
				<?php echo $_SESSION["system_warning"] ?>
			</div>
		<? 
			unset($_SESSION['system_warning']);
		}

		if (isset($_SESSION["system_success"])) {?>
			<div class="alert text-center   alert-success" > 
				<?php echo $_SESSION["system_success"] ?>
			</div>
		<? 
			unset($_SESSION['system_success']);
		}

		if (isset($_SESSION["system_danger"])) {?>
			<div class="alert text-center   alert-danger" > 
				<?php echo $_SESSION["system_danger"] ?>
			</div>
		<? 
			unset($_SESSION['system_danger']);
		}
	}
	if (isset($_SESSION['mysql_error'])) {
		unset($_SESSION["mysql_error"]);	
	}
	if (isset($_SESSION['system_message'])) {?>
	<div class="alert alert-warning   text-center" > 
		<b><small><?php echo $_SESSION['sys_msg']?></small></b>
	</div>
	<?
	unset($_SESSION['sys_msg']);
} 

?>
</div>