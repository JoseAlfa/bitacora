<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Inicio extends CI_Controller {
	 public function __construct() {
	 	parent::__construct();
	 	$this->load->model("ModeloInicio","modelo");
	}
	public function index(){
		$id=$this->session->userdata('idu');
		if ($id!=null) {
			$datos=$this->userdata($id);
			if (is_array($datos)) {
				$datos['proy']=$this->proyectos($id);
				$datos['soci']=$this->socios($id);
			}
			$this->load->view('inicio',$datos);
		}else{
			$this->load->view('login');
		}
		
	}
	public function log(){
		$usr=$this->input->post('usr',true);
	 	$ps= $this->input->post('ps',true);
	 	$msg="";
	 	$opt=2;
	 	$seguir=true;
	 	if ($usr==""||$usr==null) {
	 		$msg='Falta Usuario';
	 		$seguir=false;
	 	}else{
	 		if ($ps==""||$ps==null) {
	 			$msg='Falta Contraseña';
	 			$seguir=false;
	 		}
	 	}
	 	if ($seguir) {
	 		if ($this->verU($usr)) {
	 			$con=$this->modelo->log($usr,$ps);
	 			if ($con!=null) {
	 				foreach ($con as $val) {
	 					$idu=$val->idu;
	 					$idp=$val->idp;
	 					$nom=$val->nom;
	 					$ap=$val->ap;
	 					$idr=$val->idr;
	 					$data=array('idu'=>$idu,'idp'=>$idp,'idr'=>$idr);
	 					$this->session->set_userdata($data);
	 					$opt=1;
	 					$msg='Bienvenido '.$nom;
	 				}
	 			}else{
	 				$msg='La contraseña no es correcta';
	 			}
	 		}else{
	 			
	 			$msg="El usuario no existe.";
	 		}
	 	}
		echo json_encode(array('opt'=>$opt,'msg'=>$msg));
	}
	public function userdata($idu){
		$con=$this->modelo->userdata($idu);
		$arr=array();
		if ($con!=null) {
			foreach ($con as $val) {
				#p.idpersona idp,u.idusr idu,p.nombre nom,p.apellidos ap,p.correo cor,p.telefono tel,u.usr usr,u.freg freg,u.referencia ref
				$nom=$val->nom;
				$ap=$val->ap;
				$idu=$val->idu;
				$idp=$val->idp;
				$cor=$val->cor;
				$tel=$val->tel;
				$usr=$val->usr;
				$freg=$val->freg;
				$ref=$val->ref;
				$fot=$val->fot;
				$arr=array('idu'=>$idu,'idp'=>$idp,'nom'=>$nom,'ap'=>$ap,'nomcom'=>$nom.' '.$ap,'cor'=>$cor,'tel'=>$tel,'usr'=>$usr,'freg'=>$freg,'ref'=>$ref,'fot'=>$fot);
			}
		}
		return $arr;
	}
	public function getPro(){
		$id=$this->session->userdata('idu');
		$m="Exitoso";$o=1;$con=true;$pro="";
		if ($id==null) {
			$m="Error en la sesión, se redireccionará.";$o=3;$con=false;$cl="red";
		}
		if ($con) {
			$pro=$this->proyectos($id);
		}
		
		echo json_encode(array('o'=>$o,'m'=>$m,'pro'=>$pro));
	}
	public function proyectos($idu)	{
		$con=$this->modelo->proyectos($idu);
		$res='<li><a href="#!">No tienes proyectos</a></li>';
		if ($con!=null) {
			$res='';
			foreach ($con as $val) {
				#p.idproj idpro,p.nombre nom,p.descripcion des,p.fini ini
				$idpro=$val->idpro;
				$nom=$val->nom;
				$des=$val->des;
				$fin=$val->ini;
				$click=$idpro.''.$idu;
				$res.='<li class="proyec" data-clickpro="'.$click.'" data-pro="'.$idpro.'"><a href="#!"><i class="fa fa-circle-o"></i>'.substr($nom, 0,20).'</a></li>';
			}
		}
		return $res;
	}
	public function socios($id)	{
		$con=$this->modelo->socios($id);
		$res='<li><a href="#!">No tienes socios</a></li>';
		if ($con!=null) {
			
		}
		return $res;
	}


	public function salir()	{
		$this->session->sess_destroy();
		echo 1;
	}
	public function reg(){
		$nom=$this->input->post('nom',true);
		$usr=$this->input->post('usr',true);
	 	$ps= $this->input->post('ps',true);
	 	$reps=$this->input->post('psr',true);
	 	$msg="";
	 	$opt=2;
	 	$seguir=true;
	 	if ($nom==""||$nom==null) {
	 		$msg="Falta Nombre";
	 		$seguir=false;
	 	}else{
	 		if ($usr==""&&$usr==null) {
	 			$msg="Falta Usuario";
	 			$seguir=false;
	 		}else{
	 			if ($ps==""||$ps==null) {
	 				$msg="Falta Contraseña";
	 				$seguir=false;
	 			}else{
	 				if ($reps==""||$reps==null) {
	 					$msg="Falta Repetir Contraseña";
	 					$seguir=false;
	 				}else{
	 					if ($ps!=$reps) {
	 						$msg="Las contraseñas no coinciden";
	 						$seguir=false;
	 					}
	 				}
	 			}
	 		}
	 	}
	 	$usrav=$this->verU($usr);
	 	if ($seguir&&(!$usrav)) {
	 		$fecha=$this->fechaDB(false);
	 		$per=array('nombre'=>$nom);
	 		$user = array('idpersona' => 0,'idrol'=>1,'usr'=>$usr,'psw'=>$ps,'freg'=>$fecha);
	 		$res=$this->modelo->saveUser($per,$user);
	 		$msg=$res['msg'];
	 		$opt=$res['opt'];
	 	} 	
	 	echo json_encode(array('opt'=>$opt,'msg'=>$msg));
	}
	public function fechaDB($normal=true,$ex=false){
		$arr=$this->modelo->getDate();
		$fecha="";
		//day(now()) d,dayname(now()) dn,month(now()) m,monthname(now()) mn,year(now()) y,now() now
		if ($normal) {
			foreach ($arr as $val) {
				$dia=$val->d;
				$nomdia=$val->dn;
				$mes=$val->m;
				$nommes=$val->mn;
				$year=$val->y;
				$fecha=$this->getDateNom($dia,$mes,$year,false);
			}
		}else{
			foreach ($arr as $val) {
				$fecha=$val->now;
			}
		}
		return $fecha;
	}
	/*Validar Usuario*/
	public function verU($usr=""){
		$fin=false;
		$ajax=false;
		$arr=array('o'=>2,'m'=>'Ya existe un Usuario con este nombre.','i'=>'fa-times','c'=>'red');
		if ($usr=="") {
			$usr=$this->input->post('usr',true);
			$ajax=true;
		}
		$con=$this->modelo->verU($usr);
		if ($con!=null) {
			$fin=true;			
		}else{
			$arr=array('o'=>1,'m'=>'Usuario disponible.','i'=>'fa-check','c'=>'green');
		}
		if ($ajax) {
			echo json_encode($arr);
		}else{
			return $fin;
		}
	}
	/*Crear fecha: Diseñado para recibir nombre de dia en Ingles,
	  el mes se recibe en nemero, se espera en una cadena, por ejemplo 01,02, etc.*/
	public function getDateNom($dia,$mes,$ano,$nomdia,$larg=false){
		$diac='';
		$dial='';
		$mesc='';
		$mesl='';
		$mesf='';
		$diaf='';
		switch ($nomdia) {
			case 'Sunday':
				$diac='Dom';
				$dial='Domingo';
				break;
			case 'Monday':
				$diac='Lun';
				$dial='Lunes';	
				break;
			case 'Tuesday':
				$diac='Mar.';
				$dial='Martes';
				break;
			case 'Wednesday':
				$diac='Mie';
				$dial='Miercoles';
				break;
			case 'Thursday':
				$diac='Jue';
				$dial='Jueves';
				break;
			case 'Friday':
				$diac='Vie';
				$dial='Viernes';
				break;
			case 'Saturday':
				$diac='Sáb';
				$dial='Sábado';
				break;
			default:
				# code...
				break;
		}
		switch ($mes) {
			case '01':
				$mesc='Ene';
				$mesl='Enero';
				break;
			case '02':
				$mesc='Feb';
				$mesl='Febrero';
				break;
			case '03':
				$mesc='Mar';
				$mesl='Marzo';
				break;
			case '04':
				$mesc='Abr';
				$mesl='Abril';
				break;
			case '05':
				$mesc='May';
				$mesl='Mayo';
				break;
			case '06':
				$mesc='Jun';
				$mesl='Junio';
				break;
			case '07':
				$mesc='Jul';
				$mesl='Julio';
				break;
			case '08':
				$mesc='Ago';
				$mesl='Agosto';
				break;
			case '09':
				$mesc='Sep';
				$mesl='';
				break;
			case '10':
				$mesc='Oct';
				$mesl='Octubre';
				break;
			case '11':
				$mesc='Nov';
				$mesl='Noviembre';
				break;
			case '12':
				$mesc='Dic';
				$mesl='Diciembre';
				break;
			default:
				# code...
				break;
		}
		if ($larg==true) {
			$diaf=$dial.' de';
			$mesf=$mesl.' de';
		}else{
			$diaf=$diac;
			$mesf=$mesc;
		}
		$fecha=$diaf.' '.$dia.' '.$mesf.' '.$ano;
		return $fecha;
	}
	/*******Acciones para los proyectos********/
	public function newPro(){
		$id=$this->session->userdata('idu');
		$nom=$this->input->post('nom',true);
		$des=$this->input->post('des',true);
		$fh=$this->input->post('fh',true);
		$m="";$o=2;$con=true;$cl="red";
		if ($id==null) {
			$m="Error en la sesión, se redireccionará.";$o=3;$con=false;$cl="red";
		}
		if ($nom==null||$nom=="") {
			$m="Falta nombre de proyecto";$con=false;
		}else{
			if ($des==null||$des=="") {
				$m="Falta descripción de proyecto";$con=false;
			}else{
				if ($des==null||$des=="") {
					$m="Falta fecha de inicio de proyecto";$con=false;
				}
			}
		}
		if ($con) {
			$pro=array('nombre'=>$nom,'descripcion'=>$des,'fini'=>$fh);
			$prou=array('idusr'=>$id,'idproj'=>0,'owner'=>1);
			$ar=$this->modelo->newPro($pro,$prou);
			if (is_array($ar)) {
				$m=$ar['m'];
				$o=$ar['o'];
			}
		}
		echo json_encode(array('o'=>$o,'m'=>$m,'c'=>$cl));
	}
	public function detpro(){
		$idu=$this->session->userdata('idu');
		$idpro=$this->input->post('id',true);
		if ($idu==null) {
			header('location: ./');
			exit();
		}
		if ($idpro==null||$idpro=="") {
			echo "Proyecto no especificado";
			exit();
		}
		$data=$this->avances($idpro);
		if (is_array($data)) {
			$data['prou']=$this->prou($idpro,$idu);
			$data['idpro']=$idpro;
			$data['click']=$idpro.''.$idu;
		}
		$this->load->view('ajax/pro',$data);
	}
	public function avances($idpro,$ida='')	{
		if ($ida=='') {
			$ida=0;
		} 
		$tabla='<tr><td colspan="4" class="center-align">No hay resultados.</td></tr>';
		$det="";
		$con=$this->modelo->avances($idpro,$ida);
		$prou=0;
		if ($ida==0) {
			if ($con!=null) {
				$tabla="";
				foreach ($con as $val) {
					#a.idavance ida,a.detalle deta,a.fmake f,a.img img,a.error er,a.resuelto re,p.nombre nom,p.apellidos ap,p.correo cor,p.telefono tel,p.foto foto,pr.nombre pro,pr.descripcion despro,pr.fini proin ,pu.owner du
					$ida=$val->ida;
					$a=$val->deta;
					$f=$val->fh;
					$d=$val->day;
					$e=$val->er;
					$prou=$val->prou;
					$nom=$val->nom.' '.$val->ap;
					$icon='<i class="fa fa-bug tooltipped t-red" data-position="right" data-delay="50" data-tooltip="Error"></i>';
					if ($e!=1) {
						$icon='<i class="fa fa-clock-o tooltipped t-green" data-position="right" data-delay="50" data-tooltip="Avance"></i>';
					}
					$arf=explode('-', $f);
					$fecha=$this->getDateNom($arf[2],$arf[1],$arf[0],$d,false);
					$tabla.='<tr class="avance" data-av="'.$ida.'" data-prou="'.$prou.'" data-pro="'.$idpro.'">
							<td>'.$icon.'</td>
							<td>'.substr($a, 0,20).'...</td>
							<td>'.$fecha.'</td>
							<td>'.$nom.'</td>
						</tr>';
				}				
			}
		}else{
			//Detalles de un solo avance
		}
		return array('tabla'=>$tabla,'det'=>$det,'prou'=>$prou);
	}
	public function prou($pro,$usr){
		if ($pro==null||$usr==null) {
			return 0;
		}
		$con=$this->modelo->prou($pro,$usr);
		$id=0;
		foreach ($con as $val) {
			$id=$val->id;
		}
		return $id;
	}
	public function newav()	{
		$idu=$this->session->userdata('idu');
		$prou=$this->input->post('prou',true);
		$tip=$this->input->post('tip',true);
		$des=$this->input->post('des',true);
		$o=2;$m="";$con=true;
		if ($idu==null) {
			$o=3;$m="Error en la sesión.";$con=false;
		}else{
			if ($prou==""||$prou==null) {
				$m="Proyecto no especificado.";$con=false;
			}else{
				if ($tip==""||$tip==null) {
					$m="No se especificó tipo.";$con=false;
				}else{
					if ($des==""||$des==null) {
						$m="Falta detalle.";$con=false;
					}
				}
			}
		}
		if ($con) {
			$datos=array('idprou'=>$prou,'detalle'=>$des,'error'=>$tip);
			if ($this->modelo->newav($datos)) {
				$o=1;$m="Su reporte ha sido guardado.";
			}else{
				$m="No pudo guardarse, intenete más tarde.";
			}
		}
		echo json_encode(array('o'=>$o,'m'=>$m));
	}
	public function detav()	{
		$idu=$this->session->userdata('idu');
		$av=$this->input->post('id',true);
		$pr=$this->input->post('pro',true);
		$o=2;$m="";$con=true;
		$det="";
		if ($idu==null) {
			$o=3;$m="Error en la sesión.";$con=false;
		}else{
			if ($av==null||$av=="") {
				$con=false;$m='No se especificó avance.';
			}else{
				if ($pr==false||$pr=="") {
					$con=false;$m="No se especificó proyecto.";
				}
			}
		}
		$res=$this->modelo->avances($pr,$av);
		if ($res!=null) {
			foreach ($res as $val) {
				$ida=$val->ida;
				$av=$val->deta;
				$a='<b>Detalle de avance: </b>'.$val->deta.'<br>';
				$f=$val->fh;
				$d=$val->day;
				$e=$val->er;
				$prou=$val->prou;
				$nom=$val->nom.' '.$val->ap;
				$arf=explode('-', $f);
				$fecha='<b>Fecha: </b>'.$this->getDateNom($arf[2],$arf[1],$arf[0],$d,false).'<br>';
				$pro=$val->pro;
				$despro=$val->despro;
				$arf=explode('-', $val->proin);
				$proin=$this->getDateNom($arf[2],$arf[1],$arf[0],$d,false);
				$cor=$val->cor;
				$tel=$val->tel;
				$btn='';
				$fn='';
				$error='<b>Tipo de reporte: </b>';
				$sel='<option value="0" selected>Avance</option><option value="1">Error</option>';
				if ($e==1) {
					$error.='Error<br>';
					$sel='<option value="0">Avance</option><option value="1" selected>Error</option>';
				}else{
					$error.='Avance<br>';
				}
				if ($val->idu==$idu) {
					$a='<div class="input-field">
				          <input placeholder="Placeholder" id="avance_des" type="text" class="validate" value="'.$av.'">
				          <label for="avance_des">Avance</label>
				        </div>';
				    $error='<div class="input-field">
							    <select id="avance_sel">'.$sel.'</select>
							    <label>Tipo de reporte</label>
							  </div>';
				    $btn='<button type="submit" class="waves-effect waves-light btn blue"><i class="fa fa-save"></i> GUARDAR</button>';
				    $fn='t=$("#avance_sel");
						d=$("#avance_des");
						btn=$("#idprou");
						if(t.val()==2||t.val()==undefined){t.focus();app.toastRed("Seleccione el tipo de avance.");return false;}
						if (d.val()==""||d.val()==undefined) {d.focus();app.toastRed("Ingrese avance.");return false;}
						datos={
						url:baseurl+"Inicio/udpav",
						type:"post",
						data:{av:id,tip:t.val(),des:d.val()},
						beforeSend:function() {
							btn.attr("disabled", true);
						},
						success:function (dat) {
							try{
								r=$.parseJSON(dat);
								o=r.o;
								m=r.m;
								if (o==1) {
									app.toastgreen(m);
									$("#modal6").modal("close");
									$(\'[data-clickpro="'.$pr.'"]\').click();
								}else{
									app.toastRed(m);
									if (o==3) {app.rec();}
								}
							}catch(e){app.toastRed("Error en el servidor");}
							btn.attr("disabled", false);
						},
						error:function (a,s,d) {
							btn.attr("disabled", false);
						}
					};
					app.pet(datos);';
				}
				$det='<div class="">
				<ul class="collapsible popout" data-collapsible="accordion">
				    <li>
				      <div class="collapsible-header"><i class="fa fa-user"></i> Usuario.</div>
				      <div class="collapsible-body"><b>Usuario quien registra:</b> '.$nom.'<br><b>Correo: </b>'.$cor.'<br><b>Telefono: </b>'.$tel.'</div>
				    </li>
				    <li>
				      <div class="collapsible-header"><i class="fa fa-book"></i> Proyecto</div>
				      <div class="collapsible-body"><b>Nombre de proyecto: </b>'.$pro.'<br><b>Descripción: </b>'.$despro.'<br><b>Fecha de Inicio: </b>'.$proin.'</div>
				    </li>
				    <li>
				      <div class="collapsible-header active"><i class="fa fa-check"></i> Avance</div>
				      <div class="collapsible-body"><form onsubmit="udpAvan('.$ida.');return false;" method="post" accept-charset="utf-8">'.$a.$fecha.$error.$btn.'</form></div>
				    </li>
				  </ul>
				  <script>
					function udpAvan(id){
						'.$fn.'
					}
				  </script>
			</div>';
			$o=1;
			}
		}else{
			$det='Parese que alo no anda bien.';
		}
		echo json_encode(array('o'=>$o,'m'=>$m,'d'=>$det));
	}
	public function udpav()	{
		$idu=$this->session->userdata('idu');
		$prou=$this->input->post('prou',true);
		$tip=$this->input->post('tip',true);
		$des=$this->input->post('des',true);
		$o=2;$m="";$con=true;
		if ($idu==null) {
			$o=3;$m="Error en la sesión.";$con=false;
		}else{
			if ($prou==""||$prou==null) {
				$m="Proyecto no especificado.";$con=false;
			}else{
				if ($tip==""||$tip==null) {
					$m="No se especificó tipo.";$con=false;
				}else{
					if ($des==""||$des==null) {
						$m="Falta detalle.";$con=false;
					}
				}
			}
		}
		if ($con) {
			$datos=array('idprou'=>$prou,'detalle'=>$des,'error'=>$tip);
			if ($this->modelo->newav($datos)) {
				$o=1;$m="Su reporte ha sido guardado.";
			}else{
				$m="No pudo guardarse, intenete más tarde.";
			}
		}
		echo json_encode(array('o'=>$o,'m'=>$m));
	}
}