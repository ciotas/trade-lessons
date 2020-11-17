<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function show($lesson_id, Request $request)
    {
        $lessons = Lesson::with('type', 'tags', 'videos')->find($lesson_id);
        return Inertia::render('Lesson/Lesson', ['lessons'=>$lessons]);
    }
}
