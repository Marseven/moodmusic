import {icons} from 'lucide-react';
import {createLucideIcon} from '../create-lucide-icon';
import {ComponentType} from 'react';
import {SvgIconProps} from '../svg-icon';

// Dynamically wrap all Lucide icons for the Icon Picker
export const allIcons: Record<string, ComponentType<SvgIconProps>> = {};

for (const [name, icon] of Object.entries(icons)) {
  allIcons[`${name}Icon`] = createLucideIcon(icon, `${name}Icon`);
}
