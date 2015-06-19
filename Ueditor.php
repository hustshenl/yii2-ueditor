<?php
/**
 * 
 * @author 	wenyuan 
 * Email 	liucunzhou@163.com
 * qq		1510033691
 *
 */
namespace hustshenl\ueditor;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use hustshenl\ueditor\UeditorAsset;

class Ueditor extends InputWidget
{
	/**
	 * 百度编辑器内设置的参数
	 * @var array
	 */
    public $events = [];
    public $ucontent = '';
    
    /*
     * Tag
     * @var bool
     */
    public $renderTag=true;
    
    /*
     * Initializes the widget
     */
    public function init() {
    	if(isset($this->options['ucontent'])){
    		$this->ucontent = $this->options['ucontent'];
    		unset($this->options['ucontent']);
    	}
    	
    	$this->events = $this->options;
    	\Yii::setAlias('@hustshenl\ueditor\assets', '@vendor/hustshenl/yii2-ueditor/assets');
        if(empty($this->name)){
            $this->name=$this->hasModel() ? Html::getInputName($this->model, $this->attribute): $this->id;
        }
    	
        //register css & js
        $asset = UeditorAsset::register($this->view);
        
        //init options
        parent::init();
    }
    
    /*
     * Renders the widget
     */
    public function run() {
    	
        $this->registerScripts();
        if($this->renderTag===true){
            echo $this->renderTag();
        }
    }
  
    /**
     * render file input tag
     * @return string
     */
    private function renderTag() {
    	$options = [
    		'type'	=> 'text/plain',
    		'name'	=> $this->name,
    		'id'	=> $this->id
		];
    	
        return Html::script($this->ucontent, $options);
    }
    
    /**
     * register script
     */
    private function registerScripts() {
        $jsonOptions = Json::encode($this->events);
        
        $script = <<<EOF
UE.getEditor('{$this->id}', {$jsonOptions});
EOF;
        $this->view->registerJs($script, View::POS_READY);
    }
}
