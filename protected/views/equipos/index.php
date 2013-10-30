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
<h1><?php echo $sistemas[$id]->nombre?></h1>

<div size='15' style="border:solid black; padding:0px;float:left;"><font size='15' id='horas' onclick="aumentaHoras()" > <?php echo ($model->horas)?$model->horas:0?></font>:<font size = '15' id='minutos' onclick='aumentaMinutos()'><?php echo($model->minutos)?$model->minutos:30?></font> </div>
<div style="border:solid black; padding:10px;float:left;">

<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'sistema'=> $sistemas[$id]));
if (!$sistemas[$id]->disponible){
	$color = ($model->restante === 0)?'background-color:orange':'';
echo '<div style="border:solid black; padding:10px;float:left;'.$color.';">';
	echo 'Inició: '.$model->hora.' -----> ';
	echo 'Termina: '.$model->fin.'</br>';
	echo 'Restan: '.$model->restante.' minutos</br>';
	echo 'Deve: <h2 id="costo">'.$model->costo.'$</h2></br>';
	echo '</div>';
	}?>
</div>
 
 <script>
function aumentaHoras()
{
var horas = document.getElementById("RentaForm_horas");
var horas_view = document.getElementById("horas");
horas.value = parseInt(horas.value) + 1;
if( horas.value >= '5' )
	horas.value = 0; 
horas_view.innerHTML = horas.value;
document.getElementById("minutos").innerHTML = '00';
document.getElementById("RentaForm_minutos").value = 0;
}

function aumentaMinutos()
{
var minutos = document.getElementById("RentaForm_minutos");
var minutos_view = document.getElementById("minutos");
minutos.value = parseInt(minutos.value) + 15;
if( minutos.value >= '60' )
	minutos.value = '00'; 
minutos_view.innerHTML = minutos.value;
}
</script>
