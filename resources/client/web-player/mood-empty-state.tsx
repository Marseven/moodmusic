import {ComponentType, ReactNode} from 'react';
import {SvgIconProps} from '@common/icons/svg-icon';
import {Trans} from '@common/i18n/trans';
import {Music} from 'lucide-react';

interface MoodEmptyStateProps {
  icon?: ComponentType<SvgIconProps>;
  title: ReactNode;
  description?: ReactNode;
  action?: ReactNode;
  className?: string;
}

export function MoodEmptyState({
  icon: Icon,
  title,
  description,
  action,
  className,
}: MoodEmptyStateProps) {
  return (
    <div className={`mood-empty-state ${className || ''}`}>
      <div className="mood-empty-state-icon">
        {Icon ? (
          <Icon size="xl" />
        ) : (
          <Music className="w-full h-full" strokeWidth={1.2} />
        )}
      </div>
      <div className="mood-empty-state-title">{title}</div>
      {description && (
        <div className="mood-empty-state-description">{description}</div>
      )}
      {action && <div className="mt-20">{action}</div>}
    </div>
  );
}
