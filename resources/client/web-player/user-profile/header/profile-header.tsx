import {useAuth} from '@common/auth/use-auth';
import {
  actionButtonClassName,
  MediaPageHeaderLayout,
} from '@app/web-player/layout/media-page-header-layout';
import {UserImage} from '@app/web-player/users/user-image';
import {BulletSeparatedItems} from '@app/web-player/layout/bullet-separated-items';
import {Link} from 'react-router-dom';
import {Trans} from '@common/i18n/trans';
import {ProfileDescription} from '@app/web-player/user-profile/profile-description';
import React from 'react';
import {User} from '@common/auth/user';
import {userFollowsStore} from '@app/web-player/users/user-follows-store';
import {useFollowUser} from '@app/web-player/users/use-follow-user';
import {useUnfollowUser} from '@app/web-player/users/use-unfollow-user';
import {Button} from '@common/ui/buttons/button';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {EditIcon} from '@common/icons/material/Edit';
import {EditProfileDialog} from '@app/web-player/user-profile/edit-profile-dialog';

interface ProfileHeaderProps {
  user: User;
  tabLink: (tabName: string) => string;
}

export function ProfileHeader({user, tabLink}: ProfileHeaderProps) {
  const {user: currentUser} = useAuth();

  return (
    <MediaPageHeaderLayout
      image={
        <UserImage
          user={user}
          size="w-240 h-240"
          className="rounded"
          showProBadge
        />
      }
      title={user.display_name}
      subtitle={
        <BulletSeparatedItems className="text-sm text-muted z-20 w-max mx-auto">
          {user.followers_count && user.followers_count > 0 ? (
            <Link to={tabLink('followers')} className="hover:underline">
              <Trans
                message=":count followers"
                values={{count: user.followers_count}}
              />
            </Link>
          ) : null}
          {user.followed_users_count && user.followed_users_count > 0 ? (
            <Link to={tabLink('following')} className="hover:underline">
              <Trans
                message="Following :count"
                values={{count: user.followed_users_count}}
              />
            </Link>
          ) : null}
        </BulletSeparatedItems>
      }
      actionButtons={
        <div className="flex items-center justify-center md:justify-start">
          <FollowButton user={user} />
          {currentUser?.id === user.id && <EditButton user={user} />}
        </div>
      }
      footer={<ProfileDescription profile={user.profile} links={user.links} />}
    />
  );
}

interface EditButtonProps {
  user: User;
}

function EditButton({user}: EditButtonProps) {
  return (
    <DialogTrigger type="modal">
      <Button
        variant="outline"
        radius="rounded-full"
        startIcon={<EditIcon />}
        className={actionButtonClassName()}
      >
        <Trans message="Edit" />
      </Button>
      <EditProfileDialog user={user} />
    </DialogTrigger>
  );
}

function FollowButton({user}: EditButtonProps) {
  const {user: currentUser} = useAuth();
  const isFollowing = userFollowsStore(s => s.isFollowing(user.id));
  const followUser = useFollowUser();
  const unfollowUser = useUnfollowUser();

  if (isFollowing) {
    return (
      <Button
        variant="flat"
        color="primary"
        className={actionButtonClassName({isFirst: true})}
        radius="rounded-full"
        onClick={() => unfollowUser.mutate({user})}
        disabled={currentUser?.id === user.id || unfollowUser.isLoading}
      >
        <Trans message="Unfollow" />
      </Button>
    );
  }

  return (
    <Button
      variant="flat"
      color="primary"
      className={actionButtonClassName({isFirst: true})}
      radius="rounded-full"
      onClick={() => followUser.mutate({user})}
      disabled={currentUser?.id === user.id || followUser.isLoading}
    >
      <Trans message="Follow" />
    </Button>
  );
}
