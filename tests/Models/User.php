<?php

namespace Cerbero\EloquentInspector\Models;

use Cerbero\EloquentInspector\Models\Nested\Tag;
use Illuminate\Database\Eloquent\Model;
use Foo\Bar as Baz;

/**
 * The user model.
 *
 */
class User extends Model
{
    /**
     * Relationship with the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the Post model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relationship with the Post model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestPost()
    {
        return $this->hasOne(\Cerbero\EloquentInspector\Models\Post::class)->latestOfMany();
    }

    /**
     * Relationship with the Tag model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Dummy method to test that non-relationships methods are skipped while collecting relationships
     *
     * @return void
     */
    public function notARelationship(): void
    {
        //
    }

    /**
     * Relationship with the User model through the Post model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function relationshipWithMissingModel()
    {
        return $this->hasManyThrough(MissingModel::class, Post::class);
    }
}
