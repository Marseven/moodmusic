import {Navigate, useParams} from 'react-router-dom';
import {Trans} from '../../i18n/trans';
import {CheckoutLayout} from './checkout-layout';
import {CheckoutProductSummary} from './checkout-product-summary';
import {usePaypal} from './paypal/use-paypal';
import {StripeElementsForm} from './stripe/stripe-elements-form';
import {Fragment} from 'react';
import {useProducts} from '../pricing-table/use-products';
import {FullPageLoader} from '../../ui/progress/full-page-loader';
import {useSettings} from '../../core/settings/use-settings';
import {EbillingButton} from './ebilling/ebilling-button';

export function Checkout() {
  const {productId, priceId} = useParams();
  const productQuery = useProducts();
  const {paypalElementRef} = usePaypal({
    productId,
    priceId,
  });
  const {
    base_url,
    billing: {stripe, ebilling, paypal},
  } = useSettings();

  if (productQuery.isLoading) {
    return <FullPageLoader />;
  }

  const product = productQuery.data?.products.find(
    p => p.id === parseInt(productId!)
  );
  const price = product?.prices.find(p => p.id === parseInt(priceId!));

  // make sure product and price exists in backend
  if (!product || !price || productQuery.status === 'error') {
    return <Navigate to="/pricing" replace />;
  }


  return (
    <CheckoutLayout>
      <Fragment>
        <div className="mood-glass-panel p-28 mb-20">
          <h1 className="text-4xl font-bold">
            <Trans message="Commande" />
          </h1>
        </div>

        <div className="mood-glass-panel p-24 mb-20">
          {stripe?.enable ? (
            <Fragment>
              <StripeElementsForm
                productId={productId}
                submitLabel={<Trans message="Souscrire" />}
                type="subscription"
                returnUrl={`${base_url}/checkout/${productId}/${priceId}/stripe/done`}
              />
              {(ebilling?.enable || paypal?.enable) && <Separator />}
            </Fragment>
          ) : null}

          {ebilling?.enable && (
            <Fragment>
              <EbillingButton
                productId={productId!}
                priceId={priceId!}
                className="w-full mood-cta-button"
              />
              {paypal?.enable && <Separator />}
            </Fragment>
          )}

          {paypal?.enable && <div ref={paypalElementRef} />}
        </div>

        <div className="mood-glass-notification p-20 text-xs">
          <Trans message="Vous serez facturé jusqu'à ce que vous annuliez votre abonnement. Les frais précédents ne seront pas remboursés lors de l'annulation sauf si la loi l'exige. Vos données de paiement sont chiffrées et sécurisées. En vous abonnant, vous acceptez nos conditions d'utilisation et notre politique de confidentialité." />
        </div>
      </Fragment>
      <div className="mood-glass-panel p-28">
        <CheckoutProductSummary />
      </div>
    </CheckoutLayout>
  );
}

function Separator() {
  return (
    <div className="relative text-center my-20 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-1 before:w-full before:bg-divider">
      <span className="bg relative z-10 px-10 text-sm text-muted">
        <Trans message="ou" />
      </span>
    </div>
  );
}
