<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Video;
use App\Models\Lesson;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class VideoController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Video(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('videoId');
            $grid->column('duration');
            $grid->column('corver_url');
            $grid->column('lesson_id')->display(function ($val) {
                return Lesson::find($val)->name ?? '';
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

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
        return Show::make($id, new Video(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('videoId');
            $show->field('duration');
            $show->field('corver_url');
            $show->field('lesson_id');
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
        return Form::make(new Video(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('corver_url');
            $form->select('lesson_id')->options(Lesson::all()->pluck('name', 'id'));

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
