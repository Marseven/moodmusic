import {useBillingUser} from '../use-billing-user';
import {BillingPlanPanel} from '../billing-plan-panel';
import {Trans} from '../../../i18n/trans';
import {useInvoices} from '../requests/use-invoices';
import {FormattedDate} from '../../../i18n/formatted-date';
import {FormattedCurrency} from '../../../i18n/formatted-currency';
import {Chip} from '../../../ui/forms/input-field/chip-field/chip';
import {OpenInNewIcon} from '../../../icons/material/OpenInNew';
import {Skeleton} from '../../../ui/skeleton/skeleton';
import {AnimatePresence, m} from 'framer-motion';
import {Invoice} from '../../invoice';
import {opacityAnimation} from '../../../ui/animation/opacity-animation';
import {useSettings} from '../../../core/settings/use-settings';

export function InvoiceHistoryPanel() {
  const {user} = useBillingUser();
  const query = useInvoices(user?.id!);
  if (!user) return null;

  const invoices = query.data?.invoices;

  return (
    <BillingPlanPanel title={<Trans message="Historique des paiements" />}>
      <div className="max-w-full md:max-w-[464px]">
        <AnimatePresence initial={false} mode="wait">
          {query.isLoading ? (
            <LoadingSkeleton key="loading-skeleton" />
          ) : (
            <InvoiceList key="invoices" invoices={invoices} />
          )}
        </AnimatePresence>
      </div>
    </BillingPlanPanel>
  );
}

interface InvoiceListProps {
  invoices?: Invoice[];
}
function InvoiceList({invoices}: InvoiceListProps) {
  const {base_url} = useSettings();
  return (
    <m.div {...opacityAnimation}>
      {!invoices?.length ? (
        <div className="text-muted italic">
          <Trans message="Aucune facture pour l'instant" />
        </div>
      ) : undefined}
      {invoices?.map(invoice => (
        <div
          className="text-sm md:text-base flex flex-wrap md:flex-nowrap items-center justify-between gap-6 md:gap-10 mb-14"
          key={invoice.id}
        >
          <a
            href={`${base_url}/billing/invoices/${invoice.uuid}`}
            target="_blank"
            className="flex items-center gap-8 hover:underline"
            rel="noreferrer"
          >
            <FormattedDate date={invoice.created_at} />
            <OpenInNewIcon size="xs" />
          </a>
          {invoice.subscription.price && (
            <div>
              <FormattedCurrency
                value={invoice.subscription.price.amount}
                currency={invoice.subscription.price.currency}
              />
            </div>
          )}
          <Chip
            size="xs"
            color={invoice.paid ? 'positive' : 'danger'}
            radius="rounded"
          >
            {invoice.paid ? (
              <Trans message="Payé" />
            ) : (
              <Trans message="Non payé" />
            )}
          </Chip>
          <div>{invoice.subscription.product?.name}</div>
        </div>
      ))}
    </m.div>
  );
}

function LoadingSkeleton() {
  return (
    <m.div {...opacityAnimation}>
      <Skeleton className="mb-14" />
      <Skeleton className="mb-14" />
      <Skeleton className="mb-14" />
      <Skeleton className="mb-14" />
      <Skeleton />
    </m.div>
  );
}
