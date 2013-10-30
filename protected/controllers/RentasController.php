<?php

class RentasController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @return array action filters
	 */
	 private $id_equipo;
	 private $equipo = new Equipo;
	 private $equipos = array();
	 private $model = new RentaForm;
	 
	 private function inicializar( $id )
	 {
	 	$this->id_equipo = $id;	
	 	foreach(Sistema::model()->findAll() as $system)
			$this->equipos[$system->id] = $system;
		$this->equipo = $equipos[$id];
		$this->model->equipo = $id;
	 }
	 
	public function actionIndex()
    {
		
    }
    
    public function actionRealizar ( $id = 1 ){
       	$this->inicializar( $id );
		if(isset($_POST['RentaForm'])){
			$this->model->attributes = $_POST['RentaForm'];
			switch ( $this->model->accion ){
			case 'Iniciar': $this->iniciar();
				break;
			case 'Detener': $this->detener();
				break;
			case 'Agregar': $this->agregar();
				break;
			case 'Abonar': $this->abonar();
				break;
			}
			$this->equipo->save();
		}else{
			if( !$this->equipo->disponible ){
				$this->model->pago = $this->equipo->pagado;
				$this->cargarModel();
			}
		$this->render('realizar', array( 'model' => $this->model, 'sistemas' => $this->equipos, 'id' => $this->id_equipo ));
	}
		 
	private function agregar()
	{
		//$renta = new Renta();
		$renta = Renta::model()->find('equipo='.$this->id_equipo );
		$renta->tiempo += ($this->model->horas*60)+$this->model->minutos;
		if( $renta->save() ){
			$this->equipo->pagado = $this->model->pago;
			$this->cargarModel( );
		}
	}
	
	private function iniciar( )
	{
		$renta = new Renta;
		$renta->sistema = $this->id_equipo;
		$renta->hora = date("G:i");
		$renta->tiempo = ($this->model->horas*60)+$this->model->minutos;
		$renta->fecha = date("Y/n/j"); 
		if( $renta->save() ){
			$this->equipo->disponible = 0;
			$this->equipo->pagado = $this->model->pago;
			$this->cargarModel( );
		}
	}
	
	private function detener()
	{
		$this->equipo->disponible = 1;
		$this->model->accion = 'Iniciar';
	}
    
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function cargarModel( ){
		 		$renta = Renta::model()->find('equipo='.$this->id_equipo );
		 		$this->model->hora = substr($renta->hora, 0, 5);
		 		$this->model->tiempo = $renta->tiempo;
		 		$this->model->fin = strtotime ( '+'.$this->model->tiempo.' minute' , strtotime ( $this->model->hora ) ) ;
				$this->model->fin = date ('G:i', $this->model->fin );
				$this->model->restante = $this->restante($this->model->fin);
				($this->model->pago) ? $this->model->costo = 0 : $this->model->costo = $this->model->tiempo * 0.2;
		 		$this->model->hora = $this->to12h($model->hora);
		 		$this->model->fin = $this->to12h($model->fin);
		 		$this->model->accion='Detener';
		 		else $this->model->accion = 'Iniciar';
		 		
	}
	
	function to12h( $hora ) {
    	return date("g:i", strtotime( $hora ));
}
	
	public function restante( $final ){
		$actual = date( "G:i" );
		$datetime1 = new DateTime( $actual );
		$datetime2 = new DateTime( $final );
		if ( $datetime1 >= $datetime2 ) {
			return 0;
		}
		$interval = $datetime1->diff($datetime2);
		if ($interval->format('%h') == 0 ) 
			return $interval->format('%i');
		return $interval->format('%h hrs %i');
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
				'actions'=>array('admin','delete','listar','realizar'),
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
		$model=new Renta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Renta']))
		{
			$model->attributes=$_POST['Renta'];
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

		if(isset($_POST['Renta']))
		{
			$model->attributes=$_POST['Renta'];
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
	public function actionListar()
	{
		$dataProvider=new CActiveDataProvider('Renta');
		$this->render('listar',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Renta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Renta']))
			$model->attributes=$_GET['Renta'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Renta the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Renta::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Renta $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='renta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
