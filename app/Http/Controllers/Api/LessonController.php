<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function getCases(Request $request)
    {
        $lessons = Lesson::all()->sortBy('id')->take(3)
            ->map(function ($item) {
            if (!Str::contains($item->cover_img, 'http') && $item->cover_img) {
                $item->cover_img = Storage::url($item->cover_img);
            }
            return $item;
        });
        return LessonResource::collection($lessons);
    }
}
