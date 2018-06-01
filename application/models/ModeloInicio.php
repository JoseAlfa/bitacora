<?php
class ModeloInicio extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	public function getDate(){
		$this->db->select('day(now()) d,dayname(now()) dn,month(now()) m,monthname(now()) mn,year(now()) y,now() now');
		$res=$this->db->get();
		return $res->result();
	}
	public function saveUser($per,$user){
		$res=array('opt'=>1,'msg'=>'Registro Exitoso');
		$this->db->trans_begin();
		if($this->db->insert('persons',$per)){
			$user['idpersona']=$this->db->insert_id();
			if ($this->db->insert('usrs',$user)) {
				$this->db->trans_commit();
				#$this->db->trans_rollback();
			}else{
				$re['opt']=2;
				$res['msg']='No se puedo guardar Persona';
				$this->db->trans_rollback();
			}
		}else{
			$res['opt']=2;
			$res['msg']='No se puedo guardar Persona';
			$this->db->trans_rollback();
		}
		return $res;
	}
	public function verU($usr){
		$res=$this->db->get_where('usrs',array('usr'=>$usr));
		return $res->result();
	}
	public function log($usr,$ps){
		$this->db->select('p.idpersona idp,p.nombre nom,p.apellidos ap, u.idusr idu,u.idrol idr');
		$this->db->from('usrs u');
		$this->db->join('persons p','p.idpersona=u.idpersona');
		$this->db->where('u.usr',$usr);
		$this->db->where('u.psw',$ps);
		$res=$this->db->get();
		return $res->result();
	}
	public function userdata($idu)	{
		$this->db->select('p.idpersona idp,u.idusr idu,p.nombre nom,p.apellidos ap,p.correo cor,p.telefono tel,u.usr usr,u.freg freg,u.referencia ref,p.foto fot');
		$this->db->from('usrs u');
		$this->db->join('persons p','p.idpersona=u.idpersona');
		$this->db->where('u.idusr',$idu);
		$res=$this->db->get();
		return $res->result();
	}
	public function proyectos($idu){
		$this->db->select('p.idproj idpro,p.nombre nom,p.descripcion des,p.fini ini');
		$this->db->from('projectusr pu');
		$this->db->join('projects p','pu.idproj=p.idproj');
		$this->db->join('usrs u','u.idusr=pu.idusr');
		$this->db->where('u.idusr',$idu);
		$res=$this->db->get();
		return $res->result();
	}
	public function socios($id)	{
		$this->db->select('p.idproj idpro,p.nombre nom,p.descripcion des,p.fini ini');
		$this->db->from('projectusr pu');
		$this->db->join('projects p','pu.idproj=p.idproj');
		$this->db->join('usrs u','u.idusr=pu.idusr');
		$this->db->where('u.idusr',$id);
		$res=$this->db->get();
		return $res->result();
	}
	/*******Guardar nuevopryecto********/
	public function newPro($pro,$prou){
		$res=array('o'=>1,'m'=>'Se guardó correctamente');
		$this->db->trans_begin();
		if($this->db->insert('projects',$pro)){
			$prou['idproj']=$this->db->insert_id();
			if ($this->db->insert('projectusr',$prou)) {
				$this->db->trans_commit();
				#$this->db->trans_rollback();
			}else{
				$re['o']=2;
				$res['m']='No se puedo guardar.';
				$this->db->trans_rollback();
			}
		}else{
			$res['o']=2;
			$res['m']='No se puedo guardar Proyecto';
			$this->db->trans_rollback();
		}
		return $res;
	}
	public function avances($idpro,$ida){
		$this->db->select('a.idavance ida,a.detalle deta,a.fmake f,a.img img,a.error er,a.resuelto re,p.nombre nom,p.apellidos ap,p.correo cor,p.telefono tel,p.foto foto,pr.nombre pro,pr.descripcion despro,date(pr.fini) proin ,pu.owner du,date(a.fmake) fh,day(a.fmake) day,u.idusr idu,pu.idprou prou');
		$this->db->from('projectusr pu');
		$this->db->join('projects pr','pu.idproj=pr.idproj');
		$this->db->join('usrs u','u.idusr=pu.idusr');
		$this->db->join('avances a','a.idprou=pu.idprou');
		$this->db->join('persons p','u.idpersona=p.idpersona');
		$this->db->where('pr.idproj',$idpro);
		if ($ida!=0) {
			$this->db->where('a.idavance',$ida);
		}		
		$res=$this->db->get();
		return $res->result();
	}
	public function prou($pro,$usr){
		$this->db->select('pu.idprou id');
		$this->db->from('projectusr pu');
		$this->db->where('pu.idproj',$pro);
		$this->db->where('pu.idusr',$usr);
		$res=$this->db->get();
		return $res->result();
	}
	public function newav($datos){
		return $this->db->insert('avances',$datos);
	}
}