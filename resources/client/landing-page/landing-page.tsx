import clsx from 'clsx';
import {LandingPageContent} from './landing-page-content';
import {Navbar} from '@common/ui/navigation/navbar/navbar';
import {Button, ButtonProps} from '@common/ui/buttons/button';
import {MixedImage} from '@common/ui/images/mixed-image';
import {Footer} from '@common/ui/footer/footer';
import {Trans} from '@common/i18n/trans';
import {Link} from 'react-router-dom';
import {createSvgIconFromTree} from '@common/icons/create-svg-icon';
import {MenuItemConfig} from '@common/core/settings/settings';
import {Fragment, useState} from 'react';
import {DefaultMetaTags} from '@common/seo/default-meta-tags';
import {useSettings} from '@common/core/settings/use-settings';
import {PricingTable} from '@common/billing/pricing-table/pricing-table';
import {BillingCycleRadio} from '@common/billing/pricing-table/billing-cycle-radio';
import {UpsellBillingCycle} from '@common/billing/pricing-table/find-best-price';
import {useProducts} from '@common/billing/pricing-table/use-products';
import {TextField} from '@common/ui/forms/input-field/text-field/text-field';
import {SearchIcon} from '@common/icons/material/Search';
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {useLandingPageTrendingArtists} from '@app/landing-page/requests/use-landing-page-trending-artists';
import {ContentGrid} from '@app/web-player/playable-item/content-grid';
import {SmallArtistImage} from '@app/web-player/artists/artist-image/small-artist-image';
import {ArtistLink, getArtistLink} from '@app/web-player/artists/artist-link';
import {PlayableMediaGridSkeleton} from '@app/web-player/playable-item/player-media-grid-skeleton';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {useLightThemeVariables} from '@common/ui/themes/use-light-theme-variables';

interface ContentProps {
  content: LandingPageContent;
}
export function LandingPage() {
  const settings = useSettings();
  const appearance = settings.homepage.appearance;
  const showPricing = settings.homepage?.pricing && settings.billing.enable;
  const showTrending = settings.homepage?.trending;

  return (
    <Fragment>
      <DefaultMetaTags />
      <div className="h-full overflow-y-auto scroll-smooth">
        <HeroHeader content={appearance} />
        <PrimaryFeatures content={appearance} />
        <SecondaryFeatures content={appearance} />
        {showTrending && <TrendingArtistsSection />}
        <BottomCta content={appearance} />
        {showPricing && <PricingSection content={appearance} />}
        <Footer className="landing-container" />
      </div>
    </Fragment>
  );
}

function HeroHeader({content}: ContentProps) {
  const lightThemeVars = useLightThemeVariables();
  const {trans} = useTrans();
  const navigate = useNavigate();
  const {
    headerTitle,
    headerSubtitle,
    headerImage,
    headerImageOpacity,
    actions,
    headerOverlayColor1,
    headerOverlayColor2,
  } = content;
  let overlayBackground = undefined;

  if (headerOverlayColor1 && headerOverlayColor2) {
    overlayBackground = `linear-gradient(45deg, ${headerOverlayColor1} 0%, ${headerOverlayColor2} 100%)`;
  } else if (headerOverlayColor1) {
    overlayBackground = headerOverlayColor1;
  } else if (headerOverlayColor2) {
    overlayBackground = headerOverlayColor2;
  }

  return (
    <header className="relative mb-14 md:mb-60 overflow-hidden isolate">
      <img
        data-testid="headerImage"
        src={headerImage}
        style={{
          opacity: headerImageOpacity,
        }}
        alt=""
        width="2347"
        height="1244"
        decoding="async"
        loading="lazy"
        className="absolute top-1/2 left-1/2 max-w-none -translate-x-1/2 -translate-y-1/2 z-20"
      />
      <div
        className="absolute w-full h-full z-10 bg-[rgb(37,99,235)]"
        style={{background: overlayBackground}}
      />
      <div className="flex flex-col relative h-full z-30">
        <Navbar
          color="transparent"
          darkModeColor="transparent"
          className="flex-shrink-0"
          menuPosition="landing-page-navbar"
          primaryButtonColor="white"
        />
        <div className="flex-auto flex flex-col items-center justify-center text-white max-w-850 mx-auto text-center px-14 py-50 lg:py-90">
          {headerTitle && (
            <h1
              className="text-3xl md:text-5xl font-normal"
              data-testid="headerTitle"
            >
              <Trans message={headerTitle} />
            </h1>
          )}
          {headerSubtitle && (
            <div
              className="max-auto max-w-640 text-lg tracking-tight md:text-xl mt-24"
              data-testid="headerSubtitle"
            >
              <Trans message={headerSubtitle} />
            </div>
          )}
          <form
            style={lightThemeVars}
            className="w-full mt-60 md:mt-80"
            onSubmit={e => {
              e.preventDefault();
              navigate(
                `search/${(e.currentTarget[0] as HTMLInputElement).value}`
              );
            }}
          >
            <TextField
              background="bg-white"
              inputRadius="rounded-full"
              size="lg"
              placeholder={trans(message(content.actions.inputText))}
              startAdornment={<SearchIcon />}
              adornmentPosition="left-10"
            />
          </form>
          <div className="flex gap-20 pt-70 md:pt-90 pb-30 md:pb-50 min-h-50 empty:min-h-0">
            <CtaButton
              item={actions.cta1}
              variant="raised"
              color="white"
              size="lg"
              radius="rounded-full"
              data-testid="cta1"
              className="min-w-180"
            />
            <CtaButton
              item={actions.cta2}
              variant="text"
              color="paper"
              size="lg"
              radius="rounded-full"
              data-testid="cta2"
            />
          </div>
        </div>
      </div>
      <div className="w-full absolute bottom-0 transform translate-y-1/2 -skew-y-3 h-[6vw] z-20 bg"></div>
    </header>
  );
}

