import {ReactNode} from 'react';

interface BillingPlanPanelProps {
  title: ReactNode;
  children: ReactNode;
}
export function BillingPlanPanel({title, children}: BillingPlanPanelProps) {
  return (
    <div className="mood-glass-panel p-28 mb-24">
      <div className="text-sm font-medium uppercase pb-16 mb-16 border-b border-white/10">
        {title}
      </div>
      {children}
    </div>
  );
}
