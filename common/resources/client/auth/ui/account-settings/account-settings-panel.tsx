import {ReactNode} from 'react';

interface Props {
  id: string;
  title: ReactNode;
  titleSuffix?: ReactNode;
  children: ReactNode;
  actions?: ReactNode;
  variant?: 'default' | 'danger';
}
export function AccountSettingsPanel({
  id,
  title,
  titleSuffix,
  children,
  actions,
  variant = 'default',
}: Props) {
  return (
    <section
      id={id}
      className={`mood-glass-panel px-24 py-20 mb-24 w-full ${
        variant === 'danger'
          ? 'border-danger/30 hover:border-danger/50'
          : ''
      }`}
    >
      <div className="border-b border-white/10 pb-10 flex items-center gap-14">
        <div className="text-lg font-semibold tracking-tight">{title}</div>
        {titleSuffix && <div className="ml-auto">{titleSuffix}</div>}
      </div>
      <div className="pt-24">{children}</div>
      {actions && (
        <div className="pt-10 mt-36 border-t border-white/10 flex justify-end">
          {actions}
        </div>
      )}
    </section>
  );
}
