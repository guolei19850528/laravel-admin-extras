<?php


namespace Guolei\Extras\Form\Field;


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

class ExtraCheckboxButtonImpl extends CheckboxButton
{
    protected $view='admin::form.checkboxbutton';
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    protected function addScript()
    {
        $script = <<<'SCRIPT'
//设置checkbox button样式
$('.checkbox-group-toggle label').filter('.active').attr('class','btn btn-primary active');
$('.checkbox-group-toggle label').click(function(e) {
    e.stopPropagation();
    e.preventDefault();

    if ($(this).hasClass('active')) {
        $(this).attr('class','btn btn-default');
        $(this).find('input').prop('checked', false);
    } else {
        $(this).attr('class','btn btn-primary active');
        $(this).find('input').prop('checked', true);
    }

    $(this).find('input').trigger('change');
});
SCRIPT;

        Admin::script($script);
    }

    /**
     * {@inheritdoc}
     */
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
            case ExtraRadioButtonImpl::class:
            case RadioCard::class:
            case Select::class:
            case BelongsTo::class:
            case BelongsToMany::class:
            case MultipleSelect::class:
                return 'var checked = $(this).val();';
            case Checkbox::class:
            case CheckboxButton::class:
            case ExtraCheckboxButtonImpl::class:
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
