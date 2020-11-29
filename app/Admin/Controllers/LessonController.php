<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\TagTable;
use App\Admin\Repositories\Lesson;
use App\Models\Tag;
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
        return Grid::make(new Lesson('tags'), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('price');
            $grid->column('crossed_price');
            $grid->column('type_id')->display(function ($val) {
                return Type::find($val)->name ?? '';
            });
            $grid->column('tags')->display(function ($tags) {
                return array_column($tags, 'name');
            })->label();
            $grid->column('cover_img')->image('', 50,50);
            $grid->column('brief')
                ->display('查看') // 设置按钮名称
                ->modal(function ($modal) {
                    // 设置弹窗标题
                    $modal->title('介绍');
                    $card = new Card(null, $this->brief);
                    return $card;
                });;

            $grid->toolsWithOutline(false);
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('name');
                $filter->equal('type_id')->select(Type::all()->pluck('name', 'id'));
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
        return Show::make($id, new Lesson('tags'), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('tags')->as(function ($tags) {
                return array_column($tags, 'name');
            })->label();
            $show->field('price');
            $show->field('crossed_price');
            $show->field('type_id')->as(function($type_id) {
                return Type::find($type_id)->name ?? '';
            });
            $show->field('cover_img')->image();

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
        return Form::make(new Lesson('tags'), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->multipleSelect('tags')
                 ->options(Tag::all()->pluck('name', 'id'))
                ->customFormat(function ($v) {
                    if (!$v) return [];
                    return array_column($v, 'id');
                });
//            $form->multipleSelectTable('tags')
//                ->from(TagTable::make([]))  // 'id' => $form->getKey() 设置渲染类实例，并传递自定义参数
//                ->model(Tag::class, 'id', 'name')  // 设置编辑数据显示
//                ->customFormat(function ($v) {
//                    if (!$v) return [];
//                    // 这一步非常重要，需要把数据库中查出来的二维数组转化成一维数组
//                    return array_column($v, 'id');
//                });

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
