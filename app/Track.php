<?php namespace App;

use App\Traits\OrdersByPopularity;
use Common\Comments\Comment;
use Common\Search\Searchable;
use Common\Tags\Tag;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Track extends Model
{
    use OrdersByPopularity, Searchable, HasFactory;

    const MODEL_TYPE = 'track';

    protected $guarded = ['id', 'formatted_duration', 'plays', 'lyric'];

    protected $hidden = [
        'fully_scraped',
        'temp_id',
        'pivot',
        'artists_legacy',
        'album_name',
        'album_id',
        'auto_update',
        'local_only',
        'spotify_id',
        'updated_at',
        'user_id',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'album_id' => 'integer',
        'number' => 'integer',
        'spotify_popularity' => 'integer',
        'duration' => 'integer',
        'auto_update' => 'boolean',
        'position' => 'integer',
        'added_at' => 'datetime',
    ];

    protected $appends = ['model_type'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!EnsureFrontendRequestsAreStateful::fromFrontend(request())) {
            $this->hidden[] = 'url';
            $this->hidden[] = 'youtube_id';
            $this->hidden[] = 'spotify_popularity';
        }
    }

    public function likes(): BelongsToMany
    {
        return $this->morphToMany(
            User::class,
            'likeable',
            'likes',
        )->withTimestamps();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy(
            'created_at',
            'desc',
        );
    }

    public function reposts(): MorphMany
    {
        return $this->morphMany(Repost::class, 'repostable');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class)->select([
            'artists.id',
            'artists.name',
            'artists.image_small',
        ]);
    }

    public function plays(): HasMany
    {
        return $this->hasMany(TrackPlay::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->select(
            'tags.name',
            'tags.display_name',
            'tags.id',
        );
    }

    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable')->select(
            'genres.name',
            'genres.display_name',
            'genres.id',
            'genres.image',
        );
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class)->withPivot('position');
    }

    public function lyric(): HasOne
    {
        return $this->hasOne(Lyric::class);
    }

    public function getWaveStorageDisk(): Filesystem
    {
        return Storage::disk(config('common.site.wave_storage_disk'));
    }

    public function toNormalizedArray(): array
    {
        $image = $this->image;
        if (!$image && $this->relationLoaded('album')) {
            $image = $this->album?->image;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $image,
            'description' => $this->relationLoaded('artists')
                ? $this->artists->pluck('name')->implode(', ')
                : null,
            'model_type' => self::MODEL_TYPE,
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'album_name' => $this->album_name,
            'spotify_id' => $this->spotify_id,
            'artists' => $this->artists->pluck('name'),
        ];
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with(['artists']);
    }

    public static function filterableFields(): array
    {
        return ['id', 'spotify_id'];
    }

    public static function getModelTypeAttribute(): string
    {
        return Track::MODEL_TYPE;
    }
}
