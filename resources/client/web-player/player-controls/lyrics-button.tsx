import {useSettings} from '@common/core/settings/use-settings';
import {useCuedTrack} from '@app/web-player/player-controls/use-cued-track';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {IconButton} from '@common/ui/buttons/icon-button';
import {LyricsDialog} from '@app/web-player/tracks/lyrics/lyrics-dialog';
import {MediaMicrophoneIcon} from '@common/icons/media/media-microphone';
import {Tooltip} from '@common/ui/tooltip/tooltip';
import {Trans} from '@common/i18n/trans';

export function LyricsButton() {
  const {player} = useSettings();
  const track = useCuedTrack();

  if (!track || player?.hide_lyrics) {
    return null;
  }

  return (
    <DialogTrigger type="modal">
      <Tooltip label={<Trans message="Lyrics" />}>
        <IconButton>
          <MediaMicrophoneIcon />
        </IconButton>
      </Tooltip>
      <LyricsDialog track={track} />
    </DialogTrigger>
  );
}
