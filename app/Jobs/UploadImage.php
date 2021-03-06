<?php

namespace App\Jobs;

use Image;
use File;
use Storage;
use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadImage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $channel;
    public $fileId;


    public function __construct(Channel $channel, $fileId)
    {
        
        $this->channel = $channel;
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = storage_path().'/uploads/'.$this->fileId;
        $fileName=$this->fileId . '.png';


        Image::make($path)->encode('png')->fit(40,40, function($c){
            $c->upsize();
        })->save();

        if(Storage::disk('s3images')->put('profile/'.$fileName, fopen($path, 'r+'))){//uploads images to AWS S3
            File::delete($path);
        } 

        $this->channel->image_filename = $fileName;
        $this->channel->save();
    }
}
