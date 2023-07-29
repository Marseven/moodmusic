import {UserProfile} from '@app/web-player/user-profile/user-profile';
import {UserLink} from '@app/web-player/user-profile/user-link';
import {ProfileLinks} from '@app/web-player/user-profile/profile-links';

interface Props {
  profile?: UserProfile;
  links?: UserLink[];
  shortDescription?: boolean;
}
export function ProfileDescription({profile, links, shortDescription}: Props) {
  if (!profile) return null;
  return (
    <div className="text-sm">
      {profile.description && (
        <div
          className="p-10 rounded bg-alt/80 dark:bg text-secondary max-w-720"
          dangerouslySetInnerHTML={{
            __html: shortDescription
              ? profile.description.slice(0, 300)
              : profile.description,
          }}
        />
      )}
      {profile.city || profile.country || links?.length ? (
        <div className="flex items-center gap-24 justify-between mt-20">
          {(profile.city || profile.country) && (
            <div className="p-10 rounded bg-alt/80 dark:bg text-secondary w-max">
              {profile.city}
              {profile.city && ','} {profile.country}
            </div>
          )}
          <ProfileLinks links={links} />
        </div>
      ) : null}
    </div>
  );
}
