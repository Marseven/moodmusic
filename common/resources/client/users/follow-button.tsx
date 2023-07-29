import {Button, ButtonProps} from '@common/ui/buttons/button';
import {useAuth} from '@common/auth/use-auth';
import {User} from '@common/auth/user';
import {useIsUserFollowing} from '@common/users/queries/use-followed-users';
import {useFollowUser} from '@common/users/queries/use-follow-user';
import {useUnfollowUser} from '@common/users/queries/use-unfollow-user';
import {Trans} from '@common/i18n/trans';
import clsx from 'clsx';

interface Props extends Omit<ButtonProps, 'onClick' | 'disabled'> {
  user: User;
}
export function FollowButton({user, className, ...buttonProps}: Props) {
  const {user: currentUser} = useAuth();
  const {isFollowing, isLoading} = useIsUserFollowing(user);
  const followUser = useFollowUser();
  const unfollowUser = useUnfollowUser();

  const mergedClassName = clsx(className, 'min-w-82');

  if (isFollowing) {
    return (
      <Button
        {...buttonProps}
        className={mergedClassName}
        onClick={() => unfollowUser.mutate({user})}
        disabled={
          !currentUser ||
          currentUser?.id === user.id ||
          unfollowUser.isLoading ||
          isLoading
        }
      >
        <Trans message="Unfollow" />
      </Button>
    );
  }

  return (
    <Button
      {...buttonProps}
      className={mergedClassName}
      onClick={() => followUser.mutate({user})}
      disabled={
        !currentUser ||
        currentUser?.id === user.id ||
        followUser.isLoading ||
        isLoading
      }
    >
      <Trans message="Follow" />
    </Button>
  );
}
