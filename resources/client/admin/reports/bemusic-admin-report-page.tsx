import {Trans} from '@common/i18n/trans';
import {Link, Outlet, useParams} from 'react-router-dom';
import React, {useState} from 'react';
import {DateRangeValue} from '@common/ui/forms/input-field/date/date-range-picker/date-range-value';
import {DateRangePresets} from '@common/ui/forms/input-field/date/date-range-picker/dialog/date-range-presets';
import {ReportDateSelector} from '@common/admin/analytics/report-date-selector';
import {Button} from '@common/ui/buttons/button';
import {ButtonGroup} from '@common/ui/buttons/button-group';
import {AdminHeaderReport} from '@common/admin/analytics/admin-header-report';
import {useAdminReport} from '@common/admin/analytics/use-admin-report';
import {StaticPageTitle} from '@common/seo/static-page-title';

export interface AdminReportOutletContext {
  dateRange: DateRangeValue;
  setDateRange: (dateRange: DateRangeValue) => void;
}

export function BemusicAdminReportPage() {
  const [dateRange, setDateRange] = useState<DateRangeValue>(() => {
    // This week
    return DateRangePresets[2].getRangeValue();
  });
  const params = useParams();
  const channel = params['*'] || 'plays';

  const title =
    channel === 'visitors' ? (
      <Trans message="Visitors report" />
    ) : (
      <Trans message="Plays report" />
    );

  return (
    <div className="min-h-full p-12 md:p-24 overflow-x-hidden">
      <div className="md:flex items-center justify-between gap-24 mb-24">
        <StaticPageTitle>{title}</StaticPageTitle>
        <h1 className="mb-24 md:mb-0 text-3xl font-light">{title}</h1>
        <div className="flex-shrink-0 flex items-center justify-between gap-10 md:gap-24">
          <ButtonGroup variant="outline" radius="rounded" value={channel}>
            <Button value="plays" elementType={Link} to="plays">
              <Trans message="Plays" />
            </Button>
            <Button value="visitors" elementType={Link} to="visitors">
              <Trans message="Visitors" />
            </Button>
          </ButtonGroup>
          <ReportDateSelector value={dateRange} onChange={setDateRange} />
        </div>
      </div>
      <Header dateRange={dateRange} />
      <Outlet context={{dateRange, setDateRange}} />
    </div>
  );
}

interface HeaderProps {
  dateRange: DateRangeValue;
}
function Header({dateRange}: HeaderProps) {
  const {data} = useAdminReport({types: ['header'], dateRange});
  return (
    <AdminHeaderReport report={data?.headerReport} dateRange={dateRange} />
  );
}