interface CtaButtonProps extends ButtonProps {
  item?: MenuItemConfig;
}
function CtaButton({item, ...buttonProps}: CtaButtonProps) {
  if (!item?.label || !item?.action) return null;
  const Icon = item.icon ? createSvgIconFromTree(item.icon) : undefined;
  return (
    <Button
      elementType={item.type === 'route' ? Link : 'a'}
      href={item.action}
      to={item.action}
      startIcon={Icon ? <Icon /> : undefined}
      {...buttonProps}
    >
      <Trans message={item.label} />
    </Button>
  );
}

function PrimaryFeatures({content}: ContentProps) {
  if (!content.primaryFeatures?.length) {
    return null;
  }
  return (
    <Fragment>
      <div
        className="md:flex items-stretch gap-26 landing-container z-20"
        id="primary-features"
      >
        {content.primaryFeatures.map((feature, index) => (
          <div
            key={index}
            className="flex-1 px-24 py-36 rounded-2xl mb-14 md:mb-0 shadow-[0_10px_30px_rgba(0,0,0,0.08)] text-center dark:bg-alt"
            data-testid={`primary-root-${index}`}
          >
            <MixedImage
              className="h-128 mx-auto mb-30"
              data-testid={`primary-image-${index}`}
              src={feature.image}
            />
            <h2
              className="my-16 text-lg font-medium"
              data-testid={`primary-title-${index}`}
            >
              <Trans message={feature.title} />
            </h2>
            <div
              className="text-md text-[0.938rem]"
              data-testid={`primary-subtitle-${index}`}
            >
              <Trans message={feature.subtitle} />
            </div>
          </div>
        ))}
      </div>
      <div className="h-1 bg-divider mt-100" />
    </Fragment>
  );
}

