import {LandingPageContent} from './landing-page-content';
import {Navbar} from '@common/ui/navigation/navbar/navbar';
import {Button, ButtonProps} from '@common/ui/buttons/button';
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
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {useLandingPageTrendingArtists} from '@app/landing-page/requests/use-landing-page-trending-artists';
import {SmallArtistImage} from '@app/web-player/artists/artist-image/small-artist-image';
import {getArtistLink} from '@app/web-player/artists/artist-link';
import {PlayableMediaGridSkeleton} from '@app/web-player/playable-item/player-media-grid-skeleton';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {
  ModernSmartphoneIcon,
  ModernSearchIcon,
  ModernDiscIcon,
  ModernSparklesIcon,
} from '@app/web-player/icons/modern-icons';
import {
  Play,
  SkipBack,
  SkipForward,
  Shuffle,
  Repeat,
  Volume2,
  Headphones,
  Zap,
  Crown,
  Music,
  Users,
  Globe,
  Download,
  Shield,
  Mic,
  Radio,
} from 'lucide-react';

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
      <div className="h-full overflow-y-auto scroll-smooth bg-[#0a0a0f]">
        <HeroSection content={appearance} />
        <StatsBar />
        <PlaylistShowcase content={appearance} />
        <PlayerPreview />
        {showTrending && <ArtistSpotlight />}
        <FeaturesGrid content={appearance} />
        {showPricing && <PricingSection content={appearance} />}
        <DownloadCta content={appearance} />
        <Footer className="landing-container" />
      </div>
    </Fragment>
  );
}

