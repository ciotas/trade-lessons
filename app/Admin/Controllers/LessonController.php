<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Lesson;
use App\Models\Type;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Illuminate\Support\Facades\Storage;

class LessonController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Lesson(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('price');
            $grid->column('crossed_price');
            $grid->column('type_id')->display(function ($val) {
                return Type::find($val)->name ?? '';
            });
            $grid->column('cover_img')->image('', 50,50);
            $grid->column('brief')
                ->display('查看') // 设置按钮名称
                ->modal(function ($modal) {
                    // 设置弹窗标题
                    $modal->title('介绍');
                    $card = new Card(null, $this->brief);
                    return $card;
                });;

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
        return Show::make($id, new Lesson(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('price');
            $show->field('crossed_price');
            $show->field('type_id');
            $show->field('cover_img')->image();
//            ->as(function ($cover_img) {
//                return Storage::url($cover_img);
//            })
            $show->field('brief');
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
        return Form::make(new Lesson(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->decimal('price');
            $form->decimal('crossed_price');
            $form->select('type_id')->options(Type::all()->pluck('name', 'id'));
            $form->textarea('brief');
            $form->image('cover_img')->accept('jpg,png,gif,jpeg')->move('covers')->uniqueName()->autoUpload();;
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
