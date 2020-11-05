<?php
namespace App\Admin\Renderable;

use App\Models\Tag;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class TagTable extends  LazyRenderable
{
    public function grid(): Grid
    {
        // 获取外部传递的参数
//        $id = $this->id;
        return Grid::make(new Tag(), function (Grid $grid) {
            $grid->column('id');
            $grid->column('name');
            $grid->rowSelector()->titleColumn('name');
            $grid->quickSearch(['id', 'name']);
            $grid->paginate(10);
            $grid->disableActions();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('name')->width(4);
            });
        });
    }

}
