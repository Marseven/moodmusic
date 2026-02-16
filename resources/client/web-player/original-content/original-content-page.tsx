import React, {Fragment} from 'react';
import {useParams} from 'react-router-dom';
import {Trans} from '@common/i18n/trans';
import {StaticPageTitle} from '@common/seo/static-page-title';
import {AdHost} from '@common/admin/ads/ad-host';
import {useOriginalContent} from './use-original-content';
import {TrackTable} from '@app/web-player/tracks/track-table/track-table';
import {PageErrorMessage} from '@common/errors/page-error-message';
import {MediaPageNoResultsMessage} from '@app/web-player/layout/media-page-no-results-message';
import {PlaybackToggleButton} from '@app/web-player/playable-item/playback-toggle-button';

export function OriginalContentPage() {
  const {categoryName} = useParams<{categoryName: string}>();
  const {data, isLoading, isError} = useOriginalContent(categoryName!);

  if (isError) {
    return <PageErrorMessage />;
  }

  const tracks = data?.pagination?.data || [];
  const category = data?.category;
  const queueId = `original-content.${categoryName}`;

  return (
    <Fragment>
      <StaticPageTitle>
        <Trans
          message=":name - Création Originale"
          values={{name: category?.display_name || categoryName}}
        />
      </StaticPageTitle>
      <AdHost slot="general_top" className="mb-34" />
      <div className="flex flex-wrap items-center gap-24 justify-between mb-34">
        <h1 className="text-2xl font-semibold whitespace-nowrap">
          {category?.display_name || categoryName}
        </h1>
        {tracks.length > 0 && (
          <PlaybackToggleButton
            queueId={queueId}
            buttonType="text"
            className="min-w-128 flex-shrink-0"
          />
        )}
      </div>
      {isLoading ? (
        <div className="space-y-8">
          {[...Array(8)].map((_, i) => (
            <div
              key={i}
              className="h-48 rounded-lg animate-pulse bg-fg-base/4"
            />
          ))}
        </div>
      ) : tracks.length > 0 ? (
        <TrackTable
          queueGroupId={queueId}
          tracks={tracks}
        />
      ) : (
        <MediaPageNoResultsMessage
          className="mt-34"
          description={
            <Trans message="Aucun contenu dans cette catégorie pour le moment." />
          }
        />
      )}
      <AdHost slot="general_bottom" className="mt-34" />
    </Fragment>
  );
}
