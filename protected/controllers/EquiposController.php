<?php

class EquiposController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	private $id_equipo;
	 private $equipo;
	 private $equipos = array();
	 private $model;
	 private $usuario;
	/**
	 * @return array action filters
	 */
	 private function inicializar( $id )
	 {
	 $this->usuario = Usuario::model()->find('estado = 1');
	 $this->equipo = new Equipo;
	 $this->id_equipo = $id;	
		foreach(Equipo::model()->findAll() as $system)
			$this->equipos[$system->id] = $system;
		$this->equipo = $this->equipos[$id];
		$this->iniciarModel();
	 }
	 
	 private function iniciarModel(){
	 	 $this->model = new RentaForm;
	 	 $this->model->equipo = $this->id_equipo;
	 }
	 
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Equipo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Equipo']))
		{
			$model->attributes=$_POST['Equipo'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Equipo']))
		{
			$model->attributes=$_POST['Equipo'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	 public function actionIndex( $id=1 )
	{
		$this->inicializar( $id );
		if(isset($_POST['RentaForm'])){
			$this->model->attributes = $_POST['RentaForm'];
			switch ( $this->model->accion ){
			case 'Iniciar': $this->iniciar();
				break;
			case 'Detener': $this->detener();
				break;
			case 'Aumentar': $this->agregar();
				break;
				case 'Pagar': $this->pagar();
				break;
			case 'Abonar': $this->abonar();
				break;
			case 'Adeudar': $this->adeudar();
				break;
				default: var_dump($this->model);
			}
		}else{
			if( !$this->equipo->disponible ){
				$this->model->pago = $this->equipo->pagado;
				$this->cargarModel();
			}
			else $this->model->accion='Iniciar';
		}
		$this->render('index', array( 'usuario' => $this->usuario->nick, 'model' => $this->model, 'sistemas' => $this->equipos, 'id' => $this->id_equipo ));
	}
	
	public function cargarModel( ){
				$criteria = new CDbCriteria();
				$criteria->order = "fecha DESC, hora DESC";
				$criteria->condition = 'equipo='.$this->id_equipo;
		 		if( ! $renta = Renta::model()->find($criteria) )
		 			$this->detener();
		 		else {
		 		$this->model->hora = substr($renta->hora, 0, 5);
		 		$this->model->tiempo = $renta->tiempo;
		 		$this->model->horas = (int)($renta->tiempo / 60);
		 		$this->model->minutos = (int)($renta->tiempo % 60);
		 		if( $this->model->minutos == 0 ) 
		 			$this->model->minutos = '00';
		 		$this->model->fin = strtotime ( '+'.$this->model->tiempo.' minute' , strtotime ( $this->model->hora ) ) ;
				$this->model->fin = date ('G:i', $this->model->fin );
				$this->restante( $renta->fecha );
				($this->model->pago) ? $this->model->costo = 0 : $this->model->costo = $this->model->tiempo * 0.2;
				//REPARAME : Tiempo se multiplica por costo de equipo
		 		$this->model->hora = $this->to12h($this->model->hora);
		 		$this->model->fin = $this->to12h($this->model->fin);
		 	}
	}
	
	function to12h( $hora ) {
    	return date("g:i", strtotime( $hora ));
}
	
	public function restante( $fecha_renta ){
		$actual = date( "G:i" );
		$datetime1 = new DateTime( $actual );
		$datetime2 = new DateTime( $this->model->fin );
		if ( $datetime1 >= $datetime2 || $fecha_renta !== date("Y-n-j") ) {
			$this->model->restante = 0;
			$this->model->accion='Detener';
		}
		else{
		$interval = $datetime1->diff($datetime2);
		if ($interval->format('%h') == 0 ) 
			$this->model->restante = $interval->format('%i');
		$this->model->restante = $interval->format('%h <font size="6">hrs</font> %i');
		$this->model->accion='Aumentar';
		}
	}
	
	private function iniciar( )
	{
		$renta = new Renta;
		$renta->equipo = $this->id_equipo;
		$renta->hora = date("G:i");
		$renta->tiempo = ($this->model->horas*60)+$this->model->minutos;
		$renta->fecha = date("Y/n/j"); 
		$renta->usuario = $this->usuario->clave;
		if( $renta->save() ){
			$this->equipo->disponible = 0;
			$this->equipo->pagado = $this->model->pago;
			$this->equipo->save();
			$this->cargarModel( );
		}
	}
	
	private function detener()
	{
		$this->equipo->disponible = 1;
		$this->iniciarModel();
		$this->model->accion = 'Iniciar';
		$this->equipo->save();
	}
	
	private function pagar(){
			$this->equipo->pagado = 1;
			$this->equipo->save();
			$this->cargarModel( );
	}
	
	private function agregar()
	{
		$renta = new Renta();
		$criteria = new CDbCriteria();
		$criteria->order = "fecha DESC, hora DESC";
		$criteria->condition = 'equipo='.$this->id_equipo;
		$renta = Renta::model()->find($criteria);
		$renta->tiempo += ($this->model->horas*60)+$this->model->minutos;
		if( $renta->save() ){
			$this->equipo->pagado = $this->model->pago;
			$this->equipo->save();
			$this->cargarModel( );
		}
	}
	
	private function adeudar(){
		
	}
	/*public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Equipo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Equipo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Equipo']))
			$model->attributes=$_GET['Equipo'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Equipo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Equipo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Equipo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='equipo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
