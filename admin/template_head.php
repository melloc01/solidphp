<!DOCTYPE html>
<html>
<head>
  <?php  include_once("./head_includes.php");?>
</head>
<body>
  <?php
  $menu_left_model = new menul_model();
  $this->menu_left = $menu_left_model->getMenu();

  $this->printMessage(); 
  ?>
  <div class="container-fluid">
   <div class="row">
     <div class="col-sm-12">
     </div>
   </div>
   <div class="row">
    <div class="col-sm-3 col-md-2 sidebar  text-center">
      <div >
      <h3>
        Project
      </h3>
        <small id="logout">      Olá <?php echo $_SESSION['admin']['user']['login']?>, seu último acesso foi <?php echo $this->Util->getCurrentDate('d/m/Y \a\s h:i');?> (<a href="./?l=login&sl=logout">sair</a>)
        </small>
        <div id="menuToggle">
          <div></div>
          <div></div>
          <div></div>
        </div> 
        <nav id="menu" class='row' >
          <ul>
            <?php  
              require ADMIN.'core/view/menu_left.php'; 
            ?>
          </ul>
        </nav>
      </div> 
    </div>
    <div id="content" class="col-sm-9 col-md-10">