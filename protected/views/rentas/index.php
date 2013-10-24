<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
    'Rentas',
);

$this->menu=array(
    array('label'=>'Terminar Rentas', 'url'=>array('index')),
    array('label'=>'Pausar Rentas', 'url'=>array('admin')),
);
?>
<h1>Rentas</h1>
<div style="border:solid black; padding:10px;float:left">
<h3><?php echo $extra['sistema']?></h3>
<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'extra'=>$extra));
if (isset($model->hora)){
echo '<div style="border:solid black; padding:10px;float:left">';
	echo 'Inició: '.$model->hora.' -----> ';
	echo 'Termina: '.$model->fin.'</br>';
	echo 'Restan: '.$extra['restante'].'</br>';
	echo 'Deve: '.$extra['costo'].'$</br>';
	}?>
	</div>
</div>
