import {useSettings} from '@common/core/settings/use-settings';
import {ChannelPage} from '@app/web-player/channels/channel-page';
import {useAuth} from '@common/auth/use-auth';
import React from 'react';

export function HomepageChannelPage() {
  const {homepage} = useSettings();
  const {user} = useAuth();
  let slugOrId = 'discover';
  if (homepage.type.startsWith('channel') && homepage.value) {
    slugOrId = homepage.value;
  }

  const firstName = user?.display_name?.split(' ')[0];

  return (
    <div>
      {user && (
        <h2 className="text-2xl font-semibold mb-16 px-8">
          On dit quoi, {firstName} ?
        </h2>
      )}
      <ChannelPage slugOrId={slugOrId} />
    </div>
  );
}
