<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Scope\PublishedTrait;


class Product extends Model
{
    use SoftDeletes;
    use PublishedTrait;

    protected $dates = ['deleted_at'];
    public $timestamps = false;
    // protected $hidden = ['description', 'stock'];
    protected $fillable = ['name', 'description', 'price', 'stock'];

    public function scopeOverstock($query) {
        return $query->where('stock', '>', 30);
    }
    public function scopeOverprice($query)
    {
        return $query->where('price', '>', 400000000);
    }

    public function scopePremium($query)
    {
        return $query->overstock()->overprice();
    }

    public function scopeLevel($query, $parameter)
    {
        switch ($parameter) {
            case 'lux':
            return $query->where('price', '>', 500000000);
            break;
            case 'mid':
            return $query->whereBetween('price', [300000000,500000000]);
            break;
            case 'entry':
            return $query->where('price', '<', 300000000);
            break;
            default:
            return $query;
            break;
        }
    }

    protected static function boot() {
        parent::boot();
        static::created(function($model){
            \Log::info('Berhasil menambah '. $model->name .'. Stock : '. $model->stock);
        });
    }


}
