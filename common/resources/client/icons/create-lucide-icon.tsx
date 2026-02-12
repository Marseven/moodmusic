import React, {ComponentType, RefObject} from 'react';
import {LucideIcon} from 'lucide-react';
import clsx from 'clsx';
import {IconSize, SvgIconProps} from './svg-icon';

function getSizeClassName(size?: IconSize) {
  switch (size) {
    case '2xs':
      return 'icon-2xs';
    case 'xs':
      return 'icon-xs';
    case 'sm':
      return 'icon-sm';
    case 'md':
      return 'icon-md';
    case 'lg':
      return 'icon-lg';
    case 'xl':
      return 'icon-xl';
    default:
      return size;
  }
}

export function createLucideIcon(
  Icon: LucideIcon,
  displayName: string = ''
): ComponentType<SvgIconProps> {
  const Component = (props: SvgIconProps, ref: RefObject<SVGSVGElement>) => {
    const {size, color, className, title, style, ...rest} = props;

    return (
      <Icon
        ref={ref}
        aria-hidden={!title}
        className={clsx('svg-icon', className, getSizeClassName(size || 'md'))}
        style={{color, ...style}}
        width="1em"
        height="1em"
        strokeWidth={1.5}
        {...rest}
      >
        {title && <title>{title}</title>}
      </Icon>
    );
  };

  if (process.env.NODE_ENV !== 'production') {
    Component.displayName = displayName;
  }

  return React.memo(React.forwardRef(Component as any));
}
