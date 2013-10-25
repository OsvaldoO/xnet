<?php

/**
 * RentaForm class.
 * RentaForm is the data structure for keeping
 *  renta form data. It is used by the 'contact' action of 'SiteController'.
 */
class RentaForm extends CFormModel
{
	public $tiempo;
	public $pago;
	public $fin;
	public $sistema;
	public $minutos;
	public $horas;
	public $hora;
	public $accion;
	public $costo;
	public $restante;
	

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			//array(', 'required'),
			array('pago fin sistema accion horas minutos restante costo', 'safe')
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'horas'=>'Hs',
			'minutos'=>'Ms',
			'pago'=>'$',
		);
	}
}