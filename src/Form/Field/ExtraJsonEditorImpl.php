<?php


namespace Guolei\Extras\Form\Field;


use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Renderable;

class ExtraJsonEditorImpl extends Field\Textarea
{
    protected $view = 'laravel-admin-extras::form.filed.extra-json-editor';

    protected $options = [
        'ajax' => true,
        'schema' => [],
        'theme' => 'bootstrap3',
        'iconlib' => 'fontawesome4',
        'no_additional_properties' => true,
        'disable_edit_json' => true,
        'disable_properties' => true,
        'disable_collapse' => true,
    ];

    protected static $js = [

    ];

    /**
     * @param mixed $schema
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }


    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public function addModalHtml()
    {
        $options = json_encode($this->options);
        $modalHtml = <<<HTML
<div class="modal" tabindex="-1" role="dialog" id="{$this->formatName($this->column)}_json_editor_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$this->label()}-编辑器</h4>
            </div>
            <form>
            <div class="modal-body">
            <div id="{$this->formatName($this->column)}_json_editor"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary {$this->formatName($this->column)}_json_editor_save">保存</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
 //初始化json editor
 var {$this->formatName($this->column)}_json_editor = new JSONEditor(document.getElementById('{$this->formatName($this->column)}_json_editor'),{$options});

 //监听保存按钮点击时间
 $(".{$this->formatName($this->column)}_json_editor_save").on('click',function (){
  $("textarea[name='{$this->formatName($this->column)}']").val(JSON.stringify({$this->formatName($this->column)}_json_editor.getValue()));
     $("#{$this->formatName($this->column)}_json_editor_modal").modal('hide');
 });

  //监听模态框显示
 $("#{$this->formatName($this->column)}_json_editor_modal").on('show.bs.modal',function (){
       if($("textarea[name='{$this->formatName($this->column)}']").val()){
            {$this->formatName($this->column)}_json_editor.setValue(JSON.parse($("textarea[name='{$this->formatName($this->column)}']").val()));
       }

 });

</script>
HTML;
        Admin::html($modalHtml);

    }


    public function render()
    {
        $this->addModalHtml();
        return parent::render();
    }
}
