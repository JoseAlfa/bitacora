<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
  	<link rel="shortcut icon" href="./img/logos/logo.png" type="image/x-icon">
	<title>Control de proyectos</title>
	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/materialize.min.css">
	<script src="./js/jquery-3.2.1.min.js"></script>
	<script src="./js/materialize.min.js"></script>
	<link rel="stylesheet" href="./css/mystyle.css">
	<script src="./js/log.js"></script>
</head>
<body>
	<ul id="slide-out" class="side-nav fixed">
    	<li id="background"><div class="user-view">
    		<div class="background">
        		<img width="300px" src="./img/paisaje.png">
    		</div>
    		<a href="#!user"><img class="circle" src="./img/perfiles/<?php echo $fot; ?>"></a>
    		<a href="#!name"><span class="white-text name"><?php echo $nomcom; ?></span></a>
    		<a href="#!email"><span class="white-text email"><?php echo $cor; ?></span></a>
    	</div></li>
    	<li><a href="#!"><i class="fa fa-user"></i> Perfil</a></li>
    	<li><div class="divider"></div></li>
    	<li class="no-padding">
        	<ul class="collapsible collapsible-accordion">
          		<li>
            		<a class="collapsible-header"><i class="fa fa-terminal"></i>Mis Proyectos</a>
            		<div class="collapsible-body">
              			<ul id="proList">
                			<?php echo $proy; ?>
              			</ul>
            		</div>
          		</li>
       		</ul>
      	</li>
      	<li class="no-padding">
        	<ul class="collapsible collapsible-accordion">
          		<li>
            		<a class="collapsible-header"><i class="fa fa-address-book"></i> Mis socios</a>
            		<div class="collapsible-body">
              			<ul>
                			<?php echo $soci; ?>
              			</ul>
            		</div>
          		</li>
       		</ul>
      	</li>
    </ul>
    <main class="separate bak">
    	<div class="navbar-fixed">
    		<nav class="top-nav red darken-4">
	          		<div class="nav-wrapper">
	          			<a href="#!" class="brand-logo" style="margin-left: 10px;">Mi bitácora</a>
						<a href="#" data-activates="slide-out" class="button-collapse home right"><i class="fa fa-align-justify"></i></a>
	          		</div>
	      	</nav>
    	</div>
      <div class="row bak">
        <div class="col s12 m1"></div>
        <div class="col s12 m10" id="contenedor">

        </div>
        <div id="loader" class="preloader-wrapper active car" style="display: none;">
          <div class="spinner-layer spinner-red-only">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div><div class="gap-patch">
              <div class="circle"></div>
            </div><div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>
        </div>
    </main>
  <div id="modal1" class="modal salir">
    <div class="modal-content">
      <h5><i class="fa fa-power-off"></i> Salir</h5>
      <div class="row" id="perfil_content">
        <p>¿Seguro quiere salir?</p>
      </div>
      <div class="center" id="perfil_load"><h1 class="fa fa-refresh fa-pulse" style="font-size: 25pt;margin-top: 25px;display: none;"></h1></div>
    </div>
    <div class="modal-footer" id="perfil_foot">
      <a href="#!" class="waves-effect waves-red btn btn-flat blue modal-close"> CANCELAR</a>
      <a href="#!" class="modal-action waves-effect waves-green btn-flat red darken-4"  style="color:white;" onclick="salir()" id="perfil_salir">CONTINUAR</a>
    </div>
  </div>
  <div id="modal4" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h5><i class=""></i> Nuevo Proyecto</h5>
      <div class="row" id="newpro_content">
        <form id="newpro_form">
          <div class="row">
            <div class="input-field col s12">
              <i id="des_nom_pro" class="fa fa-heart prefix tooltipped" data-position="right" data-delay="50" data-tooltip="Elige un nombre corto."></i>
              <input id="newpro_nom" type="text" class="validate" data-length="20">
              <label id="labe_nombrepro" for="newpro_nom">Nombre del proyecto</label>
            </div>
            <div class="input-field col s12">
              <i class="fa fa-list prefix"></i>
              <textarea id="newpro_des" rows="1" class="materialize-textarea"></textarea>
              <label for="newpro_des">Detalles del proyecto</label>
            </div>
            <div class="col s12 m6 input-field">
              <i class="fa fa-calendar prefix"></i>
              <input type="text" id="newpro_date" class="datepicker">
              <label for="newpro_date" id="newpro_dateclick">Fecha de inicio</label>
            </div>
          </div>
        </form>
      </div>
      <div class="center" id="newpro_load"><h1 class="fa fa-refresh fa-pulse" style="font-size: 25pt;margin-top: 25px;display: none;"></h1></div>
    </div>
    <div class="modal-footer" id="newpro_foot">
      <a href="#!" class="waves-effect waves-red btn btn-flat  red darken-4 modal-close"> CANCELAR</a>
      <a href="#!" class="modal-action waves-effect waves-green btn-flat blue darken-2"  style="color:white;" id="newpro_save">CONTINUAR</a>
    </div>
  </div>

  <div class="fixed-action-btn">
    <a class="btn-floating btn-large red darken-4">
      <i class="fa fa-gears"></i>
    </a>
    <ul>
      <li><a class="btn-floating red darken-4 tooltipped modal-trigger" href="#modal1" data-position="left" data-delay="50" data-tooltip="Salir"><i class="fa fa-power-off"></i></a></li>
      <li><a class="btn-floating yellow darken-1  tooltipped" href="#modal1" data-position="left" data-delay="50" data-tooltip="Perfil"><i class="fa fa-user"></i></a></li>
      <li><a class="btn-floating blue  tooltipped" href="#modal1" data-position="left" data-delay="50" data-tooltip="Nuevo Socio"><i class="fa fa-user-secret"></i></a></li>
      <li><a class="btn-floating green  tooltipped" href="#modal4" data-position="left" data-delay="50" data-tooltip="Nuevo Proyecto"><i class="fa fa-plus"></i></a></li>
    </ul>
  </div>
    
</body>
<script>
	$(document).ready(function() {
		$(".button-collapse").sideNav();
    $('.modal').modal();
    $('.collapsible').collapsible();
    $('.tooltipped').tooltip({delay: 50});
    $('.datepicker').pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 20, // Creates a dropdown of 15 years to control year,
      today: 'Hoy',
      clear: 'Limpiar',
      close: 'Aceptar',
      closeOnSelect: false // Close upon selecting a date,
    });
	});
</script>
</html>