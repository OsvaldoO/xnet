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
		<font size ='10' style="border:solid black;background-color:#5CCCCC;font-size:500%;"><b id='horas' >0</b>:<b id='minutos'><?php echo ($sistema->tipo == 'pc')?'00':'30'?></b> </font>
	</div>

	<div class='right' style='position:relative;top:40%'>
	<?php if (!$sistema->disponible){ ?>
	<input id='deuda' type='text' size='5' style='font-size:150%;;'></input>$</br><?php }?>
	<img id='pago' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$img.'.jpg' ; ?>" alt="Pago"  height="80" width="80">

	</div>
</div>
		

<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'tipo'=> $sistema->tipo));
if (!$sistema->disponible){
	$color = ($model->restante === 0)?'#FF7600':'#36D695'; ?>
	
<div class='span-15'>
	<font size="5"> Inicio</font><font size ='5' style='position:relative;left:120px;'>Finaliza </font><font size="5" class='right'> Tiempo Total</font><br/>
	<div style="border:solid;background-color:#3CC">
		<font  size='6'><?php echo $model->hora;?></font>
		<font  size='6' style='position:relative;left:120px;'><?php echo $model->fin;?></font>
		<font class='right' size='5' ><?php echo ($model->horas != 0)?'<big>'.$model->horas.'</big><small>Hrs </small>':''; 
			echo '<big> '; echo ($model->minutos != 0)?$model->minutos:'0';?></big><small>Min</small></font>
	</div>
	<div class='' style="border:solid;background-color:<?php echo $color;?>">
			<font size="6"> Transcurrido </font> 	<font size="6" class='right span-6' style=''> Restan </font><br/>
			<font  size='10'><?php echo $model->transcurrido;?><font size="6" > min</font></font>
			<font size='8' class='right span-6' style='' ><?php echo $model->restante;?> <font size='6'>min</font></font>
	</div>
</div>


<div class='span-15' >

	<div class = 'left'>
			<font size='8' style='border:solid;background-color:#33CDC7' ><?php echo $model->deuda;?>$</font></br>
		<font size="5" > Renta </font>
	</div>

	<div class='right' style='position:relative;top:40%'>
		<font size='10' class='right' style='border:solid;background-color:#FFF700'; ><?php echo $sistema->deuda;?>$</font> </font></br>
	<font size="5" class='right' > Deve</font>
	</div>
	
		
</div>

<?php 	}
Yii::app()->getClientScript()->registerScriptFile("/xnet/js/equipo_index.js");
?>

