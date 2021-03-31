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
$form->radioButton('status', __('text'));
//替换为
$form->extraRadioButton('status', __('text'));

$form->checkBoxButton('status', __('text'));
//替换为
$form->extraCheckBoxButton('status', __('text'));

//json editor
//option see https://github.com/jdorn/json-editor/
$form->extraJsonEditor('json_data', __('text'))->setOptions([]);
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



