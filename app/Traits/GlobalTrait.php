<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait GlobalTrait
{
    private function getPeriodData($request){
        $currentDate = Carbon::now();
        $date['from'] =$request&& $request['from']?date_format(Carbon::parse($request['from']),'Y-m-d'):date_format($currentDate->startOfMonth(),'Y-m-d');
        $date['to'] = $request&&$request['to']?date_format(Carbon::parse($request['to']),'Y-m-d'):date('Y-m-d');
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
