<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/11/08
 * Time: 14:33
 */

namespace Tests;


use Crhg\EloquentExistsRelation\Providers\EloquentExistsRelationProvider;
use Orchestra\Database\ConsoleServiceProvider;
use Orchestra\Testbench\TestCase;
use Tests\Models\Bar;
use Tests\Models\Foo;


class WithExistsTest extends TestCase
{
    public function testWithExistsReturns(): void
    {
        $query = Foo::query();

        $this->assertSame($query, $query->withExists([]));
        $this->assertSame($query, $query->withExists(['bars']));
    }

    public function testHasManyRelation(): void
    {
        $created_foo = Foo::create([]);
        $foo_id = $created_foo->id;

        $foo = Foo::withExists(['bars'])->find($foo_id);
        $this->assertFalse($foo->bars_exists);

        Bar::create(['foo_id' => $foo_id, 'name' => 'a']);
        $foo = Foo::withExists(['bars'])->find($foo_id);
        $this->assertTrue($foo->bars_exists);
    }

    public function testAlias(): void
    {
        $created_foo = Foo::create([]);
        $foo_id = $created_foo->id;

        $foo = Foo::withExists(['bars as has_bars'])->find($foo_id);
        $this->assertEquals(0, $foo->has_bars);

        Bar::create(['foo_id' => $foo_id, 'name' => 'a']);
        $foo = Foo::withExists(['bars as has_bars'])->find($foo_id);
        $this->assertEquals(1, $foo->has_bars);
    }

    public function testConstraint(): void
    {
        $created_foo = Foo::create([]);
        $foo_id = $created_foo->id;

        Bar::create(['foo_id' => $foo_id, 'name' => 'a']);

        $foo = Foo::withExists(['bars' => function ($q) { $q->where('name', 'a'); }])->find($foo_id);
        $this->assertTrue($foo->bars_exists);

        $foo = Foo::withExists(['bars' => function ($q) { $q->where('name', 'b'); }])->find($foo_id);
        $this->assertFalse($foo->bars_exists);
    }

    public function testScalarParam(): void
    {
        $created_foo = Foo::create([]);
        $foo_id = $created_foo->id;

        $foo = Foo::withExists('bars')->find($foo_id);
        $this->assertFalse($foo->bars_exists);

        Bar::create(['foo_id' => $foo_id, 'name' => 'a']);
        $foo = Foo::withExists('bars')->find($foo_id);
        $this->assertTrue($foo->bars_exists);

        $foo = Foo::withExists('bars', 'bars as has_bars')->find($foo_id);
        $this->assertTrue($foo->bars_exists);
        $this->assertEquals(true, $foo->has_bars);


    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(realpath(__DIR__.'/database/migrations'));
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            EloquentExistsRelationProvider::class,
            ConsoleServiceProvider::class,
        ];
    }
}
