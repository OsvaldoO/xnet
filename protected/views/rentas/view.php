<?php
/* @var $this RentasController */
/* @var $model Renta */

$this->breadcrumbs=array(
	'Rentas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Renta', 'url'=>array('index')),
	array('label'=>'Create Renta', 'url'=>array('create')),
	array('label'=>'Update Renta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Renta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Renta', 'url'=>array('admin')),
);
?>

<h1>View Renta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'equipo',
		'hora',
		'tiempo',
		'usuario',
		'fecha',
	),
)); ?>
