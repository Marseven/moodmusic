import {Trans} from '@common/i18n/trans';
import React, {cloneElement, Fragment, ReactElement, useState} from 'react';
import {DateRangeValue} from '@common/ui/forms/input-field/date/date-range-picker/date-range-value';
import {DateRangePresets} from '@common/ui/forms/input-field/date/date-range-picker/dialog/date-range-presets';
import {ReportDateSelector} from '@common/admin/analytics/report-date-selector';
import {StaticPageTitle} from '@common/seo/static-page-title';
import {InsightsReportChartsProps} from '@app/admin/reports/insights-report-charts';
import {Navbar} from '@common/ui/navigation/navbar/navbar';
import {Footer} from '@common/ui/footer/footer';
import {Skeleton} from '@common/ui/skeleton/skeleton';

interface Props {
  children: ReactElement<InsightsReportChartsProps>;
  reportModel: string;
  title?: ReactElement;
  isNested?: boolean;
}
export function BackstageInsightsLayout({
  children,
  reportModel,
  title,
  isNested,
}: Props) {
  const [dateRange, setDateRange] = useState<DateRangeValue>(() => {
    // This week
    return DateRangePresets[2].getRangeValue();
  });
  return (
    <Fragment>
      <StaticPageTitle>
        <Trans message="Insights" />
      </StaticPageTitle>
      <div className="h-full flex flex-col">
        {!isNested && (
          <Navbar className="flex-shrink-0" color="bg" darkModeColor="bg" />
        )}
        <div className="overflow-y-auto flex-auto bg-cover relative">
          <div className="min-h-full p-12 md:p-24 overflow-x-hidden max-w-[1600px] mx-auto flex flex-col">
            <div className="flex-auto">
              <div className="md:flex items-center justify-between gap-24 h-48 mt-14 mb-38">
                {title ? title : <Skeleton className="max-w-320" />}
                <div className="flex-shrink-0 flex items-center justify-between gap-10 md:gap-24">
                  <ReportDateSelector
                    value={dateRange}
                    onChange={setDateRange}
                  />
                </div>
              </div>
              {cloneElement(children, {dateRange, model: reportModel})}
            </div>
            {!isNested && <Footer />}
          </div>
        </div>
      </div>
    </Fragment>
  );
}
