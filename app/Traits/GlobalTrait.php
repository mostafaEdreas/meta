<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait GlobalTrait
{
    private function getPeriodData($request){
        $currentDate = Carbon::now();
        $date['from'] = $request['from']?date_format($request['from'],'Y-m-d'):date_format($currentDate->startOfMonth(),'Y-m-d');
        $date['to'] = $request['to']?date_format($request['to'],'Y-m-d'):date_format($currentDate,'Y-m-d');
        return $date;
    }

    public function saveImg($key ,$folderName){
        $image = request()->file($key);
        $img = Image::make($image);
        $imgName = uniqid() . '.webp';
        $imagePath = Storage::disk('img')->path($folderName.'/'.$imgName) ;
        $img->save($imagePath);
        return $imgName;
    }
    public function removeImg($imgName,$folderName){
        $imagePath = $folderName.'/'.$imgName;
        if (Storage::disk('img')->exists($imagePath)) {
            Storage::disk('img')->delete($imagePath);
        }
        return true;
    
    }
}
