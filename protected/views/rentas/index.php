<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
    'Rentas',
);

$this->menu=array(
	foreach( $sistemas as $system ) { 
    array('label'=>$system['sys']->nombre, 'url'=>array('index/'.$system['sys']->id)),
    }
    array('label'=>'Pausar Rentas', 'url'=>array('admin')),
);
?>
<h1>Rentas</h1>
<? foreach( $sistemas as $system ) { ?>
<div style="border:solid black; padding:10px;float:left">
<h3><?php echo $system['sys']->nombre?></h3>
<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'extra'=>$extra, 'sistema'=> $system));
if (isset($model->hora)){
echo '<div style="border:solid black; padding:10px;float:left">';
	echo 'Inició: '.$model->hora.' -----> ';
	echo 'Termina: '.$model->fin.'</br>';
	echo 'Restan: '.$extra['restante'].'</br>';
	echo 'Deve: '.$extra['costo'].'$</br>';
	echo '</div>';
	}?>
</div>
<? } ?>
