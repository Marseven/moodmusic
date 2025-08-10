import {Button} from '../../../ui/buttons/button';
import {Trans} from '../../../i18n/trans';
import {useEbilling} from './use-ebilling';
import {useState} from 'react';

interface EbillingButtonProps {
  productId: string;
  priceId: string;
  className?: string;
  size?: 'sm' | 'md' | 'lg';
  variant?: 'flat' | 'raised' | 'outline';
  color?: 'primary' | 'danger' | 'paper' | 'chip' | 'white';
  disabled?: boolean;
}

export function EbillingButton({
  productId,
  priceId,
  className = 'w-full',
  size = 'lg',
  variant = 'flat',
  color = 'primary',
  disabled = false,
}: EbillingButtonProps) {
  const {initiatePayment} = useEbilling();
  const [isProcessing, setIsProcessing] = useState(false);

  const handlePayment = async () => {
    setIsProcessing(true);
    try {
      await initiatePayment(productId, priceId);
    } catch (error: any) {
      console.error('Ebilling payment initiation failed:', error);
      // Could show a toast notification or error message here
      // For now, just log the error
    } finally {
      setIsProcessing(false);
    }
  };

  return (
    <Button
      variant={variant}
      color={color}
      size={size}
      className={className}
      onClick={handlePayment}
      disabled={disabled || isProcessing}
    >
      {isProcessing ? (
        <Trans message="Processing..." />
      ) : (
        <Trans message="Pay with Ebilling" />
      )}
    </Button>
  );
}