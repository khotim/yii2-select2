# yii2-select2
Yii2 Select2 is an input widget extends from `\yii\widgets\inputWidget` which uses
the ability of [Select2 plugin](https://select2.github.io/).

[![Latest Stable Version](https://poser.pugx.org/khotim/yii2-select2/v/stable)](https://packagist.org/packages/khotim/yii2-select2)
[![License](https://poser.pugx.org/khotim/yii2-select2/license)](https://packagist.org/packages/khotim/yii2-select2)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist khotim/yii2-select2 "*"
```

or add

```
"khotim/yii2-select2": "*"
```

to the require section of your `composer.json` file.


Usage
-----
This extension is similar to `Html::dropDownList()` except that a text input will be used to search
the available option list from a data source.
The minimum usage using prepared array is
```php
echo \khotim\select2\Select2::widget([
    'name' => 'option_list',
    'data' => [
        0 => 'enhancement',
        1 => 'bug',
        2 => 'duplicate',
        3 => 'invalid',
        4 => 'wontfix'
    ],
]);
```
You can also attach this extension in an ActiveField by configuring its `widget()` method like this
```php
<?= $form->field($model, 'option_list')->widget(\khotim\select2\Select2::className(), [
    'data' => [
        0 => 'enhancement',
        1 => 'bug',
        2 => 'duplicate',
        3 => 'invalid',
        4 => 'wontfix'
    ],
]) ?>
```
Example usage using data source via ajax call:
##### View
```php
<?= $form->field($model, 'option_list')->widget(\khotim\select2\Select2::className(), [
    'clientOptions' => [
        'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
        'minimumInputLength' => 1,
        'placeholder' => 'search option list...',
        'allowClear' => true,
        'ajax' => [
            'url' => \yii\helpers\Url::to(['lookup/search-option']),
            'dataType' => 'json',
            'delay' => 250,
            'data' => new \yii\web\JsExpression('function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            }'),
            'processResults' => new \yii\web\JsExpression('function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            }'),
            'cache' => true
        ]
    ]
])?>
```
##### Controller
```php
public function actionSearchOption()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $param = Yii::$app->request->get('q');
    $models = \app\models\OptionSearch::findAll($param);
    $data['items'] = \yii\helpers\ArrayHelper::toArray($models, [
        'app\models\OptionSearch' => [
            'id',
            'text' => function ($model) {
                return $model->name;
            }
        ]
    ]);
    
    return $data;
}
```
--------------------
### Public Properties
Property       &nbsp;|  Type   &nbsp;| Description
:--------------------|:-------------:|:-----------
$data          &nbsp;|  array  &nbsp;| The array of data items, similar to how `items` parameter in `\yii\helpers\BaseHtml::dropDownList()`.
$options       &nbsp;|  array  &nbsp;| The HTML attributes for the input `<select></select>` tag. The default value for "class" element is set to "form-control".
$clientOptions &nbsp;|  array  &nbsp;| The options for the underlying Select2 plugin. Refers to [this page](https://select2.github.io/options.html) for more information.
$clientEvents  &nbsp;|  array  &nbsp;| The event handlers for the underlying Select2 plugin <br> e.g. `['change' => 'function () {alert('event "change" occured.');}']`.
