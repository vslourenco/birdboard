<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Arr;

trait RecordsActivity
{

    public $oldAttributes = [];


    protected static function bootRecordsActivity()
    {
        foreach(self::recordableEvents() as $event){
            static::$event(function($model) use ($event){
                $model->recordActivity($model->activityDescription($event));
            });

            if($event === 'updated'){
                static::updating(function($model){
                    $model->oldAttributes =  $model->getOriginal();
                });
            }
        }
    }

    private function activityDescription($event)
    {
        return "{$event}_".strtolower(class_basename($this));;
    }

    private static function recordableEvents()
    {
        if(isset(static::$recordableEvents)){
            return static::$recordableEvents;
        }

        return ['created', 'updated', 'deleted'];
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => $this->id
        ]);
    }

    private function activityChanges()
    {
        if($this->wasChanged()){
            return [
                'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), ['updated_at']),
                'after' => Arr::except($this->getChanges(), ['updated_at'])
            ];
        }
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
