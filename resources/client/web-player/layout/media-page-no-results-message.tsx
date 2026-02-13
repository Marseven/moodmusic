import {Trans} from '@common/i18n/trans';
import React, {ReactElement} from 'react';
import {MoodEmptyState} from '@app/web-player/mood-empty-state';

interface MediaPageNoResultsMessage {
  description: ReactElement;
  searchQuery?: string;
  className?: string;
}
export function MediaPageNoResultsMessage({
  description,
  searchQuery,
  className,
}: MediaPageNoResultsMessage) {
  if (searchQuery) {
    return (
      <MoodEmptyState
        className={className}
        title={<Trans message="No results found" />}
        description={
          <Trans message="Try another search query or different filters" />
        }
      />
    );
  }
  return (
    <MoodEmptyState
      className={className}
      title={<Trans message="Nothing to display" />}
      description={description}
    />
  );
}