// ─── Hero Section ────────────────────────────────────────────
function HeroSection({content}: ContentProps) {
  const {trans} = useTrans();
  const navigate = useNavigate();
  const {
    headerTitle,
    headerSubtitle,
    actions,
  } = content;

  return (
    <header className="relative min-h-[100vh] overflow-hidden flex flex-col">
      {/* Animated gradient background */}
      <div className="absolute inset-0 bg-[#0a0a0f]">
        {/* Orb 1 - violet */}
        <div
          className="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full opacity-30"
          style={{
            background: 'radial-gradient(circle, #7351EA 0%, transparent 70%)',
            animation: 'heroOrb1 12s ease-in-out infinite',
          }}
        />
        {/* Orb 2 - pink */}
        <div
          className="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full opacity-25"
          style={{
            background: 'radial-gradient(circle, #FF6B9D 0%, transparent 70%)',
            animation: 'heroOrb2 15s ease-in-out infinite',
          }}
        />
        {/* Orb 3 - teal */}
        <div
          className="absolute top-[40%] right-[20%] w-[400px] h-[400px] rounded-full opacity-20"
          style={{
            background: 'radial-gradient(circle, #4ECDC4 0%, transparent 70%)',
            animation: 'heroOrb3 10s ease-in-out infinite',
          }}
        />
        {/* Grid overlay */}
        <div
          className="absolute inset-0 opacity-[0.03]"
          style={{
            backgroundImage:
              'linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px)',
            backgroundSize: '60px 60px',
          }}
        />
        {/* Noise texture */}
        <div
          className="absolute inset-0 opacity-[0.015]"
          style={{
            backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E")`,
          }}
        />
      </div>

      {/* Navbar */}
      <Navbar
        color="transparent"
        darkModeColor="transparent"
        className="flex-shrink-0 relative z-30"
        menuPosition="landing-page-navbar"
        primaryButtonColor="white"
      />

      {/* Hero content */}
      <div className="flex-auto flex flex-col items-center justify-center text-white relative z-20 px-16 md:px-24">
        <div className="max-w-850 mx-auto text-center">
          {/* Badge */}
          <div className="inline-flex items-center gap-8 px-16 py-6 rounded-full border border-white/10 bg-white/5 backdrop-blur-sm text-xs font-medium text-white/70 mb-32">
            <Zap size={14} className="text-[#7351EA]" />
            <span>La plateforme musicale gabonaise</span>
          </div>

          {headerTitle && (
            <h1 className="text-4xl sm:text-5xl md:text-7xl font-bold tracking-tight leading-[1.1]">
              <span className="block text-white">
                <Trans message={headerTitle} />
              </span>
              <span
                className="block mt-8"
                style={{
                  background: 'linear-gradient(135deg, #7351EA 0%, #FF6B9D 50%, #4ECDC4 100%)',
                  WebkitBackgroundClip: 'text',
                  WebkitTextFillColor: 'transparent',
                }}
              >
                Ta musique, ton mood.
              </span>
            </h1>
          )}

          {headerSubtitle && (
            <p className="mt-24 text-lg md:text-xl text-white/60 max-w-600 mx-auto leading-relaxed">
              <Trans message={headerSubtitle} />
            </p>
          )}

          {/* Search bar */}
          <form
            className="w-full max-w-560 mx-auto mt-40"
            onSubmit={e => {
              e.preventDefault();
              navigate(
                `search/${(e.currentTarget[0] as HTMLInputElement).value}`
              );
            }}
          >
            <div className="relative group">
              <div className="absolute -inset-[1px] rounded-full bg-gradient-to-r from-[#7351EA] via-[#FF6B9D] to-[#4ECDC4] opacity-50 group-hover:opacity-80 blur-sm transition-opacity duration-500" />
              <div className="relative flex items-center bg-[#141420] rounded-full border border-white/10">
                <ModernSearchIcon className="ml-20 text-white/40 icon-sm" />
                <input
                  type="text"
                  placeholder={trans(message(content.actions.inputText))}
                  className="w-full bg-transparent text-white placeholder-white/40 py-16 px-14 text-base outline-none"
                />
                <button
                  type="submit"
                  className="mr-8 px-24 py-10 bg-[#7351EA] hover:bg-[#8466f0] text-white text-sm font-semibold rounded-full transition-all duration-300 whitespace-nowrap"
                >
                  <Trans message="Rechercher" />
                </button>
              </div>
            </div>
          </form>

          {/* CTA buttons */}
          <div className="flex flex-wrap justify-center gap-16 mt-40">
            <CtaButton
              item={actions.cta1}
              variant="flat"
              size="lg"
              radius="rounded-full"
              className="min-w-180 !bg-[#7351EA] hover:!bg-[#8466f0] !text-white font-semibold shadow-[0_0_30px_rgba(115,81,234,0.4)] hover:shadow-[0_0_50px_rgba(115,81,234,0.6)] transition-all duration-300"
            />
            <CtaButton
              item={actions.cta2}
              variant="outline"
              color="white"
              size="lg"
              radius="rounded-full"
              className="border-white/20 hover:border-white/40 hover:bg-white/5 transition-all duration-300"
            />
            <Button
              elementType="a"
              href="/storage/downloads/moodmusic_app.apk"
              variant="outline"
              color="white"
              size="lg"
              radius="rounded-full"
              startIcon={<ModernSmartphoneIcon />}
              className="border-white/20 hover:border-white/40 hover:bg-white/5 transition-all duration-300"
            >
              <Trans message="Télécharger l'App" />
            </Button>
          </div>
        </div>

        {/* Scroll indicator */}
        <div className="mt-auto pb-32">
          <div
            className="w-6 h-10 border-2 border-white/20 rounded-full flex justify-center"
          >
            <div
              className="w-1.5 h-3 bg-white/50 rounded-full mt-1"
              style={{animation: 'scrollBounce 2s ease-in-out infinite'}}
            />
          </div>
        </div>
      </div>
    </header>
  );
}

