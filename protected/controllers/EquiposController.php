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
		$this->prepararModel();
	 }
	 
	 	 private function prepararModel(){
	 	 $this->model = new RentaForm;
	 	 $this->model->equipo = $this->id_equipo;
	 	 $this->model->accion='Iniciar';
	 	 }
	 
	  private function iniciarModel(){
	 	 $this->model->accion='Iniciar';
	 	 $this->model->hora = '00:00';
		 $this->model->tiempo = '00:00';
		 $this->model->horas = '0';
		 $this->model->minutos = '00';
		 $this->model->fin = '00:00';
		$this->model->restante = '0' ;
		$this->model->deuda = '0';
		$this->model->transcurrido = '0'; 
	 }
	 
	 
	 public function actionIndex( $id=1 )
	{
		$this->inicializar( $id );
		( !$this->equipo->disponible )?$this->llenarModel( $this->getRenta( ) ) : $this->iniciarModel();
		if(isset($_POST['RentaForm'])){
			$this->model->attributes = $_POST['RentaForm'];
			switch ( $this->model->accion ){
			case 'Iniciar': $this->iniciarRenta();
				break;
			case 'Detener': $this->detenerRenta();
				break;
			case 'Aumentar': $this->agregarTiempo();
				break;
			case 'Extra': $this->agregarExtra();
				break;
				case 'Pagar': $this->pagarRenta();
				break;
			case 'Acumular': $this->acumularSaldo( $this->model->costo );
				break;
			}
			if($this->model->accion != 'Iniciar' )
				$this->model->accion = 'Aumentar';
		}
		if(!$this->equipo->disponible)
			$this->limpiarDatos();
		$this->render('index', array( 'usuario' => $this->usuario->nick, 'model' => $this->model, 'sistemas' => $this->equipos, 'id' => $this->id_equipo ));
	}
	
		private function getRenta(  ){
		$criteria = new CDbCriteria();
		$criteria->order = "fecha DESC, hora DESC";
		$criteria->condition = 'equipo='.$this->id_equipo;
 		if( ! $renta = Renta::model()->find($criteria) )
 			$this->detenerRenta();
 		else return ( $renta );
	}
	
	public function llenarModel( $renta ){
		 		$this->model->hora = substr($renta->hora, 0, 5);
		 		$this->model->tiempo = $renta->tiempo;
		 		$this->model->horas = (int)($renta->tiempo / 60);
		 		$this->model->minutos = (int)($renta->tiempo % 60);
		 		$this->model->fin= $this->incrementaHora( $this->model->hora, $this->model->tiempo );
				$this->tiempoRestante( $renta->fecha );
				$this->tiempoConsumido(); 
				$this->model->pago = ( $this->equipo->deuda < 0.5 )? true: false;
		 	}
	
	 private function incrementaHora ( $hora, $minutos ){
				$total = strtotime ( '+'.$minutos.' minute' , strtotime ( $hora ) ) ;
				return date ('G:i', $total );
	}
	
	private function iniciarRenta( )
	{
		$renta = new Renta;
		$renta->equipo = $this->id_equipo;
		$renta->hora = date("G:i");
		$renta->tiempo = ($this->model->horas*60)+$this->model->minutos;
		$renta->fecha = date("Y-n-d"); 
		$renta->usuario = $this->usuario->clave;
		if( $renta->save() ){
			$this->equipo->disponible = 0;
			$this->equipo->deuda = $renta->tiempo * $this->costo_equipo;
			$this->llenarModel( $renta );
			$this->equipo->save();
		}
	}
	
	private function limpiarDatos(){
		 $this->model->hora = $this->to12h($this->model->hora);
		 $this->model->fin = $this->to12h($this->model->fin);
		if( $this->model->minutos == 0 ) 
		$this->model->minutos = '00';
		$this->equipo->deuda = $this->redondear( $this->equipo->deuda );
		$this->model->deuda = $this->redondear( $this->model->deuda );
	}
	
	function to12h( $hora ) {
    	return date("g:i", strtotime( $hora ));
	}
	
	public function tiempoConsumido(  ){
		$actual = date( "G:i" );
		$datetime1 = new DateTime( $actual );
		$datetime2 = new DateTime( $this->model->hora );
		$interval = $datetime1->diff($datetime2);
		$minutos = (int)$interval->format('%h')*60+$interval->format('%i');
		$this->model->deuda = ( $this->model->restante === 0 )?$this->model->tiempo*$this->costo_equipo: $minutos * $this->costo_equipo;
		if ($interval->format('%h') == 0 ) 
			$this->model->transcurrido = $interval->format('%i');
		else
			$this->model->transcurrido =  $interval->format('%h <font size="6">hrs</font> %i');
	}
	
		public function tiempoRestante( $fecha_renta ){
		$actual = date( "G:i" );
		$datetime1 = new DateTime( $actual );
		$datetime2 = new DateTime( $this->model->fin );
		if ( $datetime1 >= $datetime2 || $fecha_renta !== date("Y-n-d") ) {
			$this->model->restante = 0;
			$this->model->accion='Detener';

		}
		else{
		$interval = $datetime1->diff($datetime2);
		if ($interval->format('%h') == 0 ) 
			$this->model->restante = $interval->format('%i');
		else
			$this->model->restante = $interval->format('%h <font size="6">hrs</font> %i');
			$this->model->accion='Aumentar';
		}
	}
	
	private function detenerRenta()
	{
		$this->equipo->disponible = 1;
		$this->equipo->deuda = 0;
		$this->equipo->save();
		$this->prepararModel();
		$this->iniciarModel();
	}
	
	private function pagarRenta(){
			$this->model->pago = true;
			$this->equipo->deuda = 0 ;
			$this->equipo->save();
	}
	
	private function agregarTiempo()
	{
		$tiempo = ($this->model->horas*60)+$this->model->minutos;
		$renta = $this->getRenta();
		$renta->tiempo += $tiempo;
		if( $renta->save() ){
			$this->acumularSaldo( $tiempo * $this->costo_equipo );
			$this->llenarModel( $renta );
		}
	}
	
	private function acumularSaldo( $deuda ){
		$this->equipo->deuda += floatval( $deuda );
		$this->model->pago = ( $this->equipo->deuda < 0.5 )? true: false;
		$this->equipo->save();
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
				'actions'=>array('create','update','listar'),
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
	 public function actionListar()
	{
		$dataProvider=new CActiveDataProvider('Equipo');
		$this->render('listar',array(
			'dataProvider'=>$dataProvider,
		));
	}

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
