import {Genre} from '@app/web-player/genres/genre';
import {GenreImage} from '@app/web-player/genres/genre-image';
import {Link} from 'react-router-dom';
import {getGenreLink} from '@app/web-player/genres/genre-link';

interface GenreGridItemProps {
  genre: Genre;
}
export function GenreGridItem({genre}: GenreGridItemProps) {
  return (
    <Link
      to={getGenreLink(genre)}
      className="block relative h-max after:bg-black/50 after:top-0 after:left-0 after:w-full after:h-full after:absolute rounded overflow-hidden cursor-pointer isolate"
    >
      <GenreImage genre={genre} className="shadow-md w-full aspect-square" />
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-xl font-semibold capitalize text-white whitespace-nowrap overflow-hidden overflow-ellipsis max-w-[86%] z-20">
        {genre.display_name || genre.name}
      </div>
    </Link>
  );
}
