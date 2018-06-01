<div class="mar-top-20">
	<a class="btn-floating btn-md-flat waves-effect waves-light cyan right tooltipped modal-trigger" href="#modal5" data-position="left" data-delay="50" data-tooltip="Nuevo reporte"><i class="fa fa-plus"></i></a>
	<table class="highlight">
        <thead>
            <tr>
            	<th>Tipo</th>
    	        <th>Aujuste</th>
                <th>Fecha</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
        	<?php echo $tabla; ?>
        </tbody>
    </table>
</div>
<div id="modal5" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h5><i class="fa fa-plus"></i> Nuevo avnace</h5>
      <div class="row" id="detav_content">
        <form id="detav_form">
          <div class="row">
			<div class="input-field col s12">
				<i class="fa fa-gear prefix"></i>
			    <select id="detav_tip">
			    	<option value="2" disabled selected>Seleccione una opción</option>
			    	<option value="0">Avance</option>
			    	<option value="1">Error</option>
			    </select>
			    <label>Tipo de reporte</label>
			</div>
            <div class="input-field col s12">
              <i class="fa fa-list prefix"></i>
              <textarea id="detav_des" rows="1" class="materialize-textarea"></textarea>
              <label for="detav_des">Detalles del avance</label>
            </div>
          </div>
        </form>
      </div>
      <div class="center" id="detav_load"><h1 class="fa fa-refresh fa-pulse" style="font-size: 25pt;margin-top: 25px;display: none;"></h1></div>
    </div>
    <div class="modal-footer" id="detav_foot">
      <a href="#!" class="waves-effect waves-red btn btn-flat  red darken-4 modal-close"> CANCELAR</a>
      <a href="#!" class="modal-action waves-effect waves-green btn-flat blue darken-2" data-prou="<?php echo $prou; ?>" data-pro="<?php echo $idpro; ?>" data-click="<?php echo $click;?>" style="color:white;" id="idprou">CONTINUAR</a>
    </div>
</div>
<!--Modal para el detalle del reporte-->
<div id="modal6" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h6 style="margin-bottom: 15px;"><i class="fa fa-list"></i> Detalles</h6>
      <div class="row" id="detav_content">
        <form id="detav_form">
          <div class="" id="detav_div">
			
          </div>
          <div id="loaderav" class="preloader-wrapper active car1" style="display: none;">
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
        </form>
      </div>
      <div class="center" id="detav_load"><h1 class="fa fa-refresh fa-pulse" style="font-size: 25pt;margin-top: 25px;display: none;"></h1></div>
    </div>
    <div class="modal-footer" id="detav_foot">
      <a href="#!" class="waves-effect waves-red btn btn-flat  red darken-4 modal-close"> CERRAR</a>
      <!--a href="#!" class="modal-action waves-effect waves-green btn-flat blue darken-2" data-prou="<?php echo $prou; ?>" data-pro="<?php echo $idpro; ?>" data-click="<?php echo $click;?>" style="color:white;" id="idprsou">CONTINUAR</a-->
    </div>
</div>
<script>
	$(document).ready(function() {
		$('select').material_select();
		$(".avance").click(function(event) {
			id=$(this).attr('data-av');
			pro=$(this).attr('data-pro');
			$('#modal6').modal('open');
			div=$("#detav_div");
			div.html('');
			lod=$("#loaderav");
			lod.css('display', 'block');
			datos={
				url:baseurl+'Inicio/detav',
				type:'post',
				data:{id:id,pro:pro},
				beforeSend:function () {
					//
				},
				success:function (dat) {
					try{
						r=$.parseJSON(dat);
						o=r.o;
						d=r.d;
						setTimeout(function() {
							lod.css('display', 'none');
							div.html(d);
							if (o==1) {$('.collapsible').collapsible();$('select').material_select();Materialize.updateTextFields();}
						}, 1000);
					}catch(e){console.log(e);}
				},
				error:function (a,b,c) {
					app.toastRed('Error, intente más tarde.');
				}
			};
			app.pet(datos);
		});
		$("#idprou").click(function(event) {
			id=$(this).attr('data-prou');
			pro=$(this).attr('data-click');
			t=$("#detav_tip");
			d=$("#detav_des");
			btn=$("#idprou");
			if(t.val()==2||t.val()==undefined){t.focus();app.toastRed('Seleccione el tipo de avance.');return false;}
			if (d.val()==""||d.val()==undefined) {d.focus();app.toastRed('Ingrese avance.');return false;}
			datos={
				url:baseurl+'Inicio/newav',
				type:'post',
				data:{prou:id,tip:t.val(),des:d.val()},
				beforeSend:function() {
					btn.attr('disabled', true);
				},
				success:function (dat) {
					try{
						r=$.parseJSON(dat);
						o=r.o;
						m=r.m;
						if (o==1) {
							app.toastgreen(m);
							document.getElementById('detav_form').reset();
							$('#modal5').modal('close');
							Materialize.updateTextFields();
							$('[data-clickpro="'+pro+'"]').click();
						}else{
							app.toastRed(m);
							if (o==3) {app.rec();}
						}
					}catch(e){app.toastRed('Error en el servidor');}
					btn.attr('disabled', false);
				},
				error:function (a,s,d) {
					btn.attr('disabled', false);
				}
			};
			app.pet(datos);
		});
		$("#detav_form").submit(function(event) {
			event.preventDefault();
		});
	});
</script>