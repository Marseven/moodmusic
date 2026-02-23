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
        title={<Trans message="Aucun résultat trouvé" />}
        description={
          <Trans message="Essaie une autre recherche ou d'autres filtres" />
        }
      />
    );
  }
  return (
    <MoodEmptyState
      className={className}
      title={<Trans message="Rien à afficher" />}
      description={description}
    />
  );
}
