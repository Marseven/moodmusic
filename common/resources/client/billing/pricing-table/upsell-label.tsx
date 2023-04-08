// find the highest percentage decrease between monthly and yearly prices of specified products
import {Product} from '../product';
import {findBestPrice} from './find-best-price';
import {Fragment, memo} from 'react';
import {Trans} from '../../i18n/trans';

interface UpsellLabelProps {
  products?: Product[];
}
export const UpsellLabel = memo(({products}: UpsellLabelProps) => {
  const upsellPercentage = calcHighestUpsellPercentage(products);

  if (upsellPercentage <= 0) {
    return null;
  }

  return (
    <Fragment>
      <span className="text-positive-darker font-medium">
        {' '}
        (
        <Trans
          message="Save up to :percentage%"
          values={{percentage: upsellPercentage}}
        />
        )
      </span>
    </Fragment>
  );
});

function calcHighestUpsellPercentage(products?: Product[]) {
  if (!products?.length) return 0;

  const decreases = products.map(product => {
    const monthly = findBestPrice('monthly', product.prices);
    const yearly = findBestPrice('yearly', product.prices);

    if (!monthly || !yearly) return 0;

    // monthly plan per year amount
    const monthlyAmount = monthly.amount * 12;
    const yearlyAmount = yearly.amount;

    const amountDecrease = Math.round(
      ((monthlyAmount - yearlyAmount) / yearlyAmount) * 100
    );

    if (amountDecrease > 0 && amountDecrease <= 200) {
      return amountDecrease;
    }

    return 0;
  });

  return Math.max(Math.max(...decreases), 0);
}
