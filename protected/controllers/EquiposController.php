<?php

class EquiposController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
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
		$sistemas = array();
    	$equipo = new Equipo;
		 foreach(Equipo::model()->findAll() as $system)
		 		$sistemas[$system->id] = $system;
       	$model = new RentaForm;
			if(isset($_POST['RentaForm'])){
				$model->attributes=$_POST['RentaForm'];
		 	 	if($model->accion == 'Iniciar' ){
		 	 		$renta = new Renta;
		 	 		$renta->equipo = $id;
		 	 		$renta->usuario = 'osval';
					$renta->hora = date("G:i");
					$renta->tiempo = ($model->horas*60)+$model->minutos;
					$renta->fecha = date("Y/n/j"); 
					var_dump($renta);
				echo '';
					if( $renta->save() ){
							$sistemas[$id]->disponible = 0;
							$sistemas[$id]->pagado = $model->pago;
						}
						$model = $this->cargarModel( false, $model, $id );
			}
			else if($model->accion == 'Detener' ) { 
			$sistemas[$id]->disponible = 1;
			$model->accion = 'Iniciar';
			}
			$equipo = $sistemas[$id];
			$equipo->save();
			$this->render('index', array( 'model' => $model, 'sistemas' => $sistemas, 'id'=>$id ));
		}
		else{
				$model->pago = $sistemas[$id]->pagado;
				$model = $this->cargarModel( $sistemas[$id]->disponible, $model, $id);
				var_dump($model);
				echo '';
				$this->render('index', array( 'model' => $model, 'sistemas' => $sistemas, 'id'=>$id ));
			}
	}
	
	public function cargarModel( $disponible, $model, $id ){
		if(!$disponible){
		 		$renta = Renta::model()->find('equipo='.$id );
		 		$model->equipo = $renta->equipo;
		 		$model->hora = substr($renta->hora, 0, 5);
		 		$model->tiempo = $renta->tiempo;
		 		$model->fin = strtotime ( '+'.$model->tiempo.' minute' , strtotime ( $model->hora ) ) ;
				$model->fin = date ('G:i', $model->fin );
				$model->restante = $this->restante($model->fin);
				($model->pago) ? $model->costo = 0 : $model->costo = $model->tiempo * 0.2;
				$model->hora = $this->to12h($model->hora);
		 		$model->fin = $this->to12h($model->fin);
		 		$model->accion='Detener';
		 		}
		 		else $model->accion = 'Iniciar';
		 		return $model;
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
