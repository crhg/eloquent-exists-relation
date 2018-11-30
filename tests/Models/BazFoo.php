<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018-11-30
 * Time: 14:49
 */

namespace Tests\Models;


use Illuminate\Database\Eloquent\Model;

class BazFoo extends Model
{
    protected $table = 'baz_foo';

    protected $fillable = [ 'baz_id', 'foo_id' ];
}