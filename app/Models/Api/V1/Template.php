<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Template extends Model
{
    /** @use HasFactory<\Database\Factories\Api/V1/TemplateFactory> */
    use HasFactory;
    protected $fillable = ['plan', 'price', 'name', 'imagepath', 'public_id'];

    // public function scopeFilter(Builder | QueryBuilder $query, string | null $plan ) : Builder | QueryBuilder{
    //     return $query->when($plan['premium'] ?? null, function ($query, $premium) {
    //         $query->where('plan', 'like', '%' . $premium . '%');
    //     })->when($plan['standard'] ?? null, function ($query, $standard) {
    //         $query->where('plan', 'like', '%' . $standard . '%');
    //     });
    // }

       // public function scopeFilter(Builder | QueryBuilder $query, string | null $plan ) : Builder | QueryBuilder{
    //     return $query->when($plan ?? null, function ($query, $premium) {
    //         $query->where('plan', 'like', '%' . $premium . '%');
    //     })->when($plan ?? null, function ($query, $standard) {
    //         $query->where('plan', 'like', '%' . $standard . '%');
    //     });
    // }

    public function scopeFilter(Builder | QueryBuilder $query, string | null $plan ) : Builder | QueryBuilder{
        return $query->when($plan ?? null, function ($query, $premium) {
            $query->where('plan',$premium);
        })->when($plan ?? null, function ($query, $standard) {
            $query->where('plan', $standard);
        });
    }
 
}