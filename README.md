# DESCRIPTION

Add an attribute that indicates whether there is an element indicated by the relation specified when acquiring to the Eloquent Model using a subquery.
It is similar to `withCount`, which finds the number of elements of a relation, but it differs in obtaining only whether it exists without counting. .

# INSTALL

```shell
composer require crhg/eloquent-exists-relation
```

This package is compliant with Package Discovery so no additional configuration is required.

## In case of Lumen

Register `EloquentExistsRelationProvider` as follows in `bootstrap/app.php`.

```php
$app->register(\Crhg\EloquentExistsRelation\Providers\EloquentExistsRelationProvider::class);
```

# USAGE

リレーション結果の有無を実際にレコードを読み込むことなく知りたい場合は、`withExists`メソッドを使います。有無は結果のモデルの`{リレーション名}_exists`カラムに格納されます。

If you want to know wheather results from a relationship exists without actually loading them you may use the `withExists` method, which will place a {relation}_exists column on your resulting models. For example:

```php
$posts = App\Post::withExists('comments')->get();

foreach ($posts as $post) {
    echo $post->comments_exists;
}
```

You may add the "exists" for multiple relations as well as add constraints to the queries:

```php
$posts = App\Post::withCount(['votes', 'comments' => function ($query) {
    $query->where('content', 'like', 'foo%');
}])->get();

echo $posts[0]->votes_exists;
echo $posts[0]->comments_exists;
```



You may also alias the relationship exists result, allowing multiple exists on the same relationship:

```php
$posts = App\Post::withCount([
    'comments',
    'comments as pending_comments_exists' => function ($query) {
        $query->where('approved', false);
    }
])->get();

echo $posts[0]->comments_exists;

echo $posts[0]->pending_comments_exists;
```

# TIPS

Since the value of EXISTS may not be a boolean value (for example, 0 or 1 in mysql), it is convenient to cast it explicitly when you want to treat it as a boolean value.

```php
    protected $cast = [
        'commensts_exists' => 'bool',
    ];
```

