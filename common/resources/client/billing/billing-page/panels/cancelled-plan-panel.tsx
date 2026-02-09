import {useBillingUser} from '../use-billing-user';
import {FormattedDate} from '../../../i18n/formatted-date';
import {BillingPlanPanel} from '../billing-plan-panel';
import {Trans} from '../../../i18n/trans';
import {Chip} from '../../../ui/forms/input-field/chip-field/chip';
import {FormattedPrice} from '../../../i18n/formatted-price';
import {CalendarTodayIcon} from '../../../icons/material/CalendarToday';
import {Button} from '../../../ui/buttons/button';
import {Link} from 'react-router-dom';

export function CancelledPlanPanel() {
  const {subscription} = useBillingUser();
  if (!subscription?.price || !subscription?.product) return null;

  const endingDate = (
    <span className="whitespace-nowrap">
      <FormattedDate preset="long" date={subscription.ends_at} />
    </span>
  );

  return (
    <BillingPlanPanel title={<Trans message="Plan actuel" />}>
      <div className="flex flex-col md:flex-row gap-20 md:justify-between mt-24">
        <div className="min-w-0">
          <Chip
            className="w-min mb-10"
            size="xs"
            radius="rounded"
            color="danger"
          >
            <Trans message="Annulé" />
          </Chip>
          <div className="text-xl font-bold mb-2">
            {subscription.product.name}
          </div>
          <FormattedPrice className="text-xl mb-8" price={subscription.price} />
          <div className="text-base flex items-center gap-8">
            <CalendarTodayIcon size="sm" className="text-muted flex-shrink-0" />
            <Trans
              message="Votre plan sera annulé le :date"
              values={{date: endingDate}}
            />
          </div>
        </div>
        <div className="w-full md:w-[233px] flex-shrink-0">
          <Button
            variant="flat"
            color="primary"
            size="md"
            className="w-full mb-12"
            elementType={Link}
            to="/billing/renew"
          >
            <Trans message="Renouveler le plan" />
          </Button>
        </div>
      </div>
    </BillingPlanPanel>
  );
}
