<?php

namespace Modules\Iprofile\Entities;


use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use Translatable;

    protected $table = 'iprofile__departments';
    protected $fillable = ['parent_id','options','name'];
    public $translatedAttributes = ['name'];

    /**
     * @return mixed
     */
    public function user()
    {
        $driver = config('asgard.user.config.driver');

        return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
    }

}