<?php

namespace App\Observers;

use App\Models\RecApplication;
use App\Models\Log;

class RecApplication
{
    /**
     * Handle the RecApplication "created" event.
     *
     * @param  \App\Models\RecApplication  $recApplication
     * @return void
     */
    public function created(RecApplication $recApplication)
    {
        $data=[
            'user_id'=>auth()->user()->id,
            'detail'=>json_encode($this->vacancy),
        ];
        if($this->paid){
            $data['action']='111';
        }elseif($this->submitted_at){
            $data['action']='110';
        }else{
            $data['action']='101';
        }
        Log::create($data);
    }

    /**
     * Handle the RecApplication "updated" event.
     *
     * @param  \App\Models\RecApplication  $recApplication
     * @return void
     */
    public function updated(RecApplication $recApplication)
    {
        $data=[
            'user_id'=>auth()->user()->id,
            'detail'=>json_encode($this->vacancy),
        ];
        if($this->paid){
            $data['action']='111';
        }elseif($this->submitted_at){
            $data['action']='110';
        }else{
            $data['action']='102';
        }
        Log::create($data);
    }

    /**
     * Handle the RecApplication "deleted" event.
     *
     * @param  \App\Models\RecApplication  $recApplication
     * @return void
     */
    public function deleted(RecApplication $recApplication)
    {
        //
    }

    /**
     * Handle the RecApplication "restored" event.
     *
     * @param  \App\Models\RecApplication  $recApplication
     * @return void
     */
    public function restored(RecApplication $recApplication)
    {
        //
    }

    /**
     * Handle the RecApplication "force deleted" event.
     *
     * @param  \App\Models\RecApplication  $recApplication
     * @return void
     */
    public function forceDeleted(RecApplication $recApplication)
    {
        //
    }
}
