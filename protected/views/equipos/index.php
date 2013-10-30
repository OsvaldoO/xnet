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
<div class='span-5' ><font  size ='10' style="text-align:center;"><b id='horas' > <?php echo ($model->horas)?$model->horas:0?></b>:<b id='minutos'><?php echo($model->minutos)?$model->minutos:30?></b> </font></div>
<img id='pago' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$img.'.jpg' ; ?>" alt="Pago"  height="70" width="70">



<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'sistema'=> $sistemas[$id]));
if (!$sistemas[$id]->disponible){
	$color = ($model->restante === 0)?'#FF7373':''; ?>
	
<div class='span-15'>
	<font size="5"> Inicio</font><font size="5" class='right'> Finaliza</font><br/>
	<div style="border:solid;background-color:#3CC">
		<font  size='6'><?php echo $model->hora;?></font>
		<font class='right' size='6' ><?php echo $model->fin;?></font>
	</div>
	<div class='' style="border:solid;background-color:<?php echo $color;?>">
			<font size="6"> Restan</font> 	<font size="6" class='right' > Deve</font><br/>
			<font  size='10'><?php echo $model->restante;?>minutos</font>
			<font size='10' class='right' style='background-color:#FFFB73' ><?php echo $model->costo;?>$</font>
	</div>
</div>
<?php 	}?>

 
 <script>
    $("#pago").click(function() { 
     			var src = '/xnet/images/pagado.jpg';
     			$("#RentaForm_pago").attr( "checked", 'true');
            $(this).attr("src", src);
        });
        
  $("#horas").click(function() { 
   if ( $('#action').attr('value' ) === 'Detener'){
 					agregar();
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
  if ( $('#action').attr('value' ) === 'Detener'){
 					agregar();
 			}
var minutos = $("#RentaForm_minutos");
minutos.attr( 'value', parseInt ( minutos.attr('value') ) + 15 );
if( minutos.attr ( 'value' ) >= '60' )
	minutos.attr( 'value', '00' ); 
$(this).html ( minutos.attr( 'value') );
});

function agregar(){
		$('#action').attr('value', 'Aumentar');
 			var src = '/xnet/images/pago.jpg';
     			$("#RentaForm_pago").attr( "checked", 'false');
            $('#pago').attr("src", src);
}
</script>
