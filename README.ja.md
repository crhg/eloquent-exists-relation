# 説明

Eloquent Modelに対して取得するときに指定したリレーションにより示された要素が存在するかどうかを表すattributeを副問い合わせを用いて追加します。
リレーションの要素数を求める`withCount`に似ていますが、数えずに存在するかどうかのみを求めるところが異なります。。


# インストール

```shell
composer require crhg/eloquent-exists-relation
```

パッケージディスカバリーに対応しているので設定は不要です。

## Lumenの場合

`bootstrap/app.php`で以下のようにEloquentExistsRelationProviderを登録します。

```php
$app->register(\Crhg\EloquentExistsRelation\Providers\EloquentExistsRelationProvider::class);
```

# 使い方

リレーション結果の有無を実際にレコードを読み込むことなく知りたい場合は、`withExists`メソッドを使います。有無は結果のモデルの`{リレーション名}_exists`カラムに格納されます。

```php
$posts = App\Post::withExists('comments')->get();

foreach ($posts as $post) {
    echo $post->comments_exists;
}
```

クエリによる制約を加え、複数のリレーションの有無を取得することも可能です。

```php
$posts = App\Post::withCount(['votes', 'comments' => function ($query) {
    $query->where('content', 'like', 'foo%');
}])->get();

echo $posts[0]->votes_exists;
echo $posts[0]->comments_exists;
```

同じリレーションの有無の属性を複数含めるため、リレーションの有無の属性に別名も付けられます。

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

EXISTSの値は論理値ではないことがあるので(たとえばmysqlでは0か1)、論理値として扱いたいときは明示的にキャストすると便利です。

```php
    protected $cast = [
        'commensts_exists' => 'bool',
    ];
```

