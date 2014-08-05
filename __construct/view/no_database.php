<link rel='stylesheet'  href='./__construct/css/__construct.css'>
<script src='./__construct/js/__construct.js'></script>
<div class="container">	
	<div class="row ctDbNotFound">

		<div class="col-sm-10 col-sm-offset-1 ">
			<?php $this->printMessage()  ?>
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title">Create database</h3>
				</div>
				<div class="panel-body text-center">
					<div>
								The schema <b><code><?php echo DB_SCHEMA_NAME ?></code></b> defined  in <code>database_constants.php</code> could not be found on your database. 
					</div>
					<form action="./__construct/createSchema" method="post" accept-charset="utf-8">
						<input type="hidden" name="create_schema" value="1">
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3">
								<br>
								Create database with <code>base schemas</code>
								<br>
								<input type="text" name="db_name" value="<?php echo DB_SCHEMA_NAME ?>" placeholder="Database name" class="form-control">
								<br>
								<button type="submit" class="btn btn-success" style='padding:10px 15px;'> Create </button>
							</div>
						</div>
					</form>
				</div>
			</div>		
		</div>
	</div>
</div>
