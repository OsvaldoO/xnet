<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'rentaForm',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>
    <?php echo CHtml::errorSummary($model); ?>
        <?php echo CHtml::activeNumberField($model,'horas', array( 'step'=>1,  'class' => 'hide', 'value'=>0, 'min'=>0, 'max'=>5 )) ?>

        <?php echo CHtml::activeNumberField($model,'minutos', array( 'step'=>15,  'class' => 'hide', 'value'=>($tipo == 'pc')?0:30, 'min'=>0, 'max'=>45 )) ?>

    <?php echo CHtml::activeHiddenField($model,'equipo'); ?>
     <?php echo CHtml::activeHiddenField($model,'costo'); ?>
    <?php echo CHtml::activeHiddenField($model,'accion', array ( 'value' => $model->accion ) ); ?>

    <?php echo CHtml::activeCheckBox($model,'pago', array( 'class'=> 'hide' )); ?>

    <div class="row submit">
         <?php echo CHtml::submitButton($model->accion, array ('id'=>'action', 'class' => '')); ?>
    </div>
 

</div><!-- form -->
<?php $this->endWidget(); ?>




