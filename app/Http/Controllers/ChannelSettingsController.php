<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Http\Requests;
use App\Http\Requests\ChannelUpdateRequest;
use App\Jobs\UploadImage;


class ChannelSettingsController extends Controller
{
   	public function edit(Channel $channel){
   		$this->authorize('edit', $channel);
   		return view('channel.settings.edit',['channel'=>$channel]);
   	}

   	public function update(ChannelUpdateRequest $request, Channel $channel){

   		$this->authorize('update', $channel);

   		$channel->update([
   			'name' => $request->name,
   			'slug'=>$request->slug,
   			'description'=>$request->description,
   			]);

         if($request->file('image')){
            $fileId = uniqid(true);
            $request->file('image')->move(storage_path().'/uploads', $fileId);

            $this->dispatch(new UploadImage($channel, $fileId));

         }
   		
   		return redirect()->to("/channel/{$request->slug}/edit");
   	}
}
