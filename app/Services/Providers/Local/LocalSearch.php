<?php namespace App\Services\Providers\Local;

use App\Album;
use App\Artist;
use App\Channel;
use App\Genre;
use App\Playlist;
use App\Services\Search\SearchInterface;
use App\Tag;
use App\Track;
use App\User;
use Illuminate\Support\Collection;

class LocalSearch implements SearchInterface
{
    protected string $query;
    protected int $limit;

    public function search(string $q, int $limit, array $modelTypes): Collection
    {
        $this->query = urldecode($q);
        $this->limit = $limit ?: 10;

        $results = collect();

        foreach ($modelTypes as $modelType) {
            if ($modelType === Artist::MODEL_TYPE) {
                $results['artists'] = $this->artists();
            } elseif ($modelType === Album::MODEL_TYPE) {
                $results['albums'] = $this->albums();
            } elseif ($modelType === Track::MODEL_TYPE) {
                $results['tracks'] = $this->tracks();
            } elseif ($modelType === Playlist::MODEL_TYPE) {
                $results['playlists'] = $this->playlists();
            } elseif ($modelType === Channel::MODEL_TYPE) {
                $results['channels'] = $this->channels();
            } elseif ($modelType === Genre::MODEL_TYPE) {
                $results['genres'] = $this->genres();
            } elseif ($modelType === Tag::MODEL_TYPE) {
                $results['tags'] = $this->tags();
            } elseif ($modelType === User::MODEL_TYPE) {
                $results['users'] = $this->users();
            }
        }

        return $results;
    }

    public function artists(): Collection
    {
        return Artist::search($this->query)
            ->take($this->limit)
            ->get()
            ->values();
    }

    public function albums(): Collection
    {
        return Album::search($this->query)
            ->take($this->limit)
            ->get()
            ->load('artists')
            ->values();
    }

    public function tracks(): Collection
    {
        return Track::search($this->query)
            ->take($this->limit)
            ->get()
            ->load(['album', 'artists'])
            ->values();
    }

    public function playlists(): Collection
    {
        return Playlist::search($this->query)
            ->take($this->limit)
            ->get()
            ->load(['editors'])
            ->values();
    }

    public function channels(): Collection
    {
        return app(Channel::class)
            ->search($this->query)
            ->take($this->limit)
            ->get()
            ->values();
    }

    public function genres(): Collection
    {
        return app(Genre::class)
            ->search($this->query)
            ->take($this->limit)
            ->get()
            ->values();
    }

    public function tags(): Collection
    {
        return app(Tag::class)
            ->search($this->query)
            ->take($this->limit)
            ->get()
            ->values();
    }

    public function users(): Collection
    {
        return app(User::class)
            ->search($this->query)
            ->take($this->limit)
            ->get()
            ->loadCount('followers')
            ->values();
    }
}
