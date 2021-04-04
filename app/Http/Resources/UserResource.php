<?php

namespace App\Http\Resources;

use App\Session;
use DB;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->username,
            'email' => $this->email,
            'avatar_path' => $this->avatar_path,
            'online' => false,
            'session' => $this->session_details($this->id)
        ];
    }

    private function session_details($id)
    {
        $session = Session::whereIn('user1_id', [auth()->id(), $id])->whereIn('user2_id', [auth()->id(), $id])->first();
        return new SessionResource($session);
    }
}
