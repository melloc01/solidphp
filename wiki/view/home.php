<link rel='stylesheet' media='screen' href='./wiki/css/wiki.css'>
<script src='./wiki/js/wiki.js'></script>
<div class="row">
	<div class="col-md-10 col-md-offset-1 ">
		<h1>Loopwork Wiki </h1>
		<div class="text-muted"><small>Last Update: 14/04/2014</small></div>
		<hr>
		<br>
		<br>
		<h3>
			FrameWork  How To:
		</h3>

		<br>
		<ul>
			<li>MySQL Table Directives
				<ul>
					<li>
						<b>Table names:</b>
						<ul>
							<li>lowercase</li>
							<li>split by underline only</li>
						</ul>
					</li>
					<br>
					<li><b>Attribute names: </b><small>used to recognize images, files and <b>foreign keys</b> </small>

						<ul>
							<li>Images / Files</li>
							<ul>
								<li><small>Your column name must finish with <b>_img</b>. Add it to your original column name. Ex.: post_img </small></li>
								<li><small>The image will be uploaded to the <b>UPLOADS</b> directory.</small></li>
							</ul>
							<ul>
								<li><small>Your column name must finish with <b>_file</b>. Add it to your original column name. Ex.: post_file </small></li>
								<li><small>The file will be uploaded to the <b>UPLOADS</b> directory.</small></li>
							</ul>
							<br>
							<li>
								Foreign Keys
								<ul>
									<li><small>
										Your attribute name <b>that makes the reference</b> to the other_table <b>must</b> be named <b>fk</b>Other_table <br>
										Example:</small>
										<ul>
											<li><small> The table <b>Comment</b> makes a reference to the table <b>Post</b>, so, <b>Comment</b> must have an attribute that is named <b>fkPost</b></small></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
						<li>
							Data Types
							<ul>
								<li>Long Text : <b>TEXT</b></li>
								<li>Small Texts : <b>VARCHAR (X)</b></li>
								<li>Integer : <b>INT (X)</b></li>
								<li>Float/Decimal : <b>DECIMAL (X,Y)</b></li>
								<li>Boolean : <b>BOOLEAN ( mysql will transform to <i>tinyint</i>)</b></li>
								<li>Date and Time : <b>TIMESTAMP</b></li>
								<li>Date ( no time ) : <b>DATE</b></li>
								<li>Time : <b>TIME</b></li>
								<li>Images : <b>VARCHAR (X)</b> <small> name extension &rarr; _img</small></li>
								<li>Files : <b>VARCHAR (X)</b> <small> name extension &rarr; _file</small></li>
							</ul>
						</li>
					</ul>
				</li>
				<br>
				<br>
			</li>
			<li>
				After doing your business logic on the Database - Let's use the <b>Builder</b> !
				<ul>
					<li>
						Using the Builder
						<ul>
							<li><small>Access it via localhost or wherever is your server ex.: <b>localhost/loopwork/admin/builder</b></small></li>
							<li><small>Extract the stuff you want to put on your menu</small></li>
							<li><small>Ok ! You're done ! You can Create/Update/Delete from this table !</small></li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
		<br>
		<br>
		<hr>
		<h3>To do</h3>
		<ul>
			<strike> <li>Upload Images & Files <small> - make framework recognize them</small></li> </strike>
			<strike><li>Make random names for images / files</li></strike> <span class="text-muted">(changed to 'table_numberOfImage.extension')</span>
			<strike><li>Unlink images/files when data relative to it is deleted from database</li></strike>
			<li><strike>Fix JS / CSS of EDITING images/files -- </strike></li>
			<li><strike>Swap images on EDIT </strike></li>
			<li><strike>Re-do Control Logic - __construct Called</strike></li>
			<li><strike>Refactor LoopControl to make it work on ADMIN and ROOT simultaneously</strike></li> 
			<li><strike>Order &rarr; Form.class </strike></li>
			<li><strike>Label &rarr; Form.class </strike></li>
			<li><strike>Give the possibility to change the Menu name of Tables and Labels of inputs</strike></li>
			<li><strike>Re-do the Logic and put Acess-Control by Tools on ADMIN</strike></li> 
			<li><strike>Types of the Database (<strike> time, timestamp,</strike> boolean, selects [by check constraint] ) </strike> </li>
			<li><strike>Create Niveis view </strike></li>
			<li><strike>CKEditor append Uploaded Images</strike></li>
			<li><strike> Generalize Appended images - Make Form.class make one for each CKEditor.instances </strike></li>
			<li>Make fkAlbum mechanism ( ajax PHPUploader ) <b><big>CURRENT</big></b></li>
			<li>Generate Only Model on builder -- make control know it</li>
			<li>Redirect $_POST inserts to any page</li>
			<li>
				Make Methods on Form.class to return Input's HTML instead of doing it on every case <br>
				This gives possibility to create an custom input w/o having to write HTML by yourself
				</li>
			<li>E-mail password.</li>
			<li>Change the Admin Template -- maybe.</li>
			<li>Resize Images <small>-  ajax Upload will handle</small></li> 
		</ul>

		<br>
		<br>

		<h3>Ideas / Stuff to improve</h3>
		<ul>
			<li>AJAX Uploads</li>
		</ul>
		<ul>
			Performance Improvements
			<li>Before offering CSS/JS get them  and minify AND GZIP</li>
			<li></li>
		</ul>
		<hr>
		<h3>Bugs</h3>
		<ul>
			<li>Builder Bug ( morador, morador_despesa tables)</li>
		</ul>
		<hr>
	</div>
</div>