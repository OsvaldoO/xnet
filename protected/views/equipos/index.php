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

<div style="border:solid black; padding:0px;float:left;background-color:#3CC"><font size='15' id='horas' onclick="aumentaHoras()" > <?php echo ($model->horas)?$model->horas:0?></font>:<font size = '15' id='minutos' onclick='aumentaMinutos()'><?php echo($model->minutos)?$model->minutos:30?></font> </div>
<img id='pago' src="<?php echo Yii::app()->request->baseUrl; ?>/images/pago.jpg" alt="Pago"  height="70" width="70">



<?php $this->renderPartial('_rentaForm', array('model'=>$model, 'sistema'=> $sistemas[$id]));
if (!$sistemas[$id]->disponible){
	$color = ($model->restante === 0)?'background-color:orange':'';
echo '<div style="border:solid black; padding:10px;'.$color.';">';
	echo 'Inició: '.$model->hora.' -----> ';
	echo 'Termina: '.$model->fin.'</br>';
	echo 'Restan: '.$model->restante.' minutos</br>';
	echo 'Deve: <h2 id="costo">'.$model->costo.'$</h2></br>';
	echo '</div>';
	}?>

 
 <script>
    $("#pago").click(function() { 
     			var src = '/xnet/images/pagado.jpg';
     			$("#RentaForm_pago").attr( "checked", 'true');
            $(this).attr("src", src);
        });
        
function aumentaHoras(){
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
