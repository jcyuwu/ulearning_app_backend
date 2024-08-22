<?php

namespace App\Admin\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LessonController extends AdminController
{
    protected $title = "Lesson";

    protected function grid()
    {
        $grid = new Grid(new Lesson());

        $grid->column("id", __("Id"));
        $grid->column("course_id", __("Course"))->display(function($id){
            $item = Course::where("id", "=", $id)->value("name");
            return $item;
        });
        $grid->column("name", __("Name"));
        $grid->column("thumbnail", __("Thumbnail"))->image("", 50, 50);
        $grid->column('description', __('Description'));
        //$grid->column('video', __('Video'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }
    //
    protected function detail($id)
    {
        $show = new Show(Lesson::findOrFail($id));

        $show->field("id", __("Id"));
        $show->field("course_id", __("Course id"));
        $show->field("name", __("Name"));
        $show->field("thumbnail", __("Thumbnail"));
        $show->field('description', __('Description'));
        $show->field('video', __('Video'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Lesson());

        //$form->number("course_id", __("Course id"));
        $form->text("name", __("Name"));
        $result = Course::pluck("name", "id");
        $form->select("course_id", __("Courses"))->options($result);
        $form->image("thumbnail", __("Thumbnail"))->uniqueName();
        $form->textarea('description', __('Description'));
        $form->table('video', function($form) {
            $form->text('name')->rules('required');
            $form->image('thumbnail')->uniqueName()->rules('required');
            $form->file('url')->rules('required');
        });
        $form->display('created_at', __('Created at'));
        $form->display('updated_at', __('Updated at'));
        return $form;
    }
}
