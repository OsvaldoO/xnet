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
	 private $costo_equipo;
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
		$costo= Costo::model()->find('clave="'.$this->equipo->tipo.'"');
		$this->costo_equipo = $costo->costo;
		$this->limpiaModel();
	 }
	 
	 private function limpiaModel(){
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
			case 'Acumular': $this->acumular( $this->model->costo );
				break;
			}
		}else{
			if( !$this->equipo->disponible ){
				if($this->equipo->deuda <= 0 )
				$this->model->pago = true;
				$this->cargarModel();
			}
			else $this->model->accion='Iniciar';
		}
		$this->limpiarValores();
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
		 		if($this->model->pago)
					$this->equipo->deuda = 0;
				else{
						if( $this->model->accion == 'Iniciar' )
							$this->equipo->deuda = $this->model->tiempo * $this->costo;
				}
		 		$this->model->horas = (int)($renta->tiempo / 60);
		 		$this->model->minutos = (int)($renta->tiempo % 60);
		 		if( $this->model->minutos == 0 ) 
		 		$this->model->minutos = '00';
		 		$this->model->fin = strtotime ( '+'.$this->model->tiempo.' minute' , strtotime ( $this->model->hora ) ) ;
				$this->model->fin = date ('G:i', $this->model->fin );
				$this->restante( $renta->fecha );
				$this->model->transcurrido = $this->consumido(); 
				$this->model->deuda = ( $this->model->restante === 0 )?$this->model->tiempo*$this->costo_equipo: $this->consumido( true ) * $this->costo_equipo;
		 		$this->model->hora = $this->to12h($this->model->hora);
		 		$this->model->fin = $this->to12h($this->model->fin);
		 	}
	}
	
	function to12h( $hora ) {
    	return date("g:i", strtotime( $hora ));
}
	

	
	public function consumido( $min = false ){
		$actual = date( "G:i" );
		$datetime1 = new DateTime( $actual );
		$datetime2 = new DateTime( $this->model->hora );
		$interval = $datetime1->diff($datetime2);
		if( $min )
			return (int)$interval->format('%h')*60+$interval->format('%i');
		if ($interval->format('%h') == 0 ) 
			return $interval->format('%i');
		return  $interval->format('%h <font size="6">hrs</font> %i');
	}
	
		public function iniciarModel( $renta ){
		 		$this->model->hora = substr($renta->hora, 0, 5);
		 		$this->model->tiempo = $renta->tiempo;
		 		if($renta->tiempo == 0 ){
		 			$this->model->tiempo = 100;
		 			$this->equipo->deuda = 0;
		 			$this->model->fin = 'Indefinido';
		 			$this->model->restante = 'Indefinido';
		 			$this->model->horas = 99;
		 			$this->model->minutos = 99;
		 		}
		 		else{
		 		$this->equipo->deuda = ($this->model->pago)? 0 : $this->model->tiempo * $this->costo_equipo;
		 		$this->model->horas = (int)($renta->tiempo / 60);
		 		$this->model->minutos = (int)($renta->tiempo % 60);
		 		$this->model->fin = strtotime ( '+'.$this->model->tiempo.' minute' , strtotime ( $this->model->hora ) ) ;
				$this->model->fin = date ('G:i', $this->model->fin );
				$this->restante( $renta->fecha );
				$this->model->transcurrido = '0' ;
				$this->model->deuda = 0;
		 		$this->model->hora = $this->to12h($this->model->hora);
		 		$this->model->fin = $this->to12h($this->model->fin);
		 		}
	}
	
		public function restante( $fecha_renta ){
		$actual = date( "G:i" );
		$datetime1 = new DateTime( $actual );
		$datetime2 = new DateTime( $this->model->fin );
		if ( $datetime1 >= $datetime2 || $fecha_renta != date("Y-n-j") ) {
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
		$renta->fecha = date("Y-n-j"); 
		$renta->usuario = $this->usuario->clave;
		if( $renta->save() ){
			$this->equipo->disponible = 0;
			$this->iniciarModel( $renta );
			$this->equipo->save();
		}
	}
	
	private function detener()
	{
		$this->equipo->disponible = 1;
		$this->equipo->deuda = 0;
		$this->limpiaModel();
		$this->model->accion = 'Iniciar';
		$this->equipo->save();
	}
	
	private function pagar(){
			$this->model->pago = true;
			$this->cargarModel( );
			$this->equipo->save();
	}
	
	private function agregar()
	{
		$tiempo = ($this->model->horas*60)+$this->model->minutos;
		$renta = new Renta();
		$criteria = new CDbCriteria();
		$criteria->order = "fecha DESC, hora DESC";
		$criteria->condition = 'equipo='.$this->id_equipo;
		$renta = Renta::model()->find($criteria);
		$renta->tiempo += $tiempo;
		if( $renta->save() ){
			$this->acumular( $tiempo * $this->costo_equipo );
			$this->cargarModel( );
			$this->equipo->save();

		}
	}
	
	private function acumular( $deuda ){
		$this->equipo->deuda += floatval( $deuda );
		if ($this->equipo->deuda <= .50 ){
			$this->model->pago =true;
			$this->equipo->deuda = 0; 
		}
		else 	$this->model->pago = false;
		$this->cargarModel( );
		$this->equipo->save();
	}
	
	private function limpiarValores(){
	$this->equipo->deuda = $this->redondear( $this->equipo->deuda );
	$this->model->deuda = $this->redondear( $this->model->deuda );
	}
	
	private function redondear( $valor ){
		$diff = floatval($valor - intval( $valor ) ); 
	if( !$diff  > 0 )
			return (int)$valor;
	else if( $diff == 0.5 )
		return (int)$valor + 0.5 ; 
		else
	 	return ( $diff > .5 )? (int)$valor+1: (int)$valor + 0.5 ; 
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
