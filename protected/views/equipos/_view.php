<?php
/* @var $this EquiposController */
/* @var $data Equipo */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipo')); ?>:</b>
	<?php echo CHtml::encode($data->tipo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disponible')); ?>:</b>
	<?php echo CHtml::encode($data->disponible); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pagado')); ?>:</b>
	<?php echo CHtml::encode($data->pagado); ?>
	<br />


</div>