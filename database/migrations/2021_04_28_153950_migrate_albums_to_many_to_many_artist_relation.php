<?php

use App\Album;
use App\Artist;
use App\Services\Providers\SaveOrUpdate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;

class MigrateAlbumsToManyToManyArtistRelation extends Migration
{
    use SaveOrUpdate;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasColumn('albums', 'artist_type')) return;

        Album::where('artist_type', Artist::class)->chunkById(500, function(Collection $albums) {
            $records = $albums->map(function(Album $album) {
                return [
                    'artist_id' => $album->artist_id,
                    'album_id' => $album->id,
                    'primary' => true,
                ];
            });
            $this->saveOrUpdate($records->toArray(), 'artist_album');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       //
    }
}