function SecondaryFeatures({content}: ContentProps) {
  return (
    <div
      className={clsx(
        'relative overflow-hidden',
        content.primaryFeatures?.length && 'pt-100'
      )}
    >
      <div className="landing-container relative" id="features">
        {content.secondaryFeatures.map((feature, index) => {
          const isEven = index % 2 === 0;
          return (
            <div
              key={index}
              data-testid={`secondary-root-${index}`}
              className={clsx(
                'md:flex py-16 mb-14 md:mb-80 z-20 relative',
                isEven && 'flex-row-reverse'
              )}
            >
              <img
                src={feature.image}
                className="rounded-lg max-w-full mr-auto shadow-[0_10px_30px_rgba(0,0,0,0.08)] w-580"
                data-testid={`secondary-image-${index}`}
                alt=""
              />
              <div className="ml-30 mr-auto max-w-350 pt-30">
                <small
                  className="uppercase mb-16 tracking-widest font-medium text-xs text-muted"
                  data-testid={`secondary-subtitle-${index}`}
                >
                  <Trans message={feature.subtitle} />
                </small>
                <h3
                  className="py-16 text-3xl"
                  data-testid={`secondary-title-${index}`}
                >
                  <Trans message={feature.title} />
                </h3>
                <div className="w-50 h-2 bg-black/90 dark:bg-divider" />
                <div
                  className="my-20 text-[0.938rem]"
                  data-testid={`secondary-description-${index}`}
                >
                  <Trans message={feature.description} />
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

function TrendingArtistsSection() {
  const {data, isLoading} = useLandingPageTrendingArtists();
  const {branding} = useSettings();

  return (
    <Fragment>
      <div className="h-1 bg-divider" />
      <div className="landing-container pt-80 pb-128 @container">
        <h2 className="text-3xl tracking-tight sm:text-4xl text-center mb-64">
          <Trans
            message="Currently trending artists on :site"
            values={{site: branding.site_name}}
          />
        </h2>

        {isLoading ? (
          <PlayableMediaGridSkeleton
            itemCount={8}
            itemRadius="rounded-full"
            showDescription={false}
          />
        ) : (
          <ContentGrid>
            {data?.artists.map(artist => (
              <div key={artist.id}>
                <Link
                  to={getArtistLink(artist)}
                  className="block aspect-square"
                >
                  <SmallArtistImage
                    artist={artist}
                    size="w-full h-full"
                    className="rounded-full"
                  />
                </Link>
                <div className="text-center text-sm mt-12 overflow-hidden overflow-ellipsis">
                  <ArtistLink artist={artist} />
                </div>
              </div>
            ))}
          </ContentGrid>
        )}
      </div>
    </Fragment>
  );
}

interface PricingSectionProps {
  content: LandingPageContent;
}
function PricingSection({content}: PricingSectionProps) {
  const query = useProducts();
  const [selectedCycle, setSelectedCycle] =
    useState<UpsellBillingCycle>('yearly');

  return (
    <div className="py-80 sm:py-128" id="pricing">
      <div className="mx-auto max-w-1280 px-24 lg:px-32">
        <div className="md:text-center">
          {content.pricingTitle && (
            <h2
              className="font-display text-3xl tracking-tight sm:text-4xl"
              data-testid="pricingTitle"
            >
              <Trans message={content.pricingTitle} />
            </h2>
          )}
          {content.pricingSubtitle && (
            <p
              className="mt-16 text-lg text-muted"
              data-testid="pricingSubtitle"
            >
              <Trans message={content.pricingSubtitle} />
            </p>
          )}
        </div>
        <BillingCycleRadio
          products={query.data?.products}
          selectedCycle={selectedCycle}
          onChange={setSelectedCycle}
          className="my-50 flex justify-center"
          size="lg"
        />
        <PricingTable selectedCycle={selectedCycle} />
      </div>
    </div>
  );
}

function BottomCta({content}: ContentProps) {
  return (
    <div
      className="relative py-90 md:py-128 overflow-hidden text-white bg-black before:absolute before:inset-0 before:bg-gradient-to-r before:from-black before:to-transparent before:z-10 before:pointer-events-none"
      data-testid="footerImage"
    >
      <img
        draggable={false}
        src={content.footerImage}
        alt=""
        width="2347"
        height="1244"
        decoding="async"
        loading="lazy"
        className="absolute top-1/2 left-1/2 max-w-none -translate-x-1/2 -translate-y-1/2"
      />
      <div className="relative mx-auto max-w-1280 px-24 sm:px-16 lg:px-32 text-center z-20">
        <div className="mx-auto max-w-512 text-center">
          <h2
            className=" font-display text-3xl tracking-tight sm:text-4xl"
            data-testid="footerTitle"
          >
            <Trans message={content.footerTitle} />
          </h2>
          {content.footerSubtitle && (
            <p
              className="mt-16 text-lg tracking-tight"
              data-testid="footerSubtitle"
            >
              <Trans message={content.footerSubtitle} />
            </p>
          )}
          <CtaButton
            item={content.actions.cta3}
            size="lg"
            radius="rounded-full"
            variant="flat"
            color="white"
            className="block mt-40"
            data-testid="cta3"
          />
        </div>
      </div>
    </div>
  );
}
