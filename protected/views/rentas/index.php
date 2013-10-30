<?php
/* @var $this RentasController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rentas',
);

$this->menu=array(
	array('label'=>'Create Renta', 'url'=>array('create')),
	array('label'=>'Manage Renta', 'url'=>array('admin')),
);
?>

<h1>Rentas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
