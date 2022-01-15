<?php

use Cerbero\EloquentInspector\Components\Properties;
use Cerbero\EloquentInspector\Dtos\Property;
use Cerbero\EloquentInspector\Dtos\Relationship;
use Cerbero\EloquentInspector\Exceptions\UnknownType;
use Cerbero\EloquentInspector\Inspector;
use Cerbero\EloquentInspector\Models\Nested\Tag;
use Cerbero\EloquentInspector\Models\Post;
use Cerbero\EloquentInspector\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

beforeEach(fn () => $this->inspector = Inspector::inspect(User::class));
afterEach(fn () => Mockery::close());

it('can be instantiated statically')
    ->expect(fn () => $this->inspector)
    ->toBeInstanceOf(Inspector::class);

it('is a singleton')
    ->expect(fn () => $this->inspector)
    ->toBe(Inspector::inspect(User::class))
    ->not->toBe($postInspector = Inspector::inspect(Post::class))
    ->and($postInspector)->toBe(Inspector::inspect(Post::class));

it('flushes an inspector instance', function () {
    Inspector::flush(User::class);

    expect(Inspector::inspect(User::class))->not->toBe($this->inspector);
});

it('flushes all inspector instances', function () {
    $postInspector = Inspector::inspect(Post::class);

    Inspector::flush();

    expect(Inspector::inspect(User::class))->not->toBe($this->inspector);
    expect(Inspector::inspect(Post::class))->not->toBe($postInspector);
});

it('cleans up information of the inspected model', function () {
    $this->inspector->forget();

    expect(Inspector::inspect(User::class))->not->toBe($this->inspector);
});

it('retrieves the inspected model class')
    ->expect(fn () => $this->inspector->getModel())->toBe(User::class);

it('inspects `use` statements')
    ->expect(fn () => $this->inspector->getUseStatements())
    ->toBe([
        'Tag' => 'Cerbero\EloquentInspector\Models\Nested\Tag',
        'Model' => 'Illuminate\Database\Eloquent\Model',
        'Baz' => 'Foo\Bar',
    ]);

it('inspects properties')
    ->expect(fn () => $this->inspector->getProperties())
    ->toHaveCount(5)
    ->sequence(
        fn ($property, $key) => $key->toBe('id')->and($property->value)
            ->name->toBe('id')
            ->type->toBe('int')
            ->dbType->toBe('integer')
            ->nullable->toBe(false)
            ->default->toBe(null),
        fn ($property, $key) => $key->toBe('email')->and($property->value)
            ->name->toBe('email')
            ->type->toBe('string')
            ->dbType->toBe('string')
            ->nullable->toBe(false)
            ->default->toBe(null),
        fn ($property, $key) => $key->toBe('email_verified_at')->and($property->value)
            ->name->toBe('email_verified_at')
            ->type->toBe('Carbon\Carbon')
            ->dbType->toBe('datetime')
            ->nullable->toBe(true)
            ->default->toBe(null),
        fn ($property, $key) => $key->toBe('is_admin')->and($property->value)
            ->name->toBe('is_admin')
            ->type->toBe('bool')
            ->dbType->toBe('boolean')
            ->nullable->toBe(false)
            ->default->toBe('0'),
        fn ($property, $key) => $key->toBe('balance')->and($property->value)
            ->name->toBe('balance')
            ->type->toBe('float')
            ->dbType->toBe('decimal')
            ->nullable->toBe(false)
            ->default->toBe('0'),
    )
    ->each->toBeInstanceOf(Property::class);

it('fails if a property type cannot be guessed from the DB', function () {
    $properties = new Properties(User::class);
    $typesMap = new ReflectionProperty($properties, 'typesMap');

    $typesMap->setAccessible(true);
    $typesMap->setValue($properties, []);
    $typesMap->setAccessible(false);

    $message = 'Unable to map the type \'integer\' of the property Cerbero\EloquentInspector\Models\User::$id';
    expect(fn () => $properties->get())->toThrow(UnknownType::class, $message);
});

it('inspects relationships')
    ->expect(fn () => $this->inspector->getRelationships())
    ->toHaveCount(4)
    ->sequence(
        fn ($property, $key) => $key->toBe('referrer')->and($property->value)
            ->name->toBe('referrer')
            ->type->toBe('belongsTo')
            ->class->toBe(BelongsTo::class)
            ->model->toBe(User::class)
            ->relatesToMany->toBe(false),
        fn ($property, $key) => $key->toBe('posts')->and($property->value)
            ->name->toBe('posts')
            ->type->toBe('hasMany')
            ->class->toBe(HasMany::class)
            ->model->toBe(Post::class)
            ->relatesToMany->toBe(true),
        fn ($property, $key) => $key->toBe('latestPost')->and($property->value)
            ->name->toBe('latestPost')
            ->type->toBe('hasOne')
            ->class->toBe(HasOne::class)
            ->model->toBe(Post::class)
            ->relatesToMany->toBe(false),
        fn ($property, $key) => $key->toBe('tags')->and($property->value)
            ->name->toBe('tags')
            ->type->toBe('belongsToMany')
            ->class->toBe(BelongsToMany::class)
            ->model->toBe(Tag::class)
            ->relatesToMany->toBe(true),
    )
    ->each->toBeInstanceOf(Relationship::class);
