<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Type;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class TypeController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Type(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('typeId');

            $grid->toolsWithOutline(false);
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('name');
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Type(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('typeId');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Type(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('typeId');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
