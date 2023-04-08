import {User} from '@common/auth/user';
import {userFollowsStore} from '@app/web-player/users/user-follows-store';
import {UserImage} from '@app/web-player/users/user-image';
import {UserProfileLink} from '@app/web-player/users/user-profile-link';
import {Trans} from '@common/i18n/trans';
import React from 'react';
import {useFollowUser} from '@app/web-player/users/use-follow-user';
import {useAuth} from '@common/auth/use-auth';
import {Button} from '@common/ui/buttons/button';
import {useUnfollowUser} from '@app/web-player/users/use-unfollow-user';

interface Props {
  follower: User;
}
export function FollowerListItem({follower}: Props) {
  const isFollowing = userFollowsStore(s => s.isFollowing(follower.id));
  return (
    <div
      key={follower.id}
      className="flex items-center gap-16 mb-16 pb-16 border-b"
    >
      <UserImage user={follower} className="w-64 h-64 rounded" />
      <div className="text-sm">
        <UserProfileLink user={follower} />
        {follower.followers_count && follower.followers_count > 0 ? (
          <div className="text-xs text-muted">
            <Trans
              message="[one 1 followers|other :count followers]"
              values={{count: follower.followers_count}}
            />
          </div>
        ) : null}
      </div>
      {isFollowing ? (
        <UnfollowUserButton user={follower} />
      ) : (
        <FollowUserButton user={follower} />
      )}
    </div>
  );
}

interface FollowUserButtonProps {
  user: User;
}
function FollowUserButton({user}: FollowUserButtonProps) {
  const followUser = useFollowUser();
  const {user: currentUser} = useAuth();
  if (currentUser?.id === user.id) return null;
  return (
    <Button
      variant="outline"
      radius="rounded-full"
      className="flex-shrink-0 ml-auto"
      onClick={() => followUser.mutate({user})}
      disabled={followUser.isLoading}
    >
      <Trans message="Follow" />
    </Button>
  );
}

function UnfollowUserButton({user}: FollowUserButtonProps) {
  const unfollowUser = useUnfollowUser();
  const {user: currentUser} = useAuth();
  if (currentUser?.id === user.id) return null;
  return (
    <Button
      variant="outline"
      radius="rounded-full"
      className="flex-shrink-0 ml-auto"
      onClick={() => unfollowUser.mutate({user})}
      disabled={unfollowUser.isLoading}
    >
      <Trans message="Unfollow" />
    </Button>
  );
}
