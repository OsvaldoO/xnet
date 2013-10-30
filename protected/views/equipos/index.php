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
<div><font size='10'><?php echo $sistemas[$id]->nombre;?></font><font size='5' class='right'><?php echo $usuario;?></font></div>
<?php $img = ($model->pago)? 'pagado': 'pago'?>
<font  size = '15' style="border:solid black; padding:0px;float:left;background-color:#3CC"><div class='left' id='horas' > <?php echo ($model->horas)?$model->horas:0?></div>:<div class='right' id='minutos'><?php echo($model->minutos)?$model->minutos:30?></div> </font>
<img id='pago' src="<?php echo Yii::app()->request->baseUrl.'/images/'.$img.'.jpg' ; ?>" alt="Pago"  height="70" width="70">



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
        
  $("#horas").click(function() { 
var horas = $("#RentaForm_horas");
horas.attr( 'value' ,parseInt(horas.attr('value') ) + 1 );
if( horas.attr('value') >= '4' )
	horas.attr('value', 0); 
$(this).html ( horas.attr('value'));
$( "#minutos" ).html( '00' );
$("#RentaForm_minutos").attr( 'value', 0 );
});

  $("#minutos").click(function() { 
var minutos = $("#RentaForm_minutos");
minutos.attr( 'value', parseInt ( minutos.attr('value') ) + 15 );
if( minutos.attr ( 'value' ) >= '60' )
	minutos.attr( 'value', '00' ); 
$(this).html ( minutos.attr( 'value') );
});
</script>
