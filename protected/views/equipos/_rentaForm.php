<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'rentaForm'.$sistema->id.'-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>
 
    <?php echo CHtml::errorSummary($model); ?>
 
 <div class="left">
        <?php echo CHtml::activeLabel($model,'horas'); ?>
        <?php echo CHtml::activeNumberField($model,'horas', array( 'step'=>1, 'value'=>($model->horas)?$model->horas:0, 'min'=>0, 'max'=>5 )) ?>
    </div>
    
    <div class="left">
        <?php echo CHtml::activeLabel($model,'minutos'); ?>
        <?php echo CHtml::activeNumberField($model,'minutos', array( 'step'=>15,  'class' => 'hidden', 'value'=>($model->minutos)?$model->minutos:30, 'min'=>0, 'max'=>45 )) ?>
    </div>
    
    <?php echo CHtml::activeHiddenField($model,'equipo'); ?>
    <?php echo CHtml::activeHiddenField($model,'accion', array ( 'value' => $model->accion ) ); ?>

    <div class="left">
    		<?php echo CHtml::activeLabel($model,'pago'); ?>
        <?php echo CHtml::activeCheckBox($model,'pago', array( 'checked'=>false)); ?>
    </div>

    <div class="left submit">
         <?php echo CHtml::submitButton($model->accion); ?>
    </div>
 

</div><!-- form -->
<?php $this->endWidget(); ?>




