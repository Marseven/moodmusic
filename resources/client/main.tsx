import './app.css';
import React from 'react';
import {BrowserRouter, Route, Routes} from 'react-router-dom';
import {createRoot} from 'react-dom/client';
import {AppearanceListener} from '@common/admin/appearance/commands/appearance-listener';
import {CommonProvider} from '@common/core/common-provider';
import {AuthRoutes} from '@common/auth/auth-routes';
import {AuthRoute} from '@common/auth/guards/auth-route';
import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {BillingRoutes} from '@common/billing/billing-routes';
import {NotificationRoutes} from '@common/notifications/notification-routes';
import {CookieNotice} from '@common/ui/cookie-notice/cookie-notice';
import {ContactUsPage} from '@common/contact/contact-us-page';
import {CustomPageLayout} from '@common/custom-page/custom-page-layout';
import {ToastContainer} from '@common/ui/toast/toast-container';
import {useAuth} from '@common/auth/use-auth';
import {EmailVerificationPage} from '@common/auth/ui/email-verification-page/email-verification-page';
import * as Sentry from '@sentry/react';
import {BrowserTracing} from '@sentry/tracing';
import {rootEl} from '@common/core/root-el';
import {useSettings} from '@common/core/settings/use-settings';
import {getBootstrapData} from '@common/core/bootstrap-data/use-backend-bootstrap-data';
import {NotFoundPage} from '@common/ui/not-found-page/not-found-page';
import {Playlist} from '@app/web-player/playlists/playlist';
import {Track, TRACK_MODEL} from '@app/web-player/tracks/track';
import {ALBUM_MODEL} from '@app/web-player/albums/album';
import {ARTIST_MODEL} from '@app/web-player/artists/artist';
import {Repost} from '@app/web-player/reposts/repost';
import {UserProfile} from '@app/web-player/user-profile/user-profile';
import {UserLink} from '@app/web-player/user-profile/user-link';
import {UserArtist} from '@app/web-player/user-profile/user-artist';
import {DynamicHomepage} from '@common/ui/dynamic-homepage';
import {LandingPage} from '@app/landing-page/landing-page';
import {LandingPageContent} from '@app/landing-page/landing-page-content';
import {useAppearanceEditorMode} from '@common/admin/appearance/commands/use-appearance-editor-mode';
import {ignoredSentryErrors} from '@common/errors/ignored-sentry-errors';
import {DialogStoreOutlet} from '@common/ui/overlays/store/dialog-store-outlet';

const AdminRoutes = React.lazy(() => import('@common/admin/admin-routes'));
const WebPlayerRoutes = React.lazy(
  () => import('@app/web-player/web-player-routes')
);
const BackstageRoutes = React.lazy(
  () => import('@app/web-player/backstage/backstage-routes')
);
const SwaggerApiDocs = React.lazy(
  () => import('@common/swagger/swagger-api-docs-page')
);

declare module '@common/core/settings/settings' {
  interface Settings {
    spotify_is_setup?: boolean;
    lastfm_is_setup?: boolean;
    artist_provider?: string;
    album_provider?: string;
    search_provider?: string;
    artist_bio_provider?: string;
    player?: {
      show_upload_btn?: boolean;
      default_volume?: number;
      hide_video_button?: boolean;
      hide_radio_button?: boolean;
      track_comments?: boolean;
      seekbar_type?: 'waveform' | 'bar';
      enable_repost?: boolean;
      hide_queue?: boolean;
      hide_video?: boolean;
      hide_lyrics?: boolean;
      enable_download?: boolean;
      show_become_artist_btn?: boolean;
      default_artist_view?: 'list' | 'grid';
      mobile?: {
        auto_open_overlay?: boolean;
      };
    };
    artistPage: {
      tabs: {id: number; active: boolean}[];
      showDescription?: boolean;
    };
    youtube?: {
      suggested_quality?: YT.SuggestedVideoQuality;
      search_method?: string;
    };
    homepage: {
      type: string;
      value?: string;
      pricing?: boolean;
      appearance: LandingPageContent;
      trending?: boolean;
    };
    ads?: {
      general_top?: string;
      general_bottom?: string;
      artist_top?: string;
      artist_bottom?: string;
      album_above?: string;
      disable?: boolean;
    };
  }
}

