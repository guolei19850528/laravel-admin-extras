<?php


namespace Guolei\Extras\Form\Field;


use Encore\Admin\Form\Field\HasMany;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Widgets\Form as WidgetForm;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

/**
 * 扩展form has many
 * 主要处理一对多关联表单验证不能起作用
 * Class ExtraHasMany
 * @package App\Admin\Extensions\Form\Field
 */
class ExtraHasManyImpl extends HasMany
{

    protected function buildRelatedForms()
    {
        if (is_null($this->form)) {
            return [];
        }

        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation && !$relation instanceof MorphMany) {
            throw new \Exception('hasMany field must be a HasMany or MorphMany relation.');
        }

        $forms = [];

        /*
         * If redirect from `exception` or `validation error` page.
         *
         * Then get form data from session flash.
         *
         * Else get data from database.
         */
//        var_dump(request()->allFiles());exit;
        if ($values = old($this->column)) {
            foreach ($values as $key => $data) {
                if ($data[NestedForm::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }

                $model = $relation->getRelated()->replicate()->forceFill($data);

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder, $model, $key)
                    ->fill($data);
            }
        } else {
            if (empty($this->value)) {
                return [];
            }

            foreach ($this->value as $data) {
                $key = Arr::get($data, $relation->getRelated()->getKeyName());

                $model = $relation->getRelated()->replicate()->forceFill($data);

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder, $model)
                    ->fill($data);
            }
        }

        return $forms;
    }

    public function buildNestedForm($column, \Closure $builder, $model = null, $key = null)
    {
        $form = new NestedForm($column, $model);
        $form->setKey($key);

        if ($this->form instanceof WidgetForm) {
            $form->setWidgetForm($this->form);
        } else {
            $form->setForm($this->form);
        }

        call_user_func($builder, $form);

        $form->hidden($this->getKeyName());

        $form->hidden(NestedForm::REMOVE_FLAG_NAME)->default(0)->addElementClass(NestedForm::REMOVE_FLAG_CLASS);

        return $form;
    }
}
