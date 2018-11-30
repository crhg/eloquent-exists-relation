<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/11/08
 * Time: 18:10
 */

namespace Tests\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Foo extends Model
{
    protected $table = 'foo';

    protected $casts = [
        'bars_exists' => 'bool',
        'baz_exists' => 'bool',
    ];

    public function bars(): HasMany
    {
        return $this->hasMany(Bar::class);
    }

    public function baz(): BelongsToMany
    {
        return $this->belongsToMany(Baz::class);
    }
}