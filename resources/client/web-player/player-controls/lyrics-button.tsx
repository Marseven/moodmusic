import {useSettings} from '@common/core/settings/use-settings';
import {useCuedTrack} from '@app/web-player/player-controls/use-cued-track';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {IconButton} from '@common/ui/buttons/icon-button';
import {MicIcon} from '@common/icons/material/Mic';
import {LyricsDialog} from '@app/web-player/tracks/lyrics/lyrics-dialog';

export function LyricsButton() {
  const {player} = useSettings();
  const track = useCuedTrack();

  if (!track || player?.hide_lyrics) {
    return null;
  }

  return (
    <DialogTrigger type="modal">
      <IconButton>
        <MicIcon />
      </IconButton>
      <LyricsDialog track={track} />
    </DialogTrigger>
  );
}