declare module '@common/auth/user' {
  interface User {
    uploaded_tracks: Track[];
    followed_users?: this[];
    followers_count?: number;
    followed_users_count?: number;
    followers?: this[];
    playlists: Playlist[];
    reposts?: Repost[];
    profile?: UserProfile;
    links?: UserLink[];
    artists?: UserArtist[];
  }
}

declare module '@common/core/bootstrap-data/bootstrap-data' {
  interface BootstrapData {
    playlists?: Playlist[];
    artists: {
      id: number;
      name: string;
      image_small?: string;
      role: string;
    }[];
    likes?: {
      [TRACK_MODEL]: Record<number, boolean>;
      [ALBUM_MODEL]: Record<number, boolean>;
      [ARTIST_MODEL]: Record<number, boolean>;
    };
    reposts?: {
      [TRACK_MODEL]: Record<number, boolean>;
      [ALBUM_MODEL]: Record<number, boolean>;
    };
  }
}

const sentryDsn = getBootstrapData().settings.logging.sentry_public;
const version = getBootstrapData().settings.version;
if (sentryDsn && import.meta.env.PROD) {
  Sentry.init({
    dsn: sentryDsn,
    integrations: [new BrowserTracing()],
    tracesSampleRate: 1.0,
    ignoreErrors: ignoredSentryErrors,
    release: version,
  });
}

const root = createRoot(rootEl);
root.render(
  <CommonProvider>
    <Router />
  </CommonProvider>
);

function Router() {
  const {
    billing,
    notifications,
    require_email_confirmation,
    api,
    html_base_uri,
    homepage,
  } = useSettings();
  const {isAppearanceEditorActive} = useAppearanceEditorMode();
  const {user, hasPermission} = useAuth();

  if (user != null && require_email_confirmation && !user.email_verified_at) {
    return (
      <BrowserRouter>
        <ToastContainer />
        <Routes>
          <Route path="*" element={<EmailVerificationPage />} />
        </Routes>
        <DialogStoreOutlet />
      </BrowserRouter>
    );
  }

  return (
    <BrowserRouter basename={html_base_uri}>
      <AppearanceListener />
      <CookieNotice />
      <ToastContainer />
      <Routes>
        <Route
          path="/*"
          element={
            <AuthRoute requireLogin={false} permission="music.view">
              <React.Suspense fallback={<FullPageLoader />}>
                <WebPlayerRoutes />
              </React.Suspense>
            </AuthRoute>
          }
        />

        <Route
          path="backstage/*"
          element={
            <AuthRoute>
              <React.Suspense fallback={<FullPageLoader />}>
                <BackstageRoutes />
              </React.Suspense>
            </AuthRoute>
          }
        />
        {!homepage?.type.startsWith('channel') &&
          (user == null || isAppearanceEditorActive) && (
            <Route
              path="/"
              element={
                <DynamicHomepage homepageResolver={() => <LandingPage />} />
              }
            />
          )}
        <Route
          path="/admin/*"
          element={
            <AuthRoute permission="admin.access">
              <React.Suspense fallback={<FullPageLoader />}>
                <AdminRoutes />
              </React.Suspense>
            </AuthRoute>
          }
        />
        {AuthRoutes}
        {billing.enable && BillingRoutes}
        {notifications.integrated && NotificationRoutes}
        {api?.integrated && hasPermission('api.access') && (
          <Route
            path="api-docs"
            element={
              <React.Suspense fallback={<FullPageLoader />}>
                <SwaggerApiDocs />
              </React.Suspense>
            }
          />
        )}
        <Route path="contact" element={<ContactUsPage />} />
        <Route path="pages/:pageSlug" element={<CustomPageLayout />} />
        <Route path="pages/:pageId/:pageSlug" element={<CustomPageLayout />} />
        <Route path="*" element={<NotFoundPage />} />
      </Routes>
      <DialogStoreOutlet />
    </BrowserRouter>
  );
}
