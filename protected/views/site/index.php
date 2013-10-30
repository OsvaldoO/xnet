<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>
<?php $this->renderPartial('_usuario', array('model'=>$model) ); ?>
<font size ='6'><?php echo $usuario->nick ?></font>

<p><font size ='4'>
	<ul>
	<li>Todas las rentas Realizadas se relacionaran a este Usuario.</li>
	<li>Si no eres el Usuario esperado ingresa tu clave para identificarte.</li>
	</ul>
</font></p>


<div >
<font size="6"> Tiempo</font><font size="6" class='right'> Saldo</font><br/><div style="border:solid;background-color:#3CC"><font  size='10'  > <?php echo $extra['horas'];?></font> Horas <font size='10' ><?php echo $extra['minutos'];?></font>Minutos 
<div class= 'right'> 
<font size= '10' style="background-color:#70ED3B"><?php echo $extra['saldo'];?>$</font>
</div>
</div>

