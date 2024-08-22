<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;

    protected $casts = [
        'video'=>'json'
    ];

    public function setVideoAttribute($value) {
        $this->attributes['video'] = json_encode(array_values($value));
    }
     public function getVideoAttribute($value)
    {
        //dump($value);
        $resVideo = json_decode($value, true)?:[];
        //dump($resVideo);
        if (!empty($resVideo)) {
            foreach($resVideo as $k=>$v) {
                $resVideo[$k]["url"] = $v["url"];
                $resVideo[$k]["thumbnail"] = $v["thumbnail"];
            }
        }
        return $resVideo;
        //$this->attributes['video'] = array_values(json_decode($value, true) ?: []);
    }
}
