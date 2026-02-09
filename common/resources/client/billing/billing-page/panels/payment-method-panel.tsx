import {useBillingUser} from '../use-billing-user';
import {BillingPlanPanel} from '../billing-plan-panel';
import {Trans} from '../../../i18n/trans';
import {Link} from 'react-router-dom';
import {EditIcon} from '../../../icons/material/Edit';
import {Fragment} from 'react';
import paypalSvg from './paypal.svg';
import {SvgImage} from '../../../ui/images/svg-image/svg-image';

export function PaymentMethodPanel() {
  const {user, subscription} = useBillingUser();
  if (!user || !subscription) return null;

  const methodClassName = "whitespace-nowrap text-base max-w-[464px] flex items-center gap-10";
  const linkClassName = "flex items-center gap-4 text-muted mt-18 block hover:underline";

  let PaymentMethod;
  if (subscription.gateway_name === 'ebilling') {
    PaymentMethod = EbillingPaymentMethod;
  } else if (subscription.gateway_name === 'paypal') {
    PaymentMethod = PaypalPaymentMethod;
  } else {
    PaymentMethod = CardPaymentMethod;
  }

  return (
    <BillingPlanPanel title={<Trans message="Méthode de paiement" />}>
      <PaymentMethod
        methodClassName={methodClassName}
        linkClassName={linkClassName}
      />
    </BillingPlanPanel>
  );
}

interface PaymentMethodProps {
  methodClassName: string;
  linkClassName: string;
}
function CardPaymentMethod({
  methodClassName,
  linkClassName,
}: PaymentMethodProps) {
  const {user} = useBillingUser();
  if (!user) return null;
  return (
    <Fragment>
      <div className={methodClassName}>
        <span className="capitalize">{user.card_brand}</span> ••••
        {user.card_last_four}
        {user.card_expires && (
          <div className="ml-auto">
            <Trans message="Expire le :date" values={{date: user.card_expires}} />
          </div>
        )}
      </div>
      <Link className={linkClassName} to="/billing/change-payment-method">
        <EditIcon size="sm" />
        <Trans message="Changer de méthode de paiement" />
      </Link>
    </Fragment>
  );
}

function EbillingPaymentMethod({
  methodClassName,
}: PaymentMethodProps) {
  return (
    <div className={methodClassName}>
      <Trans message="eBilling (Mobile Money)" />
    </div>
  );
}

function PaypalPaymentMethod({
  methodClassName,
  linkClassName,
}: PaymentMethodProps) {
  const {subscription} = useBillingUser();
  return (
    <Fragment>
      <div className={methodClassName}>
        <SvgImage src={paypalSvg} />
      </div>
      <a
        className={linkClassName}
        href={`https://www.sandbox.paypal.com/myaccount/autopay/connect/${subscription?.gateway_id}/funding`}
        target="_blank"
        rel="noreferrer"
      >
        <EditIcon size="sm" />
        <Trans message="Changer de méthode de paiement" />
      </a>
    </Fragment>
  );
}
