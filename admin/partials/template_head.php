<!DOCTYPE html>
<html>
<head>
  <?php  include_once(ADMIN."partials/head_includes.php");?>
</head>
<body>
  <?php
  $menu_left_model = new menul_model();
  $this->menu_left = $menu_left_model->getMenu();

  $this->printMessage(); 
  ?>

  <div class="menuLeftMobile animated">
    <div class="row">
      <div class="col-sm-12 text-center" >
        <i style='font-size:25px; color:white; cursor:pointer; margin:15px 15px 0 0' class='fa fa-arrow-circle-o-left pull-right' onmouseover="$('.menuLeftMobile').css('left','-300px');"></i>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 text-center" >
        <a href=""><h4 style="color:white;">Project</h4></a>
      </div>
    </div>
    <?
      if ($this->menu_left) {
          foreach ($this->menu_left  as $key => $item_menu){
            if ( $item_menu['fkaccess_tool'] == null || $_SESSION['admin']['access'][$item_menu['code']] ) {
              ?>
                <div>
                  <a href="<?php echo $item_menu['link']?>" title="<?php echo $item_menu['mask']?>" >
                    <i class="fa fa-<?php echo ($item_menu['icon']!="")? $item_menu['icon'] : "paperclip" ?> fa-fw"></i> <?php echo $item_menu['mask']?>
                  </a>
                </div>
              <?php 
            } 
          }
        }
  ?>
  <?php   
    if ($_SESSION['admin']['access']['_use']) {?>
      <a href="./user" title="Configurações">
        <i class="fa fa-users fa-fw"></i> Usuários
      </a>
    <?php  }
    ?>
    <?php   
    if ($_SESSION['admin']['access']['_his']) {?>
      <a href="./history" title="Configurações">
        <i class="fa fa-bar-chart-o fa-fw"></i> Histórico
      </a>
    <?php  }
    ?>
  </div>

  <div class="container-fluid">
   <div class="row">
     <div class="col-sm-12">
     </div>
   </div>
   <div class="row">
    <div class="col-sm-4 col-md-3 ctSidebar text-center">
    <div class='sidebar'>
        <div >
          <h3>
            Project
          </h3>
          <small class='esconde_mobile' id="logout">      
            Olá <i> <?php echo $_SESSION['admin']['user']['login']?></i>, 
              <?php if ($_SESSION['admin']['user']['last_access'] != null) {?>
                seu último acesso foi <?php echo $this->Util->dateFormat('d/m/y H:i:s',$_SESSION['admin']['user']['last_access']);?> 
              <?} else {?>
                esse é seu primeiro acesso.
              <?}?>
              (<a href="./login/logout">sair</a>)
          </small>
          <div id="menuToggle" onmouseover="$('.menuLeftMobile').css('left',0); ">
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
    </div>
    <div id="content" class="col-sm-8 col-md-9">