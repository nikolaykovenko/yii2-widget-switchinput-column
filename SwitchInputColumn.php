<?php

namespace nikolaykovenko\switchinput;

use kartik\widgets\SwitchInput;
use yii\grid\DataColumn;

class SwitchInputColumn extends DataColumn
{
    const SIZE_LARGE = 'large';
    const SIZE_NORMAL = 'normal';
    const SIZE_SMALL = 'small';
    const SIZE_MINI = 'mini';

    const COLOR_DEFAULT = 'default';
    const COLOR_PRIMARY = 'primary';
    const COLOR_SUCCESS = 'success';
    const COLOR_WARNING = 'warning';
    const COLOR_DANGER = 'danger';
    const COLOR_INFO = 'info';

    /** @var callable */
    public $url;
    /** @var bool */
    public $targetBlank = false;
    /** @var string */
    public $controller;
    /** @inheritdoc */
    public $format = 'raw';
    /** @var array */
    public $contentOptions = ['style' => 'width: 100px;'];
    /** @var bool */
    public $disabled = false;
    /** @var string */
    public $size = self::SIZE_SMALL;
    /** @var string */
    public $onColor = self::COLOR_SUCCESS;
    /** @var string */
    public $offColor = self::COLOR_DEFAULT;
    /** @var string */
    public $name;
    /** @var array */
    public $pluginEvents = [];
    /**
     * @var array List of value => name pairs
     */
    public $enum = [];

    /**
     * @var array
     */
    public $dataAttributes = [];

    protected function renderDataCellContent($model, $key, $index): string
    {
        $value = $this->value;

        try {
            $params = [
                'options' => [
                    'id' => 'switch-input-column-' . $key,
                    'value' => $key,
                    'checked' => false,
//                    'class' => 'js-switch-input',
                ],
                'value' => $this->getValue($value, $model),
                'disabled' => $this->disabled,
                'pluginOptions' => [
                    'size' => $this->size,
                    'onColor' => $this->onColor,
                    'offColor' => $this->offColor,
                ],
                'pluginEvents' => $this->createPluginEvents($model, $key, $index),
//                'hashVarLoadPosition' => View::POS_READY, // todo WidgetTrait will need to be investigated
            ];
            foreach ($this->dataAttributes as $dataName => $dataValue) {
                $params['options']['data-' . $dataName] = $this->getValue($dataValue, $model);
            }
            $params['name'] = $this->name;

            $switch = SwitchInput::widget($params);
        } catch (\Exception $e) {
            $switch = $e->getMessage();
        }
        return $switch;
    }

    public function createPluginEvents($model, $key, $index): array
    {
        return $this->pluginEvents instanceof \Closure
            ? call_user_func($this->pluginEvents, $model, $key, $index)
            : $this->pluginEvents;
    }

    private function getValue($value, $model)
    {
        return is_callable($value) ? $value($model) : $value;
    }
}