// ─── Stats Bar ───────────────────────────────────────────────
function StatsBar() {
  const stats = [
    {value: '10K+', label: 'Titres', icon: Music},
    {value: '500+', label: 'Artistes', icon: Users},
    {value: '100%', label: 'Gabonais', icon: Globe},
    {value: '24/7', label: 'Streaming', icon: Headphones},
  ];

  return (
    <div className="relative border-y border-white/5 bg-white/[0.02]">
      <div className="landing-container py-32">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-24">
          {stats.map(({value, label, icon: Icon}) => (
            <div key={label} className="flex items-center justify-center gap-14">
              <div className="w-40 h-40 rounded-xl bg-[#7351EA]/10 flex items-center justify-center flex-shrink-0">
                <Icon size={20} className="text-[#7351EA]" />
              </div>
              <div>
                <div className="text-xl md:text-2xl font-bold text-white">{value}</div>
                <div className="text-xs text-white/50">{label}</div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

// ─── Playlist Showcase ───────────────────────────────────────
function PlaylistShowcase(_props: ContentProps) {
  const playlists = [
    {
      title: 'Ambiance Boma',
      subtitle: 'Les sons qui font vibrer Libreville',
      gradient: 'linear-gradient(135deg, #FF4500, #FF6B35)',
      icon: '🔥',
    },
    {
      title: 'Chill Gaboma',
      subtitle: 'Pour les moments de détente',
      gradient: 'linear-gradient(135deg, #4ECDC4, #2C9E8F)',
      icon: '🌊',
    },
    {
      title: 'Vibes Nocturnes',
      subtitle: "L'énergie de la nuit",
      gradient: 'linear-gradient(135deg, #7351EA, #9B7FF0)',
      icon: '🌙',
    },
    {
      title: 'Fiers d\'être Gaboma',
      subtitle: 'Les nouveautés du 241',
      gradient: 'linear-gradient(135deg, #00E676, #00C853)',
      icon: '🇬🇦',
    },
    {
      title: 'Mood Romantique',
      subtitle: 'Pour les cœurs qui battent',
      gradient: 'linear-gradient(135deg, #FF6B9D, #f093fb)',
      icon: '💜',
    },
    {
      title: 'Ça chauffe',
      subtitle: 'Les titres du moment',
      gradient: 'linear-gradient(135deg, #FFD700, #FFA000)',
      icon: '⚡',
    },
  ];

  return (
    <section className="py-80 md:py-128 relative overflow-hidden">
      {/* Subtle bg accent */}
      <div
        className="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] opacity-[0.06] pointer-events-none"
        style={{background: 'radial-gradient(ellipse, #7351EA 0%, transparent 70%)'}}
      />

      <div className="landing-container relative z-10">
        {/* Section header */}
        <div className="text-center mb-64">
          <div className="inline-flex items-center gap-8 px-14 py-6 rounded-full border border-[#7351EA]/20 bg-[#7351EA]/5 text-xs font-medium text-[#7351EA] mb-16">
            <ModernDiscIcon className="icon-xs" />
            <span>Playlists</span>
          </div>
          <h2 className="text-3xl md:text-5xl font-bold text-white tracking-tight">
            Ton mood, ta playlist
          </h2>
          <p className="mt-16 text-white/50 text-lg max-w-500 mx-auto">
            Des sélections pensées pour chaque moment de ta journée
          </p>
        </div>

        {/* Playlist grid */}
        <div className="grid grid-cols-2 md:grid-cols-3 gap-16 md:gap-20">
          {playlists.map((pl, i) => (
            <Link
              key={i}
              to="/register"
              className="group relative overflow-hidden rounded-2xl aspect-[4/3] cursor-pointer"
            >
              {/* Gradient bg */}
              <div
                className="absolute inset-0 transition-transform duration-500 group-hover:scale-110"
                style={{background: pl.gradient}}
              />
              {/* Glass overlay */}
              <div className="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors duration-300" />
              {/* Content */}
              <div className="relative h-full flex flex-col justify-between p-20 md:p-24">
                <span className="text-3xl md:text-4xl">{pl.icon}</span>
                <div>
                  <h3 className="text-base md:text-lg font-bold text-white leading-tight">{pl.title}</h3>
                  <p className="text-xs md:text-sm text-white/70 mt-4">{pl.subtitle}</p>
                </div>
              </div>
              {/* Play hint on hover */}
              <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 scale-75 group-hover:scale-100">
                <div className="w-48 h-48 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center">
                  <Play size={20} className="text-white ml-1" fill="white" />
                </div>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}

// ─── Player Preview ──────────────────────────────────────────
function PlayerPreview() {
  return (
    <section className="py-80 md:py-128 relative overflow-hidden">
      {/* Background accent */}
      <div
        className="absolute bottom-0 right-0 w-[600px] h-[600px] opacity-[0.05] pointer-events-none"
        style={{background: 'radial-gradient(circle, #FF6B9D 0%, transparent 70%)'}}
      />

      <div className="landing-container relative z-10">
        <div className="md:flex items-center gap-64 lg:gap-80">
          {/* Text side */}
          <div className="flex-1 mb-48 md:mb-0">
            <div className="inline-flex items-center gap-8 px-14 py-6 rounded-full border border-[#FF6B9D]/20 bg-[#FF6B9D]/5 text-xs font-medium text-[#FF6B9D] mb-16">
              <Headphones size={14} />
              <span>Player</span>
            </div>
            <h2 className="text-3xl md:text-5xl font-bold text-white tracking-tight">
              Une expérience
              <span className="block" style={{
                background: 'linear-gradient(135deg, #FF6B9D, #7351EA)',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent',
              }}>
                d'écoute unique
              </span>
            </h2>
            <p className="mt-20 text-white/50 text-lg leading-relaxed max-w-440">
              Un player conçu pour te plonger dans la musique.
              Qualité audio premium, paroles synchronisées, et une interface fluide.
            </p>

            {/* Feature pills */}
            <div className="flex flex-wrap gap-10 mt-32">
              {['Audio HD', 'Hors-ligne', 'Paroles', 'Sans pub'].map(f => (
                <span
                  key={f}
                  className="px-14 py-6 rounded-full bg-white/5 border border-white/10 text-sm text-white/60"
                >
                  {f}
                </span>
              ))}
            </div>
          </div>

          {/* Player mockup */}
          <div className="flex-1 max-w-480">
            <div className="mood-glass-panel p-0 overflow-hidden">
              {/* Album art area */}
              <div
                className="aspect-square relative"
                style={{
                  background: 'linear-gradient(135deg, #1a1028 0%, #0f1923 50%, #1a0f20 100%)',
                }}
              >
                {/* Decorative waveform */}
                <div className="absolute inset-0 flex items-center justify-center">
                  <div className="flex items-end gap-[3px] h-80">
                    {Array.from({length: 40}).map((_, i) => {
                      const h = Math.sin(i * 0.3) * 30 + Math.random() * 20 + 15;
                      return (
                        <div
                          key={i}
                          className="w-[3px] rounded-full"
                          style={{
                            height: `${h}%`,
                            background: `linear-gradient(to top, #7351EA, #FF6B9D)`,
                            opacity: 0.6 + Math.random() * 0.4,
                            animation: `waveBar ${1.5 + Math.random()}s ease-in-out infinite alternate`,
                            animationDelay: `${i * 0.05}s`,
                          }}
                        />
                      );
                    })}
                  </div>
                </div>

                {/* Center disc */}
                <div className="absolute inset-0 flex items-center justify-center">
                  <div
                    className="w-120 h-120 md:w-160 md:h-160 rounded-full border-4 border-white/10 flex items-center justify-center"
                    style={{
                      background: 'radial-gradient(circle at 30% 30%, rgba(115,81,234,0.3), rgba(0,0,0,0.5))',
                      animation: 'spinSlow 8s linear infinite',
                    }}
                  >
                    <div className="w-16 h-16 rounded-full bg-[#0a0a0f]" />
                  </div>
                </div>
              </div>

              {/* Controls area */}
              <div className="p-24 bg-[#0f0f18]/80 backdrop-blur-xl">
                {/* Track info */}
                <div className="mb-16">
                  <div className="text-white font-semibold text-base">Ambiance Nocturne</div>
                  <div className="text-white/50 text-sm">Fran-B feat. Lil Boo</div>
                </div>

                {/* Progress bar */}
                <div className="mb-16">
                  <div className="h-[3px] rounded-full bg-white/10 relative overflow-hidden">
                    <div
                      className="absolute left-0 top-0 h-full rounded-full"
                      style={{
                        width: '42%',
                        background: 'linear-gradient(90deg, #7351EA, #FF6B9D)',
                      }}
                    />
                  </div>
                  <div className="flex justify-between mt-6 text-[11px] text-white/40">
                    <span>1:28</span>
                    <span>3:42</span>
                  </div>
                </div>

                {/* Transport controls */}
                <div className="flex items-center justify-center gap-24">
                  <Shuffle size={16} className="text-white/40" />
                  <SkipBack size={18} className="text-white/70" />
                  <div className="w-48 h-48 rounded-full bg-[#7351EA] flex items-center justify-center shadow-[0_0_20px_rgba(115,81,234,0.4)]">
                    <Play size={20} className="text-white ml-1" fill="white" />
                  </div>
                  <SkipForward size={18} className="text-white/70" />
                  <Repeat size={16} className="text-white/40" />
                </div>

                {/* Volume */}
                <div className="flex items-center gap-10 mt-20 justify-center">
                  <Volume2 size={14} className="text-white/30" />
                  <div className="w-80 h-[3px] rounded-full bg-white/10 relative">
                    <div className="absolute left-0 top-0 h-full w-[70%] rounded-full bg-white/30" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

// ─── Artist Spotlight ────────────────────────────────────────
function ArtistSpotlight() {
  const {data, isLoading} = useLandingPageTrendingArtists();

  return (
    <section className="py-80 md:py-128 relative overflow-hidden">
      {/* Background */}
      <div
        className="absolute top-1/2 left-0 w-[500px] h-[500px] -translate-y-1/2 opacity-[0.05] pointer-events-none"
        style={{background: 'radial-gradient(circle, #4ECDC4 0%, transparent 70%)'}}
      />

      <div className="landing-container relative z-10">
        <div className="text-center mb-64">
          <div className="inline-flex items-center gap-8 px-14 py-6 rounded-full border border-[#4ECDC4]/20 bg-[#4ECDC4]/5 text-xs font-medium text-[#4ECDC4] mb-16">
            <Mic size={14} />
            <span>Artistes</span>
          </div>
          <h2 className="text-3xl md:text-5xl font-bold text-white tracking-tight">
            Les voix du Gabon
          </h2>
          <p className="mt-16 text-white/50 text-lg max-w-500 mx-auto">
            Découvre les artistes qui font vibrer la scène musicale gabonaise
          </p>
        </div>

        {isLoading ? (
          <PlayableMediaGridSkeleton
            itemCount={8}
            itemRadius="rounded-full"
            showDescription={false}
          />
        ) : (
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-24 md:gap-32">
            {data?.artists.map((artist, i) => (
              <Link
                key={artist.id}
                to={getArtistLink(artist)}
                className="group text-center"
              >
                <div className="relative mx-auto w-full aspect-square max-w-200 mb-16">
                  {/* Glow ring on hover */}
                  <div
                    className="absolute -inset-[3px] rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-md"
                    style={{
                      background: [
                        'linear-gradient(135deg, #7351EA, #FF6B9D)',
                        'linear-gradient(135deg, #4ECDC4, #7351EA)',
                        'linear-gradient(135deg, #FF6B9D, #FFD700)',
                        'linear-gradient(135deg, #00E676, #4ECDC4)',
                        'linear-gradient(135deg, #FF4500, #FF6B9D)',
                        'linear-gradient(135deg, #9C27B0, #7351EA)',
                        'linear-gradient(135deg, #FFD700, #FF4500)',
                        'linear-gradient(135deg, #7351EA, #4ECDC4)',
                      ][i % 8],
                    }}
                  />
                  <div className="relative rounded-full overflow-hidden border-2 border-white/10 group-hover:border-white/20 transition-colors duration-300">
                    <SmallArtistImage
                      artist={artist}
                      size="w-full h-full"
                      className="rounded-full group-hover:scale-105 transition-transform duration-500"
                    />
                  </div>
                </div>
                <div className="text-sm font-medium text-white/80 group-hover:text-white transition-colors duration-300">
                  {artist.name}
                </div>
                {artist.genres?.[0] && (
                  <div className="text-xs text-white/40 mt-2">{artist.genres[0].display_name || artist.genres[0].name}</div>
                )}
              </Link>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}

// ─── Features Grid ───────────────────────────────────────────
function FeaturesGrid(_props: ContentProps) {
  const features = [
    {
      icon: Music,
      title: 'Catalogue 100% Gabonais',
      description: 'Des milliers de titres d\'artistes gabonais, des classiques aux dernières sorties.',
      color: '#7351EA',
    },
    {
      icon: Radio,
      title: 'Radio & Mix Live',
      description: 'Des DJ sets en direct et des radios thématiques pour ne jamais manquer un beat.',
      color: '#FF6B9D',
    },
    {
      icon: Download,
      title: 'Écoute hors-ligne',
      description: 'Télécharge tes morceaux favoris et écoute-les partout, même sans connexion.',
      color: '#4ECDC4',
    },
    {
      icon: Shield,
      title: 'Audio haute qualité',
      description: 'Un son cristallin pour une immersion totale dans la musique.',
      color: '#FFD700',
    },
    {
      icon: Users,
      title: 'Communauté',
      description: 'Partage tes playlists, suis tes artistes préférés et connecte-toi avec d\'autres fans.',
      color: '#FF4500',
    },
    {
      icon: Crown,
      title: 'Soutiens tes artistes',
      description: 'Donne la force à tes artistes favoris en achetant leurs sons directement.',
      color: '#00E676',
    },
  ];

  return (
    <section className="py-80 md:py-128 relative">
      <div className="landing-container">
        <div className="text-center mb-64">
          <div className="inline-flex items-center gap-8 px-14 py-6 rounded-full border border-[#FFD700]/20 bg-[#FFD700]/5 text-xs font-medium text-[#FFD700] mb-16">
            <ModernSparklesIcon className="icon-xs" />
            <span>Fonctionnalités</span>
          </div>
          <h2 className="text-3xl md:text-5xl font-bold text-white tracking-tight">
            Tout ce qu'il te faut
          </h2>
          <p className="mt-16 text-white/50 text-lg max-w-500 mx-auto">
            Une plateforme complète pour vivre la musique gabonaise
          </p>
        </div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-16 md:gap-20">
          {features.map(({icon: Icon, title, description, color}) => (
            <div
              key={title}
              className="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02] p-28 hover:bg-white/[0.04] hover:border-white/[0.1] transition-all duration-400"
            >
              {/* Subtle top glow on hover */}
              <div
                className="absolute top-0 left-1/2 -translate-x-1/2 w-[60%] h-[1px] opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                style={{background: `linear-gradient(90deg, transparent, ${color}, transparent)`}}
              />

              <div
                className="w-44 h-44 rounded-xl flex items-center justify-center mb-20"
                style={{background: `${color}15`}}
              >
                <Icon size={22} style={{color}} />
              </div>
              <h3 className="text-lg font-semibold text-white mb-10">{title}</h3>
              <p className="text-sm text-white/45 leading-relaxed">{description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

// ─── Pricing ─────────────────────────────────────────────────
function PricingSection({content}: ContentProps) {
  const query = useProducts();
  const [selectedCycle, setSelectedCycle] =
    useState<UpsellBillingCycle>('yearly');

  return (
    <section className="py-80 md:py-128 relative" id="pricing">
      {/* Background accent */}
      <div
        className="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] opacity-[0.04] pointer-events-none"
        style={{background: 'radial-gradient(ellipse, #7351EA 0%, transparent 70%)'}}
      />

      <div className="landing-container relative z-10">
        <div className="text-center mb-48">
          <div className="inline-flex items-center gap-8 px-14 py-6 rounded-full border border-[#7351EA]/20 bg-[#7351EA]/5 text-xs font-medium text-[#7351EA] mb-16">
            <Crown size={14} />
            <span>Premium</span>
          </div>
          {content.pricingTitle ? (
            <h2 className="text-3xl md:text-5xl font-bold text-white tracking-tight">
              <Trans message={content.pricingTitle} />
            </h2>
          ) : (
            <h2 className="text-3xl md:text-5xl font-bold text-white tracking-tight">
              Passe au niveau supérieur
            </h2>
          )}
          {content.pricingSubtitle && (
            <p className="mt-16 text-lg text-white/50">
              <Trans message={content.pricingSubtitle} />
            </p>
          )}
        </div>

        <BillingCycleRadio
          products={query.data?.products}
          selectedCycle={selectedCycle}
          onChange={setSelectedCycle}
          className="my-40 flex justify-center"
          size="lg"
        />
        <PricingTable selectedCycle={selectedCycle} />
      </div>
    </section>
  );
}

// ─── Download CTA ────────────────────────────────────────────
function DownloadCta({content}: ContentProps) {
  return (
    <section className="py-80 md:py-128 relative overflow-hidden">
      {/* Background gradient orbs */}
      <div className="absolute inset-0">
        <div
          className="absolute top-0 left-1/4 w-[500px] h-[500px] opacity-[0.08]"
          style={{background: 'radial-gradient(circle, #7351EA 0%, transparent 70%)'}}
        />
        <div
          className="absolute bottom-0 right-1/4 w-[400px] h-[400px] opacity-[0.06]"
          style={{background: 'radial-gradient(circle, #FF6B9D 0%, transparent 70%)'}}
        />
      </div>

      <div className="landing-container relative z-10">
        <div className="text-center max-w-640 mx-auto">
          <h2 className="text-3xl md:text-5xl font-bold text-white tracking-tight">
            <Trans message={content.footerTitle || "Prêt à vibrer ?"} />
          </h2>
          {content.footerSubtitle && (
            <p className="mt-16 text-lg text-white/50">
              <Trans message={content.footerSubtitle} />
            </p>
          )}

          <div className="flex flex-wrap justify-center gap-16 mt-40">
            <CtaButton
              item={content.actions.cta3}
              size="lg"
              radius="rounded-full"
              variant="flat"
              className="!bg-[#7351EA] hover:!bg-[#8466f0] !text-white font-semibold shadow-[0_0_30px_rgba(115,81,234,0.4)] hover:shadow-[0_0_50px_rgba(115,81,234,0.6)] transition-all duration-300"
            />
            <Button
              elementType="a"
              href="/storage/downloads/moodmusic_app.apk"
              variant="outline"
              color="white"
              size="lg"
              radius="rounded-full"
              startIcon={<ModernSmartphoneIcon />}
              className="border-white/20 hover:border-white/40 hover:bg-white/5 transition-all duration-300"
            >
              <Trans message="Télécharger l'App" />
            </Button>
          </div>
        </div>
      </div>
    </section>
  );
}

// ─── CTA Button helper ───────────────────────────────────────
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
