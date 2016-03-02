<?php
/**
 * @author Thimy Khotim <thimy.khotim@gmail.com>
 * @link https://github.com/khotim/yii2-select2/
 * @license MIT License
 * @version 1.0
 */
namespace khotim\select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * Select2 widget renders dropdown list based on
 * [Select2 plugin](https://select2.github.io/) as shown in
 * [this page](https://select2.github.io/examples.html).
 */
class Select2 extends InputWidget
{
    
    public $data = [];
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'form-control'];
    /**
     * @var array the options for the underlying Select2 plugin.
     * Please refer to [this page](https://select2.github.io/options.html)
     * for list of supported options.
     */
    public $clientOptions = [];
    /**
     * @var array the event handlers for the underlying Select2 plugin.
     * For example you could write the following in your widget configuration:
     *
     * ```php
     * 'clientEvents' => [
     *     'change' => 'function () { alert('event "change" occured.'); }'
     * ],
     * ```
     */
    public $clientEvents = [];
    /**
     * @var array event names mapped to what should be specified in `.on()`.
     * If empty, it is assumed that event passed to clientEvents is prefixed with widget name.
     */
    protected $clientEventMap = [];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->renderWidget() . "\n";
        
        $this->registerClientOptions();
        $this->registerClientEvents();
        BaseAsset::register($this->getView());
    }
    
    /**
     * Renders Select2 widget.
     */
    protected function renderWidget()
    {
        $contents = [];

        // get value
        if ($this->hasModel()) {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $value = $this->value;
        }
        
        $options = $this->options;
        $options['value'] = $value;
        
        // render an input
        if ($this->hasModel()) {
            $contents[] = Html::activeDropDownList($this->model, $this->attribute, $this->data, $options);
        } else {
            $contents[] = Html::dropDownList($this->name, $value, $this->data, $options);
        }

        return implode("\n", $contents);
    }
    
    /**
     * Registers Select2 plugin options.
     */
    protected function registerClientOptions()
    {
        if ($this->clientOptions !== false) {
            $options = Json::htmlEncode($this->clientOptions);
            $id = $this->options['id'];
            $js = "$('#{$id}').select2($options);";
            
            $key = __CLASS__ . '#' . $this->id;
            $this->getView()->registerJs($js, View::POS_READY, $key);
        }
    }
    
    /**
     * Registers Select2 plugin events
     */
    protected function registerClientEvents()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            $id = $this->options['id'];
            foreach ($this->clientEvents as $event => $handler) {
                if (isset($this->clientEventMap[$event])) {
                    $eventName = $this->clientEventMap[$event];
                } else {
                    $eventName = strtolower($name . $event);
                }
                $js[] = "$('#{$id}').on('$eventName', $handler);";
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }
}
