<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
    'Equipos',
);

foreach($sistemas as $system){
	$this->menu[] = array('label'=>$system->nombre, 'url'=>array('/equipos/index/'.$system->id));
}
?>
<div><font size='10'><?php echo $sistemas[$id]->nombre;?></font><font size='5' class='right'><?php echo $usuario;?></font></div>
<?php $img = ($model->pago)? 'pagado': 'pago'?>
<div class='span-15' >

	<div class = 'left'>
		<font size="5" > Tiempo</font><br/>
		<font size ='10' style="border:solid black;background-color:#5CCCCC;font-size:500%;"><b id='horas' >0</b>:<b id='minutos'><?php echo ($sistemas[$id]->tipo == 'pc')?'00':'30'?></b> </font>
	</div>

	<div class='right' style='position:relative;top:40%'>
	<input id='deuda' type='text' size='5' style='font-size:150%;;'></input>$</br>
	<img id='pago' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$img.'.jpg' ; ?>" alt="Pago"  height="80" width="80">

	</div>
</div>
		

<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'tipo'=> $sistemas[$id]->tipo));
if (!$sistemas[$id]->disponible){
	$color = ($model->restante === 0)?'#FF7400':'#66E275'; ?>
	
<div class='span-15'>
	<font size="5"> Inicio</font><font size ='5' style='position:relative;left:120px;'>Finaliza </font><font size="5" class='right'> Tiempo Total</font><br/>
	<div style="border:solid;background-color:#3CC">
		<font  size='6'><?php echo $model->hora;?></font>
		<font  size='6' style='position:relative;left:120px;'><?php echo $model->fin;?></font>
		<font class='right' size='5' ><?php echo ($model->horas != 0)?'<big>'.$model->horas.'</big><small>Hrs </small>':''; 
			echo '<big> '; echo ($model->minutos != 0)?$model->minutos:'0';?></big><small>Min</small></font>
	</div>
	<div class='' style="border:solid;background-color:<?php echo $color;?>">
			<font size="6"> Restan</font> 	<font size="6" class='right span-3' style='background-color:#FFD300'> Deve</font><br/>
			<font  size='10'><?php echo $model->restante;?><font size="6" > min</font></font>
			<font size='10' class='right span-3' style='background-color:#FFD300' ><?php echo $model->costo;?>$</font>
	</div>
</div>
<?php 	}?>

 
 <script>
 
   $("#pago").click(function() { 
    			if ( $('#action').attr('value' ) === 'Aumentar'){
    				if( $('#deuda').attr('value') != '' ){
					$('#RentaForm_costo').attr('value', $('#deuda').attr('value') );
    					$('#RentaForm_accion').attr('value', 'Adeudar');

    				}else
    					$('#RentaForm_accion').attr('value', 'Pagar');
    			} 
    				if( $('#deuda').attr('value') == '' ){
     			var src = '/xnet/images/pagado.jpg';
     			$("#RentaForm_pago").attr( "checked", 'true');
            $(this).attr("src", src);
            }
            $('#rentaForm').submit();
        });
        
  $("#horas").click(function() { 
   if ( $('#action').attr('value' ) === 'Aumentar'){
 					modificarPago();
 			}
var horas = $("#RentaForm_horas");
horas.attr( 'value' ,parseInt(horas.attr('value') ) + 1 );
if( horas.attr('value') >= '4' )
	horas.attr('value', 0); 
$(this).html ( horas.attr('value'));
$( "#minutos" ).html( '00' );
$("#RentaForm_minutos").attr( 'value', 0 );
});

  $("#minutos").click(function() { 
  if ( $('#action').attr('value' ) === 'Aumentar'){
 					modificarPago();
 			}
var minutos = $("#RentaForm_minutos");
minutos.attr( 'value', parseInt ( minutos.attr('value') ) + 15 );
if( minutos.attr ( 'value' ) >= '60' )
	minutos.attr( 'value', '00' ); 
$(this).html ( minutos.attr( 'value') );
});

function modificarPago(){
 			var src = '/xnet/images/pago.jpg';
     			$("#RentaForm_pago").attr( "checked", false);
            $('#pago').attr("src", src);
}
</script>
