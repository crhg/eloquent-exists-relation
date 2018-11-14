<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/11/08
 * Time: 18:11
 */

namespace Tests\Models;


use Illuminate\Database\Eloquent\Model;

class Bar extends Model
{
    protected $table = 'bar';

    protected $fillable = [ 'foo_id', 'name' ];
}