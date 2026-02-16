import React, {Fragment} from 'react';
import {Link} from 'react-router-dom';
import {Trans} from '@common/i18n/trans';
import {StaticPageTitle} from '@common/seo/static-page-title';
import {AdHost} from '@common/admin/ads/ad-host';
import {useOriginalContentCategories} from './use-original-content';
import {ModernDiscIcon, ModernAudioLinesIcon, ModernMusicNoteIcon} from '@app/web-player/icons/modern-icons';
import {PageErrorMessage} from '@common/errors/page-error-message';

const iconMap: Record<string, React.ReactElement> = {
  'disc-3': <ModernDiscIcon size="lg" />,
  'audio-lines': <ModernAudioLinesIcon size="lg" />,
};

export function OriginalContentCategoriesPage() {
  const {data, isLoading, isError} = useOriginalContentCategories();

  if (isError) {
    return <PageErrorMessage />;
  }

  return (
    <Fragment>
      <StaticPageTitle>
        <Trans message="Création Originale" />
      </StaticPageTitle>
      <AdHost slot="general_top" className="mb-34" />
      <h1 className="text-2xl font-semibold mb-34">
        <Trans message="Création Originale" />
      </h1>
      {isLoading ? (
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-16">
          {[1, 2].map(i => (
            <div
              key={i}
              className="mood-glass-panel rounded-xl h-160 animate-pulse"
            />
          ))}
        </div>
      ) : (
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-16">
          {data?.categories.map(category => (
            <Link
              key={category.id}
              to={`/original/${category.name}`}
              className="mood-glass-panel mood-glass-interactive rounded-xl p-24 flex flex-col items-center justify-center gap-12 text-center min-h-160 group"
            >
              <div className="text-primary transition-transform group-hover:scale-110">
                {iconMap[category.icon || ''] || <ModernMusicNoteIcon size="lg" />}
              </div>
              <span className="text-lg font-semibold">
                {category.display_name}
              </span>
              {category.description && (
                <span className="text-sm text-muted">
                  {category.description}
                </span>
              )}
            </Link>
          ))}
        </div>
      )}
      <AdHost slot="general_bottom" className="mt-34" />
    </Fragment>
  );
}
