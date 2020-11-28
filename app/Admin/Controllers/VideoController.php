<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\VideoUpload;
use App\Admin\Repositories\Video;
use App\Models\Lesson;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Lazy;
use Dcat\Admin\Widgets\Modal;

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
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->selectOne('lesson_id', Lesson::all()->pluck('name', 'id')->toArray());
            });

            $grid->model()->orderBy('sort', 'asc');
            $grid->column('name')->editable(true)->display(function ($name) {
                return $this->sort.'. '.$name;
            });
            $grid->column('videoId')
                ->display(function ($videoId) {
                    if (!$videoId) {
                        return '视频上传';
                    }
                    return '视频更新';
                })
                ->modal(function ($modal) {
                    $modal->title('上传视频：'.$this->name);
                    // 允许在比包内返回异步加载类的实例
                    return VideoUpload::make(['lesson_id'=>$this->lesson_id]);
                });

            $grid->column('duration');
            $grid->column('corver_url')->image('', 50,50);
            $grid->column('sort')->editable(true);
            $grid->column('is_free')->switch();
            $grid->column('lesson_id')->display(function ($val) {
                return Lesson::find($val)->name ?? '';
            });

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
        return Show::make($id, new Video(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('videoId');
            $show->field('duration');
            $show->field('corver_url')->image();
            $show->field('is_free')->as(function ($is_free) {
                return $is_free ? '是' : '否';
            });
            $show->field('lesson_id')->as(function ($lesson_id) {
                return Lesson::find($lesson_id)->name ?? '';
            });
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
            $form->select('lesson_id')->options(Lesson::all()->pluck('name', 'id'));
            $form->number('sort');
            $form->switch('is_free');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
