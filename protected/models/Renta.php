<?php

/**
 * This is the model class for table "tbl_rentas".
 *
 * The followings are the available columns in table 'tbl_rentas':
 * @property integer $id
 * @property integer $equipo
 * @property string $hora
 * @property string $tiempo
 * @property string $usuario
 * @property string $fecha
 */
class Renta extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_rentas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('equipo, hora, usuario, fecha', 'required'),
			array('equipo', 'numerical', 'integerOnly'=>true),
			array('tiempo', 'length', 'max'=>3),
			array('usuario', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, equipo, hora, tiempo, usuario, fecha', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'equipo' => 'Equipo',
			'hora' => 'Hora',
			'tiempo' => 'Tiempo',
			'usuario' => 'Usuario',
			'fecha' => 'Fecha',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('equipo',$this->equipo);
		$criteria->compare('hora',$this->hora,true);
		$criteria->compare('tiempo',$this->tiempo,true);
		$criteria->compare('usuario',$this->usuario,true);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Renta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
