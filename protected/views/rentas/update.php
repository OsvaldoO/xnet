<?php
/* @var $this RentasController */
/* @var $model Renta */

$this->breadcrumbs=array(
	'Rentas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Renta', 'url'=>array('index')),
	array('label'=>'Create Renta', 'url'=>array('create')),
	array('label'=>'View Renta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Renta', 'url'=>array('admin')),
);
?>

<h1>Update Renta <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>