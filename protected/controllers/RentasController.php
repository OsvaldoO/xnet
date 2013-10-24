<?php

class RentasController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $model;
	/**
	 * @return array action filters
	 */
	
	 public function actionIndex()
    {
     /* $form = new RentaForm;
    	$model = new CActiveForm('application.views.rentas._rentaForm', $form);
    	if($model->submitted('Iniciar') && $model->validate()){
         $this->render('index', array( 'model' => $form, 'extra' => $extra));
         }*/
		 $extra = array();
		 $sistema = Sistema::model()->findByPk(2);
		 $extra['sistema'] = $sistema->nombre;
		 $extra['id'] = $sistema->id; 
		 $this->model = new RentaForm;

		 if(isset($_POST['RentaForm']))
			{
				$this->model->attributes=$_POST['RentaForm'];
				if($this->model->accion == 'Iniciar' ){
					$this->model->hora = date("g:i");
					$this->model->tiempo = ($this->model->horas*60)+$this->model->minutos;
					$this->model->fin = strtotime ( '+'.$this->model->tiempo.' minute' , strtotime ( $this->model->hora ) ) ;
					$this->model->fin = date ('g:i', $this->model->fin );
					$this->model->accion = 'Detener';
				}
				else $this->model->accion = 'Iniciar';
				$extra['restante'] = $this->restante($this->model->fin);
 				($this->model->pago) ? $extra['costo'] = 0 : $extra['costo'] = $this->model->tiempo * 0.2;
		      $this->render('index', array( 'model' => $this->model, 'extra' => $extra));
			}
			else{
		  $this->model->accion = 'Iniciar';
		  $this->render('index', array( 'model' => $this->model, 'extra' => $extra));
		 }
    }
    
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function restante( $final ){
		$actual = date( "g:i" );
		$datetime1 = new DateTime( $actual );
		$datetime2 = new DateTime( $final );
		if ( $datetime1 > $datetime2 ) {
			$final = strtotime ( '+5 hour' , strtotime ( $final ) ) ;
	   	$final = date ('g:i', $final );
			$actual = strtotime ( '+5 hour' , strtotime ( $actual ) ) ;
			$actual = date ('g:i', $actual );
			$datetime1 = new DateTime($actual);
			$datetime2 = new DateTime($final);
		}
		$interval = $datetime1->diff($datetime2);
		return $interval->format('%h horas %i minutos');
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
				'actions'=>array('admin','delete','listar'),
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
