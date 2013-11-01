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
?>
<div><font size='10'><?php echo $sistema->nombre;?></font><font size='5' class='right'><?php echo $usuario;?></font></div>
<?php $img = ($model->pago)? 'pagado': 'pago'?>
<div class='span-15' >

	<div class = 'left'>
		<font size="5" > Tiempo</font><br/>
		<font id='tiempo' size ='10' ><b id='horas' >0</b>:<b id='minutos'><?php echo ($sistema->tipo == 'pc')?'30':'30'?></b> </font>
	</div>
		<img id='act' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$model->accion.'.png' ; ?>" alt="action">
	<div class='right'>
	<?php if (!$sistema->disponible){ ?>	<i class='pesos'>$</i><input id='deuda' type='text' ></input><?php }?>
	</div>
</div>

<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'tipo'=> $sistema->tipo));
	
	if (!$sistema->disponible) 
		$color = ($model->restante === 0)?'finaliza':'inicia'; 
	else $color = 'vacia';
	?>
	
<div class='span-15'>
	<font size="5"> Inicio</font><font size ='5' class='centrado'> Finaliza </font><font size="5" class='right'> Tiempo Total</font><br/>
	<div class='clasic'>
		<font  size='6'><?php echo $model->hora;?></font>
		<font  size='6' class='centrado'><?php echo $model->fin;?></font>
		<font class='right' size='5' ><?php echo ($model->horas != 0)?'<big>'.$model->horas.'</big><small>Hrs </small>':''; 
			echo '<big> '; echo ($model->minutos != 0)?$model->minutos:'0';?></big><small>Min</small></font>
	</div>
	<div id='<?php echo $color;?>'>
			<font size="6"> Transcurrido </font> 	<font size="6" class='right'> Restan </font><br/>
			<font  size='10'><?php echo $model->transcurrido;?><font size="6" > min</font></font>
			<font size='8' class='right' ><?php echo $model->restante;?> <font size='6'>min</font></font>
	</div>
</div>


<div class='span-15' >

	<div class = 'left'>
			<font size='8' class='clasic' >	<i class='pesos'>$</i><?php echo $model->deuda;?></font></br>
		<font size="5" > Renta </font>
	</div>

	<div class='right'>
	<?php if (!$sistema->disponible){ ?>
	<img id='pago' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$img.'.png' ; ?>" alt="Pago"  height="65" width="65"><?php }?>
		<font size='10' class='right' id='total' >	<i class='pesos'>$</i><?php echo $sistema->deuda;?></font> </font></br>
	<font size="5" class='right' > Deve</font>
	</div>
	
		
</div>

<?php 	
Yii::app()->getClientScript()->registerScriptFile("/xnet/js/equipo_index.js");
?>

