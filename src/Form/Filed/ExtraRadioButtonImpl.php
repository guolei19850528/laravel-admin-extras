<?php

namespace Guolei\Extras\Form\Filed;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field\BelongsTo;
use Encore\Admin\Form\Field\BelongsToMany;
use Encore\Admin\Form\Field\Checkbox;
use Encore\Admin\Form\Field\CheckboxButton;
use Encore\Admin\Form\Field\CheckboxCard;
use Encore\Admin\Form\Field\MultipleSelect;
use Encore\Admin\Form\Field\Radio;
use Encore\Admin\Form\Field\RadioButton;
use Encore\Admin\Form\Field\RadioCard;
use Encore\Admin\Form\Field\Select;


class ExtraRadioButtonImpl extends RadioButton
{
    protected $view = 'admin::form.radiobutton';

    protected function addScript()
    {
        $script = <<<'SCRIPT'
    //设置radio button选中的样式
    $('.radio-group-toggle label').filter('.active').attr('class','btn btn-primary active');
    //radio button点击事件监测
    $('.radio-group-toggle label').click(function() {
      $(this).attr('class','btn btn-primary active').siblings().attr('class','btn btn-default');
    });
SCRIPT;

        Admin::script($script);
    }

    public function render()
    {
        $this->addScript();

        $this->addCascadeScript();

        $this->addVariables([
            'options' => $this->options,
            'checked' => $this->checked,
        ]);

        return parent::fieldRender();
    }

    protected function getFormFrontValue()
    {
        switch (get_class($this)) {
            case Radio::class:
            case RadioButton::class:
            case ExtraRadioButton::class:
            case RadioCard::class:
            case Select::class:
            case BelongsTo::class:
            case BelongsToMany::class:
            case MultipleSelect::class:
                return 'var checked = $(this).val();';
            case Checkbox::class:
            case CheckboxButton::class:
            case ExtraRadioButton::class:
            case CheckboxCard::class:
                return <<<SCRIPT
var checked = $('{$this->getElementClassSelector()}:checked').map(function(){
  return $(this).val();
}).get();
SCRIPT;
            default:
                throw new \InvalidArgumentException('Invalid form field type');
        }
    }
}
