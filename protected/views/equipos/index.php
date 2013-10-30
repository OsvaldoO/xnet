<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
    'Equipos',
);

foreach($sistemas as $system){
	$this->menu[] = array('label'=>$system->nombre, 'url'=>array('/equipos/index/'.$system->id));
}
?>
<h1>Equipos</h1>
<div style="border:solid black; padding:10px;float:left;">
<h3><?php echo $sistemas[$id]->nombre?></h3>
<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'sistema'=> $sistemas[$id]));
if (!$sistemas[$id]->disponible){
	$color = ($model->restante === 0)?'background-color:orange':'';
echo '<div style="border:solid black; padding:10px;float:left;'.$color.';">';
	echo 'Inició: '.$model->hora.' -----> ';
	echo 'Termina: '.$model->fin.'</br>';
	echo 'Restan: '.$model->restante.' minutos</br>';
	echo 'Deve: '.$model->costo.'$</br>';
	echo '</div>';
	}?>
</div>
