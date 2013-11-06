<?php
/* @var $this UsersController */
/* @var $model Users */
$this->breadcrumbs=array(
    'Equipos',
);
$sistema = $sistemas[$id];
foreach($sistemas as $system){
	$this->menu[] = array('label'=>$system->nombre, 'url'=>array('/equipos/index/'.$system->id));
}
$tiempo = ($model->tiempo)?'':'hid';
$deuda = (!$sistema->disponible)?'':'hid';
?>
<div>
	<font size='10'><?php echo $sistema->nombre;?></font><font size='5' class='right'><?php echo $usuario;?></font>
</div>
<?php $img = ($model->pago)? 'pagado': 'pago'?>
<div class='span-19' >

	<div id='tiempo' class="<?php echo $tiempo; ?>">
		<font size="4" > Tiempo</font><br/>
		<span id='hora' ><b id='horas' >0</b>:<b id='minutos'><?php echo ($sistema->tipo == 'pc')?'00':'30'?></b> </span>
	</div>
	<div id='acumular' class="<?php echo $deuda; ?>">
	<i class='pesos'>$</i><input id='deuda' type='text' ></input>
	</div>
	<img id='act' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$model->accion.'.png' ; ?>" alt="action">
</div>

<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'tipo'=> $sistema->tipo));
	
	if (!$sistema->disponible) 
		$color = ($model->restante === '0<font size="6">min</font>')?'finaliza':'inicia'; 
	else $color = 'vacia';
	?>
	
<div id='tiempos' class='span-19'>
	<div class='clasic span-14'>
		<font size="4"> Inicio</font><font size="4" class='right'> Fin</font></br>
		<font  size='6'><?php echo $model->hora;?></font>
		
		<font class='right' size='6' ><?php echo $model->fin; ?></font>
	</div>
	<div  id='div_restante' class ="<?php echo $color;?> centrado span-5" > <font size='4'> Restante </font></br>
			<font size='12' ><?php echo $model->restante; ?></font>
	</div>
</div>

<div class='span-15'> 
<div id='div_trans' class="span-6">
			<font size="4"> Transcurrido </font><br/>
			<font id='' size='5'><?php echo $model->transcurrido; ?> 
 			<span class='right'><i>$</i><?php echo $model->deuda;?></span></font>
</div>
	<?php if (!$sistema->disponible){ ?>
	<div class='right'>
		<font size='10' class='right span-5' id='total' >
		<i class='pesos'>$</i><?php echo $sistema->deuda;?></font>
	</div>
		<img id='pago' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$img.'.png' ; ?>" alt="Pago"  height="65" width="65"><?php }?>
</div>

<?php 	
Yii::app()->getClientScript()->registerScriptFile("/xnet/js/equipo_index.js");
?>

