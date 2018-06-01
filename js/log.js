var baseurl="./";
var app={
	toastRed:function (text) {
		Materialize.toast('<i class="fa fa-times"></i> '+text, 4000, 'rounded red');
	},
	toastblue:function (text) {
		Materialize.toast('<i class="fa fa-info-circle"></i> '+text, 4000, 'rounded blue lighten-1');
	},
	toastgreen:function (text) {
		Materialize.toast('<i class="fa fa-check-circle"></i> '+text, 4000, 'rounded green');
	},
	pet:function (datos) {
		try{$.ajax(datos);}catch(e){console.log(e);}
	},
	rec:function () {
		window.location.reload();
	},
	getPro:function () {
		datos={
			type:"post",
			url:baseurl+'Inicio/getPro',
			success:function(dat){
				try{
					r=$.parseJSON(dat);
					if (r.o==3) {
						app.rec();
					}
					pro=r.pro;
					$("#proList").html(pro);
					$(".proyec").click(function(event) {
						id=$(this).attr('data-pro');
						par={id:id};
						app.view('detpro',par);
					});
				}catch(e){console.log(e);app.toastRed('No se pudo actualizar la lista de proyectos.');}					
			},
			error:function (a,b,c,d) {
				app.toastRed('No se pudo actualizar la lista de proyectos.');
			}
		};
		app.pet(datos);
	},
	//determinar men en español
	montsp:function (month) {
		switch (month) {
			case 'January':
				month='01';
				break;
			case 'February':
				month='02';
				break;
			case 'March':
				month='03';
				break;
			case 'April':
				month='04';
				break;
			case 'May':
				month='05';
				break;
			case 'June':
				month='06';
				break;
			case 'July':
				month='07';
				break;
			case 'August':
				month='08';
				break;
			case 'September':
				month='';
				break;
			case 'October':
				month='10';
				break;
			case 'November':
				month='11';
				break;
			case 'December':
				month='12';
				break;
			default:
				break;
		}
		return month;
	},
	view:function (dr,par) {
		if (par==""||par==undefined) {par={hola:'mundo'};}
		div=$("#contenedor");
		load=$("#loader");
        div.removeClass('scale-transition');
		div.addClass('scale-transition');
        div.addClass('scale-out');
        setTimeout(function() {load.css('display', 'block');}, 100);
		
		div.load(baseurl+'Inicio/'+dr,par ,
			function(){
				setTimeout(function() {
					div.removeClass('scale-out');
		            div.addClass('scale-transition');
					load.css('display', 'none');
				}, 500);
				$('.tooltipped').tooltip({delay: 50});
				$('.modal').modal();
		});		
	}
}
function getNom() {
	$.ajax({
		type:"post",
		url:baseurl+'Inicio/getNom',
		success:function(dat){
			try{
				d=$.parseJSON(dat);
				nom=d.nom;
				perfil=d.load;
				$("#user_name_text").html('<a href="#modal1" id="user_name_ref"><i class="fa fa-user"></i> '+nom+'</a>');
				if (perfil==true) {
					getPerfil();
				}
			}catch(e){
				console.log(e);
			}			
		},
		error:function (a,b,c,d) {
			return false;
		}
	});
}
function salir() {
	$.ajax({
		type:"post",
		url:baseurl+'Inicio/salir',
		beforeSend:function() {
			$("#perfil_salir").attr('disabled', true);
			$("#perfil_load").show('fast');
		},
		success:function(dat){
			if (dat==1) {
				$("#perfil_content").html('');
					$("#perfil_load").hide('fast');
					$("#perfil_salir").attr('disabled', false);
					$('#modal1').modal('close');
				setTimeout(function() {
					window.location.reload();
				}, 1000);	
			}else{
				toastRed(dat);
				$("#perfil_load").hide('fast');
				$("#perfil_salir").attr('disabled', false);
			}								
		},
		error:function (a,b,c,d) {
			$("#perfil_load").hide('fast');
			$("#perfil_salir").attr('disabled', false);
		}
	});
}
function getPerfil() {
	$.ajax({
		type:"post",
		url:baseurl+'Inicio/getPerfil',
		success:function(dat){
			$("#perfil_content").html(dat);
			$('.collapsible').collapsible();
			Materialize.updateTextFields();						
		},
		error:function (a,b,c,d) {
			app.toastRed('Perfil no cargado');
		}
	});
}
function proyecto(li) {
	

}
$(document).ready(function() {
	$("#newpro_form").submit(function(event) {
		event.preventDefault();
		date=$("#newpro_date").val();
		fh="";
		if (date!=undefined&&date!="") {
			ar1=date.split(' ',3);
			dia=ar1[0];
			ar2=ar1[1].split(',',3);
			mes=app.montsp(ar2[0]);
			fh=ar1[2]+'-'+mes+'-'+dia;
		}
		nom=$("#newpro_nom").val();
		des=$("#newpro_des").val();
		if (nom==""||nom==undefined) {
			app.toastRed('Falta nombre de proyecto.');
			$("#newpro_nom").focus();
			return false;
		}else{
			if (des==""||des==undefined) {
				app.toastRed('Falta descripción de proyecto.');
				$("#newpro_des").focus();
				return false;
			}else{
				if (fh=="") {
					app.toastRed('Falta fecha de incio de proyecto.');
					$("#newpro_date").focus();
					return false;
				}
			}
		}
		btn=$("#newpro_save");
		datos={
			url:baseurl+'Inicio/newPro',
			type:'post',
			data:{nom:nom,des:des,fh:fh},
			beforeSend:function () {
				app.toastblue('Realizando...');
				btn.attr('disabled', true);
			},
			success:function (dat) {
				try{
					r=$.parseJSON(dat);
					if (r.o=1) {
						app.toastgreen(r.m);
						document.getElementById('newpro_form').reset();
						$('#modal4').modal('close');
						Materialize.updateTextFields();
						app.getPro();
					}else{
						app.toastRed(r.m);
						if (r.o==3) {
							app.rec();
						}
					}
				}catch(e){console.log(e+'\nRespuesta inesperada');app.toastRed('Error en petición');}
				btn.attr('disabled', false);
			},
			error:function () {
				console.log('ha ucurrido un error');
				btn.attr('disabled', false);
			}
		};
		app.pet(datos);
	});
	$("#newpro_save").click(function(event) {
		event.preventDefault();
		$("#newpro_form").submit();
	});
	$(".proyec").click(function(event) {
		id=$(this).attr('data-pro');
		par={id:id};
		app.view('detpro',par);
	});
	$('html').on('contextmenu', function(event) {
		event.preventDefault();
		/* Act on the event */
	});
});