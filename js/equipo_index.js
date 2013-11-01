
$( window ).load(function() {

 $('#deuda').click(function(){	
	cambiaAcc ( 'Acumular' );
 });
 
  $('#act').click(function(){
  		$('#rentaForm').submit();
  });
 
 
 $('#deuda').change(function(){
 			$('#RentaForm_costo').attr('value', $(this).attr('value') );
 });
 
   $("#pago").click(function() { 
    					$('#RentaForm_accion').attr('value', 'Pagar' );
    				 	$('#rentaForm').submit();
     					pagado( true );
        });
        
  $("#horas").click(function() { 
   if ( estado('Aumentar') ){
 			pagado( false );
 		if( $(this).html() == '3' ){
 			if( $('#minutos').html() == '00' )
 				cambiaAcc('Detener');
 		}
 	}
 	else if ( estado ('Detener') )
 			cambiaAcc ('Aumentar');
	var horas = $("#RentaForm_horas");
	horas.attr( 'value' ,parseInt(horas.attr('value') ) + 1 );
	if( horas.attr('value') >= '4' )
		horas.attr('value', 0); 
	$(this).html ( horas.attr('value'));
	$( "#minutos" ).html( '00' );
	$("#RentaForm_minutos").attr( 'value', 0 );
});

  $("#minutos").click(function() { 
  if ( estado('Aumentar') ){
 					pagado( false );
 					if( $(this).html() == '45' ){
 						if( $('#horas').html() == '0' )
 							cambiaAcc('Detener');
 						}
 			}
 	else if ( estado ('Detener') )
 			cambiaAcc ('Aumentar');
var minutos = $("#RentaForm_minutos");
minutos.attr( 'value', parseInt ( minutos.attr('value') ) + 15 );
if( minutos.attr ( 'value' ) >= '60' )
	minutos.attr( 'value', '00' ); 
$(this).html ( minutos.attr( 'value') );
});

function pagado( estado ){
			if ( estado )
 				var src = '/xnet/images/pagado.png';
 			else
 				var src = '/xnet/images/pago.png';
     			$("#RentaForm_pago").attr( "checked", estado );
            $('#pago').attr("src", src);
}

function cambiaAcc( accion ){
			$('#RentaForm_accion').attr('value', accion );
    		 var src = '/xnet/images/'+accion+'.png';
    		 $('#act').attr('src',src);
}

function estado( est ){
	  if ( $('#RentaForm_accion').attr('value' ) === est )
	  	return true;
	  return false;
}

});


