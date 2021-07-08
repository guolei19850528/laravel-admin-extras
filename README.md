# laravel-admin-extras
## 针对laravel-admin做的部分扩展
#### 安装
```
composer require guolei19850528/laravel-admin-extras

php artisan vendor:publish --tag=laravel-admin-extras
```
---
#### Form扩展使用
```php
$form->radioButton('column', __('text'));
//替换为
$form->extraRadioButton('column', __('text'));

$form->checkboxButton('column', __('text'));
//替换为
$form->extraCheckboxButton('column', __('text'));

//json editor
//option see https://github.com/jdorn/json-editor/
$form->extraJsonEditor('json_column', __('text'))->setOptions([]);

//belongsTo
//扩展归属选择单选
$form->extraBelongsTo('column', Selectable::class, 'Text')
            ->addModalLoadScript(<<<SCRIPT
//此处为处理下拉选择框显示bug
//{{column}} 归属选择字段
//{{sub-column}} 归属选择字段中需要下拉框显示的字段值
$("#modal-selector-{{column}} .{{sub-column}}").select2({
    placeholder: {id:"",text:"选择"},
    allowClear:true
});
SCRIPT
            );

//BelongsToMany
//扩展归属选择单选
$form->BelongsToMany('column', Selectable::class, 'Text')
            ->addModalLoadScript(<<<SCRIPT
//此处为处理下拉选择框显示bug
//{{column}} 归属选择字段
//{{sub-column}} 归属选择字段中需要下拉框显示的字段值
$("#modal-selector-{{column}} .{{sub-column}}").select2({
    placeholder: {id:"",text:"选择"},
    allowClear:true
});
SCRIPT
            );
```
---
#### Detail扩展使用
```php
$show->field('date_column', __('text'))->extraDateFormatter($formatter = 'Y-m-d H:i:s');
```
---
#### Grid扩展使用
```php
$grid->column('date_column', __('text'))->extraDateFormatter($formatter = 'Y-m-d H:i:s');
```



