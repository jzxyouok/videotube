<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Video;
use App\Http\Requests\VideoUpdateRequest;


class VideoController extends Controller
{
    

	public function update(VideoUpdateRequest $request, Video $video){
		$this->authorize('update', $video);

		$video->update([
				'title'=>$request->title,
				'description'=>$request->description,
				'visibility'=>$request->visibility,
				'allow_votes'=>$request->has('allow_votes'),
				'allow_comments'=>$request->has('allow_comments'),
			]);

		if($request->ajax()){
			return response()->json(null, 200);
		}


		return redirect()->back();
	}

    public function store(Request $request){
    	$uid = uniqid(true);

    	$channel = $request->user()->channel()->first();

    	$video = $channel->videos()->create([
    			'uid' => $uid,
    			'title' => $request->title,
    			'description' => $request->description,
    			'visibility' => $request->visibility,
    			'video_filename' => "{$uid}.{$request->extension}",

    		]);



    	return response()->json([
    			'data' => [
    				'uid' => $uid,
    			]
    		]);
    }
}
