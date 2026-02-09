import {useProducts} from './use-products';
import {Button} from '../../ui/buttons/button';
import {Trans} from '../../i18n/trans';
import {ForumIcon} from '../../icons/material/Forum';
import {Navbar} from '../../ui/navigation/navbar/navbar';
import {Link} from 'react-router-dom';
import {Footer} from '../../ui/footer/footer';
import {useState} from 'react';
import {UpsellBillingCycle} from './find-best-price';
import {BillingCycleRadio} from './billing-cycle-radio';
import {StaticPageTitle} from '../../seo/static-page-title';
import {PricingTable} from '@common/billing/pricing-table/pricing-table';

export function PricingPage() {
  const query = useProducts();
  const [selectedCycle, setSelectedCycle] =
    useState<UpsellBillingCycle>('yearly');

  return (
    <div className="flex flex-col h-full overflow-auto">
      <StaticPageTitle>
        <Trans message="Tarifs" />
      </StaticPageTitle>
      <Navbar
        color="bg"
        darkModeColor="transparent"
        border="border-b"
        className="flex-shrink-0"
        menuPosition="pricing-table-page"
      />
      <div className="container mx-auto px-24 flex-auto">
        <h1 className="text-3xl md:text-4xl text-center mt-30 md:mt-60 mb-30 font-normal md:font-medium">
          <Trans message="Choisissez le plan qui vous convient" />
        </h1>

        <BillingCycleRadio
          products={query.data?.products}
          selectedCycle={selectedCycle}
          onChange={setSelectedCycle}
          className="mb-40 md:mb-70 flex justify-center"
          size="lg"
        />

        <PricingTable selectedCycle={selectedCycle} />
        <ContactSection />
      </div>
      <Footer className="container mx-auto px-24 flex-shrink-0" />
    </div>
  );
}

function ContactSection() {
  return (
    <div className="p-24 text-center my-20 md:my-80">
      <ForumIcon size="xl" className="text-muted" />
      <div className="md:text-lg my-8">
        <Trans message="Avez-vous des questions sur les comptes PRO ?" />
      </div>
      <div className="mb-24 text-sm md:text-base mt-20 md:mt-0">
        <Trans message="Notre équipe de support sera ravie de vous aider." />
      </div>
      <Button variant="flat" color="primary" elementType={Link} to="/contact">
        <Trans message="Nous contacter" />
      </Button>
    </div>
  );
}
