import {useForm} from 'react-hook-form';
import React from 'react';
import {CrupdateResourceLayout} from '@common/admin/crupdate-resource-layout';
import {Trans} from '@common/i18n/trans';
import {useAlbum} from '@app/web-player/albums/requests/use-album';
import {Album} from '@app/web-player/albums/album';
import {
  UpdateAlbumPayload,
  useUpdateAlbum,
} from '@app/admin/albums-datatable-page/requests/use-update-album';
import {AlbumForm} from '@app/admin/albums-datatable-page/album-form/album-form';
import {PageStatus} from '@common/http/page-status';
import {FileUploadProvider} from '@common/uploads/uploader/file-upload-provider';

interface Props {
  wrapInContainer?: boolean;
}
export function UpdateAlbumPage({wrapInContainer}: Props) {
  const query = useAlbum({
    forEditing: true,
    with: 'tags,genres,artists,fullTracks',
  });

  if (query.data) {
    return (
      <PageContent album={query.data.album} wrapInContainer={wrapInContainer} />
    );
  }

  return <PageStatus query={query} loaderClassName="absolute inset-0 m-auto" />;
}

interface PageContentProps {
  album: Album;
  wrapInContainer?: boolean;
}
function PageContent({album, wrapInContainer}: PageContentProps) {
  const form = useForm<UpdateAlbumPayload>({
    defaultValues: {
      ...album,
    },
  });
  const updateAlbum = useUpdateAlbum(form);

  return (
    <CrupdateResourceLayout
      form={form}
      onSubmit={values => {
        updateAlbum.mutate(values);
      }}
      title={<Trans message="Edit “:name“ album" values={{name: album.name}} />}
      isLoading={updateAlbum.isLoading}
      disableSaveWhenNotDirty
      wrapInContainer={wrapInContainer}
    >
      <FileUploadProvider>
        <AlbumForm showExternalIdFields />
      </FileUploadProvider>
    </CrupdateResourceLayout>
  );
}
