<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Control de proyetos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
  	<link rel="shortcut icon" href="./img/logos/logo.png" type="image/x-icon">
	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/materialize.min.css">
	<script src="./js/jquery-3.2.1.min.js"></script>
	<script src="./js/materialize.min.js"></script>
	<style>
		.margen-login{
			margin-top: 40px;
		}
		.imag{
			max-width: 200px;max-height: 200px;
		}
		@media only screen and (max-width: 412px){
			.imag{
				max-width: 100px;max-height: 100px;
			}
		}
	</style>
</head>
<body style="background-color: beige;">
	<main>
		<div class="row">
			<div class="col s1 m3 l4"></div>
			<div class="col s10 m6 l4 z-depth-5 margen-login" style="background-color: #fff;border-radius: 6px;">
				<div class="row">
					<div class="col s1 m1 l1"></div>
					<div class="col s10 m10 l10 center" style="margin-top: 20px;">
						<div id="loginForm">
							<img src="./img/perfiles/perfil.png" class="responsive-img imag">
							<form onsubmit="met.log(this) ;return false;">
								<div class="input-field">
									<i class="fa fa-user prefix"></i>
			          				<input type="text" id="logUsr" class="validate" onkeyup="met.setUser(this.value);">
				         			<label for="logUsr">Usuario</label>
				        		</div>
				        		<div class="input-field">
									<i class="fa fa-lock prefix"></i>
			          				<input type="password" id="logPs" class="validate" onkeyup="met.setPs(this.value);">
				         			<label for="logPs">Contraseña</label>
				        		</div>
				        		<button type="submit" class="btn blue waves-effect waves-gray" style="width: 100%;margin-top: 20px;"><i class="fa fa-home"></i> INICIAR</button>
				        		<button type="button" class="btn green waves-effect waves-gray" style="width: 100%;margin-top: 15px; margin-bottom: 20px;" onclick="met.logReg(false);"><i class="fa fa-plus"></i> CREAR</button>
							</form>
						</div>
						<div id="registerForm" style="display: none;">
							<h5 style="margin-top: 30px;">Crea tu cuanta para tener tu perfil</h5>
							<form onsubmit="met.reg();return false;" id="formReg">
								<div class="input-field">
									<i class="fa fa-user prefix"></i>
			          				<input type="text" id="l" class="validate" onkeyup="met.setnomue(this.value);">
				         			<label for="l">Nombre</label>
				        		</div>
				        		<h5 style="margin-top: 20px;">Datos de sesión</h5>
				        		<div class="input-field">
									<i class="fa fa-user prefix"></i>
			          				<input type="text" id="lg" class="validate" onkeyup="met.setusrnew(this.value);">
				         			<label for="lg">Usuario</label>
				        		</div>
				        		<div id="aviableUser" class="estatusPS"></div>
				        		<div class="input-field">
									<i class="fa fa-lock prefix"></i>
			          				<input type="text" id="s" class="validate" onkeyup="met.setpsnue(this.value);">
				         			<label for="s">Contraseña</label>
				        		</div>
				        		<div class="input-field">
									<i class="fa fa-lock prefix"></i>
			          				<input type="text" id="sd" class="validate" onkeyup="met.setreppasnue(this.value);">
				         			<label for="sd">Repite contraseña</label>
				        		</div>
				        		<div id="estatusPS" class="estatusPS"></div>
				        		<button type="submit" id="registerBtn" class="btn blue waves-effect waves-gray registerBtn" disabled="true" style="width: 100%;margin-top: 20px;"><i class="fa fa-home"></i> CREAR</button>
				        		<button type="button" class="btn red waves-effect waves-gray" id="cancelReg" style="width: 100%;margin-top: 15px; margin-bottom: 20px;" onclick="met.logReg(true);"><i class="fa fa-plus"></i> CANCELAR</button>
							</form>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</main>
	<style type="text/css">
		.verde{
			color: green;
		}
		.btn[disabled]{
			color: gray !important;
			background-color: lightblue !important;
		}
		.azul{
			color: blue;
		}
		.rojo{
			color: red;
		}
		.estatusPS{
			margin-top: 5px;
		    margin-bottom: 10px;
		    margin-left: 30px;
		    line-height: 0.9;
		    display: none;
		}
		.estatusPS p{
			margin-top: 0px;
		}
	</style>
	<script>

			var met={
				'usr':"",
				'setUser':function (valor) {
					this.usr=valor;
				},
				'ps':"",
				'setPs':function (valor) {
					this.ps=valor;
				},
				'nomue':"",
				'setnomue':function (valor) {
					this.nomue=valor;
					this.verifiReg(false);
				},
				'usrnue':"",
				'setusrnew':function (valor) {
					this.usrnue=valor;
					this.verifiUser(true);
					this.verifiReg(false);
				},
				'psnue':"",
				'setpsnue':function (valor) {
					this.psnue=valor;
					this.setreppasnue(this.reppasnue);
					this.verifiReg(false);
				},
				'continue':false,
				'reppasnue':"",
				'setreppasnue':function (valor) {
					this.reppasnue=valor;
					valora=this.psnue;
					valorb=this.reppasnue;
					numa=valora.length;
					numb=valorb.length;
					if (numb!=0) {
						if (valora!=valorb) {
							this.setHTML('#estatusPS','<p class="rojo"><i class="fa fa-times"></i> Las contraseñas no coinciden</p>');
							this.show('#estatusPS');
							this.continue=false;
							this.verifiReg(false);
						}else{
							this.setHTML('#estatusPS','<p class="verde"><i class="fa fa-check"></i> Las contraseñas coinciden</p>');
							this.show('#estatusPS');
							this.continue=true;
							this.verifiReg(false);
						}
					}else{
						this.hide('#estatusPS');
					}
				},
				'verifiReg':function (toast) {
					if (toast==undefined) {toast=false;}
					nom=true;
					usr=true;
					ps=true;
					nups=true;
					seguir=true;
					if (this.nomue=="") {nom=false;}
					if (this.usrnue=="") {nom=false;}
					if (this.psnue=="") {usr=false;}
					if (this.reppasnue=="") {nups=false;}
					///this.verifiUser(true);
					if (nom&&usr&&ps&&nups&&this.continue&&this.useraviable) {$("#registerBtn").attr('disabled', false);}else{$("#registerBtn").attr('disabled', true);seguir=false;}
					if (toast) {
						if (nom) {
							if (usr) {
								if (ps) {
									if (!nups) {this.toast('Falta repetir contraseña');seguir=false;}
								}else{this.toast('Falta Contraseña');seguir=false;}
							}else{this.toast('Falta Usuario');seguir=false;}
						}else{this.toast('Falta nombre');seguir=false;}
					}
					return seguir;
				},
				'reset':function (ret) {
					if (ret) {
						this.usrnue="";
						this.nomue="";
						this.psnue="";
						this.setreppasnue("");
						$("#aviableUser").html('');
					}	
				},
				'reg':function () {
					if (this.verifiReg(true)) {
						datos={'type':'post','url':'Inicio/reg','data':{nom:met.nomue,usr:met.usrnue,ps:met.psnue,psr:met.reppasnue},'beforeSend':function () {
							$("#registerBtn").attr('disabled', true);
						},'success':function (res) {
							r=$.parseJSON(res);
							o=r.opt;
							m=r.msg;
							c="";
							if (o==1) {
								c='verde';
								document.getElementById('formReg').reset();
								met.reset(true);
								setTimeout(function () {
									met.toast('Ahora inicia sesión','verde');
									document.getElementById('cancelReg').click();
								} , 2000);
							}else{
								c='rojo';
							}
							met.toast(m,c);
							$("#registerBtn").attr('disabled', false);
						},'error':function () {
							$("#registerBtn").attr('disabled', false);
						}};
						this.ajax(datos);
					}	
				},
				'show':function (id) {
					$(id).show();
				},
				'hide':function (id) {
					$(id).hide();
				},
				'setHTML':function (id,con) {
					$(id).html(con);
				},
				'falta':"",
				'valLog':function () {
						complet=true;
						if (this.usr=="") {this.falta='Falta Usuario';complet= false;}
						if(complet){if (this.ps=="") {this.falta='Falta Contraseña'; complet=false;}}
						return complet;
					},
				'log':function (form) {
					if (this.valLog()==true) {
						datos={'type':'post','url':'Inicio/log','data':{usr:met.usr,ps:met.ps},'beforeSend':function () {
							met.toast('Verificando','azul');
						},success:function (res) {
							r=$.parseJSON(res);
							o=r.opt;
							m=r.msg;
							c="";
							if (o==1) {
								c='verde';
								setTimeout(function() {window.location.reload();}, 2000);
							}else{
								c='rojo';
							}
							met.toast(m,c);
						}};
						this.ajax(datos);
					}else{
						this.toast(this.falta,'rojo');
					}
				},
				'ajax':function (datos) {
					try {
						$.ajax(datos);
					} catch(e) {
						console.log(e);
					}
				},
				'toast':function (texto,color) {
					if (typeof texto=='undefined') {
						texto='Algo pasa.';
					}
					switch (color) {
						case 'rojo':
							Materialize.toast(texto, 4000, 'rounded red');
							break;
						case 'azul':
							Materialize.toast(texto, 4000, 'rounded blue');
							break;
						case 'verde':
							Materialize.toast(texto, 4000, 'rounded green');
							break;
						default:
							Materialize.toast(texto, 4000, 'rounded');
							break;
					}
				},
				'logReg':function (act) {
					if (act) {
						$("#registerForm").hide('slow');
						$("#loginForm").show('slow');
					}else{
						$("#loginForm").hide('slow');
						$("#registerForm").show('slow');
					}
				},
				'useraviable':false,
				'verifiUser':function (show) {
					show=true;
					usr=this.usrnue;
					lon=usr.length;
					if (lon<4) {
						if (lon==0||usr=="") {$("#aviableUser").html('');$("#aviableUser").css('display', 'none');}else{
						$("#aviableUser").html('<p style="color:red;"><i class="fa fa-times"></i> El nombre de usuario requiere como mínimo 4 carácteres</p>');
						$("#aviableUser").css('display', 'inline');
					}
						this.useraviable=false;
					}else{
						aviable=false;
						datos={'type':'post','url':'Inicio/verU','data':{usr:usr},'beforeSend':
						function () {
							if (show) {
								$("#aviableUser").html('<p style="color:blue;"><i class="fa fa-refresh fa-pulse"></i> Verificando...</p>');
							}						
						},'success':
						function (dat) {
							try {
								r=$.parseJSON(dat);
								m=r.m;
								o=r.o;
								c=r.c;
								i=r.i;
								if (o==1) {met.useraviable=true;}else{met.useraviable=false;}
								setTimeout(function() {$("#aviableUser").html('<p style="color:'+c+';"><i class="fa '+i+'"></i> '+m+'</p>');met.verifiReg(false);}, 2000);
							} catch(e) {
								console.log(e);
							}
						}
						}
						this.ajax(datos);
					};
				}
			};

	</script>
</body>
</html>