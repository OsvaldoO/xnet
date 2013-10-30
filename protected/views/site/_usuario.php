<div class="form right">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'rentaForm-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>
    <?php echo CHtml::errorSummary($model); ?>

  <div class="row">
		<?php echo $form->labelEx($model,'clave'); ?>
		<?php echo $form->textField($model,'clave',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'clave'); ?>
	</div>

    <div class="row submit">
         <?php echo CHtml::submitButton('Cambiar Usuario'); ?>
    </div>
 

</div><!-- form -->
<?php $this->endWidget(); ?>
