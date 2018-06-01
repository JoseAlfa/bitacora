var baseurl="./";
function toastRed(text) {
	Materialize.toast('<i class="fa fa-times"></i> '+text, 4000, 'rounded red');
}
function toastblue(text) {
	Materialize.toast('<i class="fa fa-info-circle"></i> '+text, 4000, 'rounded blue lighten-1');
}
function toastgreen(text) {
	Materialize.toast('<i class="fa fa-check-circle"></i> '+text, 4000, 'rounded green');
}
function logIn(form) {
	usr=$("#user_name").val();
	ps=$("#password").val();
	hacer=false;
	$.ajax({
		type:"post",
		url:baseurl+'Inicio/logIn',
		data:{'usr':usr,'ps':ps},
		beforeSend:function() {
			$("#action-content").hide('fast');
			$("#action-loading").show('fast');
		},
		success:function(dat){
			try {
				res=$.parseJSON(dat);
				opt=res.opt;
				msg=res.msg;
				load=res.data;
				nom=res.nom;
				if (opt==1) {
					toastRed(msg);
					hacer=true;
				}else{
					toastgreen(msg);
					$("#action-content").load(baseurl+load,{
					param1: "value1", param2: "value2"} ,
						function(){
							$("#user_name_text").html('<a href="#modal1" id="user_name_ref"><i class="fa fa-user"></i> '+nom+'</a>');
							getPerfil();
							setTimeout(function() {
								$("#action-loading").hide('fast');
								$("#action-content").show('fast');
							}, 1000);						
					});
					
				}
			} catch(e) {
				hacer=true;
				toastRed('El servidor no está respondiendo');
				console.log(e);
			}
			
			setTimeout(function() {
				if (hacer) {
					$("#action-loading").hide('fast');
					$("#action-content").show('fast');
				}				
			}, 1000);	
		},
		error:function (a,b,c,d) {
			$("#action-loading").hide('fast');
			$("#action-content").show('fast');
			alert('Ocurrió un error.<br>error: '+c+'.');
			return false;
		}
	});
}

function insertUser(a,e,b,t){
	if (a==undefined||a==''||e==undefined||e==''||b==undefined||b=='') {
		return false;
	}
	b="#"+b;
	try {
		$.ajax({
	        type:"post",
	        data:$(a).serialize(),
	        url:baseurl+'Inicio/'+e,
	        beforeSend:function() {
	            $(b).attr('disabled', true);
	            $(b).html('<i class="fa fa-refresh fa-pulse"></i> Espere');
	        },
	        success:function(dat){                        
	            setTimeout(function() {
	            	try {
	            		d=$.parseJSON(dat);
	            		opt=d.opt;
	            		msg=d.msg;
	            		reg=d.reg;
	            		if (opt==1) {
	            			toastgreen(msg);
	            			if (reg) {
		            			canSendMsg();
		            		}
	            		}else{
	            			toastRed(msg);
	            		}
	            		
	            	} catch(e) {
	            		toastRed('Respuesta inesperada');
	            		console.log(e);
	            	} 
	                $(b).html('<i class="fa fa-save"></i> '+t);
	                $(b).attr('disabled', false);
	            }, 2000);                                       
	        },
	        error:function (a,b,c,d) {
	            $(b).html('<i class="fa fa-save"></i> '+t);
	            $(b).attr('disabled', false);
	        }
	    });
	} catch(e) {
		// statements
		console.log(e);
	}
	
}