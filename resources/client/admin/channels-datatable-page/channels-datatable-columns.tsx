import {ColumnConfig} from '@common/datatable/column-config';
import {Trans} from '@common/i18n/trans';
import {FormattedDate} from '@common/i18n/formatted-date';
import {Link} from 'react-router-dom';
import {IconButton} from '@common/ui/buttons/icon-button';
import {EditIcon} from '@common/icons/material/Edit';
import React from 'react';
import {Channel} from '@app/web-player/channels/channel';

export const ChannelsDatatableColumns: ColumnConfig<Channel>[] = [
  {
    key: 'name',
    allowsSorting: true,
    header: () => <Trans message="Name" />,
    // prevent long names from overflowing the table
    width: 'w-5/6 max-w-200',
    body: channel => (
      <a
        className="hover:underline focus-visible:underline outline-none"
        href={`channel/${channel.slug}`}
        target="_blank"
        rel="noreferrer"
      >
        {channel.name}
      </a>
    ),
  },
  {
    key: 'content_type',
    allowsSorting: false,
    header: () => <Trans message="Content type" />,
    body: channel => (
      <span className="capitalize">{channel.config.contentModel}</span>
    ),
  },
  {
    key: 'layout',
    allowsSorting: false,
    header: () => <Trans message="Layout" />,
    body: channel => (
      <span className="capitalize">{channel.config.layout}</span>
    ),
  },
  {
    key: 'auto_update',
    allowsSorting: false,
    header: () => <Trans message="Auto update" />,
    body: channel => (
      <span className="capitalize">{channel.config.autoUpdateMethod}</span>
    ),
  },
  {
    key: 'updated_at',
    allowsSorting: true,
    header: () => <Trans message="Last updated" />,
    body: channel =>
      channel.updated_at ? <FormattedDate date={channel.updated_at} /> : '',
  },
  {
    key: 'actions',
    header: () => <Trans message="Actions" />,
    hideHeader: true,
    align: 'end',
    body: channel => (
      <Link to={`${channel.id}/edit`} className="text-muted">
        <IconButton size="md">
          <EditIcon />
        </IconButton>
      </Link>
    ),
  },
];
