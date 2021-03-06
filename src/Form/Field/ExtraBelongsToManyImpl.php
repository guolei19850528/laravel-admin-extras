<?php


namespace Guolei\Extras\Form\Field;


use Encore\Admin\Admin;
use Encore\Admin\Form\Field\BelongsTo;
use Encore\Admin\Form\Field\BelongsToMany;
use Encore\Admin\Form\Field\BelongsToRelation;


class ExtraBelongsToManyImpl extends BelongsToMany
{
    protected $view = 'admin::form.belongstomany';
    protected $modalLoadScript = '';
    protected $modalUpdateScript = '';

    public function addModalLoadScript($script = '')
    {
        $this->modalLoadScript = $script;
        return $this;
    }

    public function addModalUpdateScript($script = '')
    {
        $this->modalUpdateScript = $script;
        return $this;
    }

    protected function addScript()
    {
        $script = <<<SCRIPT
;(function () {

    var grid = $('.belongstomany-{$this->column()}');
    var modal = $('#{$this->modalID}');
    var table = grid.find('.grid-table');
    var selected = $("{$this->getElementClassSelector()}").val() || [];
    var rows = {};

    table.find('tbody').children().each(function (index, tr) {
        if ($(tr).find('.grid-row-remove').length > 0) {
            rows[$(tr).find('.grid-row-remove').data('key')] = $(tr);
        }
    });

    // open modal
    grid.find('.select-relation').click(function (e) {
        $('#{$this->modalID}').modal('show');
        e.preventDefault();
    });

    // remove row
    grid.on('click', '.grid-row-remove', function () {
        val = $(this).data('key').toString();

        var index = selected.indexOf(val);
        if (index !== -1) {
           selected.splice(index, 1);
           delete rows[val];
        }

        $(this).parents('tr').remove();
        $("{$this->getElementClassSelector()}").val(selected);

        if (selected.length == 0) {
            var empty = $('.belongstomany-{$this->column()}').find('template.empty').html();
            table.find('tbody').append(empty);
        }
    });

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($.inArray($(el).val().toString(), selected) >=0 ) {
                    $(el).iCheck('toggle');
                }
            });
             {$this->modalLoadScript}
        });
    };

    var update = function (callback) {

        $("{$this->getElementClassSelector()}")
            .select2({data: selected})
            .val(selected)
            .trigger('change')
            .next()
            .addClass('hide');

        table.find('tbody').empty();

        Object.values(rows).forEach(function (row) {
            row.find('td:last a').removeClass('hide');
            row.find('td.column-__modal_selector__').remove();
            table.find('tbody').append(row);
        });

        if (selected.length == 0) {
            var empty = $('.belongstomany-{$this->column()}').find('template.empty').html();
            table.find('tbody').append(empty);
        } else {
            table.find('.empty-grid').parent().remove();
        }

        callback();
        {$this->modalUpdateScript}
    };

    modal.on('show.bs.modal', function (e) {
        load("{$this->getLoadUrl(1)}");
    }).on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('click', 'tr', function (e) {
        $(this).find('input.select').iCheck('toggle');
        e.preventDefault();
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        e.preventDefault();
        return false;
    }).on('ifChecked', 'input.select', function (e) {
        if (selected.indexOf($(this).val()) < 0) {
            selected.push($(this).val());
            rows[$(e.target).val()] = $(e.target).parents('tr');
        }
    }).on('ifUnchecked', 'input.select', function (e) {
           var val = $(this).val();
           var index = selected.indexOf(val);
           if (index !== -1) {
               selected.splice(index, 1);
               delete rows[$(e.target).val()];
           }
    }).find('.modal-footer .submit').click(function () {
        update(function () {
            modal.modal('toggle');
        });
    });
})();
SCRIPT;

        Admin::script($script);

        return $this;
    }

    public function readOnly()
    {
        $script = <<<SCRIPT
$(".belongstomany-{$this->column()} .box-header").remove();
$(".belongstomany-{$this->column()} table .column-__remove__").remove();
$(".belongstomany-{$this->column()} table th span").remove();
SCRIPT;
        Admin::script($script);
        return $this; // TODO: Change the autogenerated stub
    }
}
