<?php
/* @var $this RentasController */
/* @var $model Renta */

$this->breadcrumbs=array(
	'Rentas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Renta', 'url'=>array('index')),
	array('label'=>'Manage Renta', 'url'=>array('admin')),
);
?>

<h1>Create Renta</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>