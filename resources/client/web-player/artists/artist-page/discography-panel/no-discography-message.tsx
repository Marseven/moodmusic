import {IllustratedMessage} from '@common/ui/images/illustrated-message';
import {Disc3} from 'lucide-react';
import {Trans} from '@common/i18n/trans';

export function NoDiscographyMessage() {
  return (
    <IllustratedMessage
      className="my-80"
      imageHeight="h-auto"
      image={<Disc3 size={64} className="text-muted" />}
      title={<Trans message="We do not have discography for this artist yet" />}
    />
  );
}
