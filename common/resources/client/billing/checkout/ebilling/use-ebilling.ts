import {useCallback} from 'react';
import {useNavigate} from 'react-router-dom';
import {useProducts} from '../../pricing-table/use-products';
import {useSettings} from '../../../core/settings/use-settings';
import {useAuth} from '@common/auth/use-auth';

interface EbillingOrderResponse {
  checkout_url: string;
  bill_id: string;
  error?: string;
}

export function useEbilling() {
  const {base_url} = useSettings();
  const {user} = useAuth();
  const navigate = useNavigate();
  const {data: products} = useProducts();

  const initiatePayment = useCallback(
    async (productId: string, priceId: string) => {
      if (!user) {
        console.error('Vous devez être connecté pour payer.');
        navigate('/login');
        return;
      }

      const product = products?.products.find(p => p.id === parseInt(productId));
      const price = product?.prices.find(p => p.id === parseInt(priceId));

      if (!product || !price) {
        console.error('Produit ou tarif introuvable.');
        return;
      }

      try {
        const response = await fetch(`${base_url}/api/v1/billing/ebilling/create-order`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            product_id: product.id,
            price_id: price.id,
            user_id: user.id,
          }),
        });

        if (!response.ok) {
          const errorText = await response.text();
          throw new Error(`Erreur API ${response.status}: ${errorText}`);
        }

        const data = await response.json() as EbillingOrderResponse;

        if (data.error) {
          throw new Error(data.error);
        }

        if (data.checkout_url) {
          window.location.href = data.checkout_url;
        }
      } catch (error) {
        console.error('Erreur Ebilling :', error);
        alert(
          error instanceof Error
            ? error.message
            : 'Une erreur est survenue pendant le paiement Ebilling.'
        );
      }
    },
    [base_url, products, user, navigate],
  );

  return {initiatePayment};
}