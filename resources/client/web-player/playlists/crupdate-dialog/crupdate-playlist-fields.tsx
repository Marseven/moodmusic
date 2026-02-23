import {FormTextField} from '@common/ui/forms/input-field/text-field/text-field';
import {Trans} from '@common/i18n/trans';
import {FileUploadProvider} from '@common/uploads/uploader/file-upload-provider';
import {FormSwitch} from '@common/ui/forms/toggle/switch';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {Fragment} from 'react';
import {FormImageSelector} from '@common/ui/images/image-selector';
import {ModernImageIcon} from '@app/web-player/icons/modern-icons';

export function CrupdatePlaylistFields() {
  const {trans} = useTrans();
  return (
    <Fragment>
      <div className="md:flex gap-28">
        <FileUploadProvider>
          <FormImageSelector
            name="image"
            diskPrefix="playlist_media"
            variant="square"
            previewSize="w-160 h-160"
            className="mb-24 md:mb-0"
            placeholderIcon={<ModernImageIcon />}
            showRemoveButton
            stretchPreview
          />
        </FileUploadProvider>
        <div className="flex-auto mb-34">
          <FormTextField
            autoFocus
            name="name"
            label={<Trans message="Nom" />}
            className="mb-24"
          />
          <FormSwitch
            name="collaborative"
            description={<Trans message="Invite d'autres utilisateurs à ajouter des titres." />}
            className="mb-24"
          >
            <Trans message="Collaborative" />
          </FormSwitch>
          <FormSwitch
            name="public"
            description={<Trans message="Tout le monde peut voir les playlists publiques." />}
          >
            <Trans message="Publique" />
          </FormSwitch>
        </div>
      </div>
      <FormTextField
        name="description"
        label={<Trans message="Description" />}
        inputElementType="textarea"
        rows={4}
        placeholder={trans(message('Donne une description accrocheuse à ta playlist.'))}
      />
    </Fragment>
  );
}
