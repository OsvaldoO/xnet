<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<font size ='6'> Ayuda</font>
<p>
<h3>Tiempo Indefinido</h3>
	<span class='nota'>Si no te espesifican cuanto tiempo se rentara un equipo, inicia la renta con tiempo igual a 0:00. Esto iniciara una renta con tiempo indefinido; el precio se actualizara conforme pase el tiempo. Nota que por defecto las computadoras inicializan con tiempo indefinido, mientras que los Xbox lo hacen con 30 minutos</span>
</p>
<p>
<h3>Incrementar deuda</h3>
	<span class='nota'>Ingresa la cantidad en efectivo en el campo de texto y preciona el boton de accion (el cual automaticamente cambiara a $). Tambien es posible ingresar valores negativos, lo cual reducira la deuda</span>
</p>
<p>
<h3>Cancelar Renta</h3>
	<span class='nota'>Para cancelar una renta antes de que esta termine posiciona el marcador de tiempo en 0:00. Entonces aparecera el boton para cancelarla. Si te equivocastes en algo al iniciar una renta tienes 5 minutos para cancelarla antes de que esta se registre. ( No se reguistran las rentas con tiempo menor a 5 minutos transcurridos )</span>
</p>

