import {CheckIcon} from '@common/icons/material/Check';
import {Trans} from '@common/i18n/trans';
import {Link, Navigate} from 'react-router-dom';
import {Button} from '@common/ui/buttons/button';
import {useBackstageRequest} from '@app/web-player/backstage/requests/use-backstage-request';
import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {BackstageLayout} from '@app/web-player/backstage/backstage-layout';

export function BackstageRequestSubmittedPage() {
  const {data, isLoading} = useBackstageRequest();

  if (isLoading) {
    return <FullPageLoader className="my-40" />;
  }

  if (!data?.request) {
    return <Navigate to="/" />;
  }

  return (
    <BackstageLayout>
      <div className="max-w-[590px] my-40 mx-auto">
        <div>
          <div className="text-center">
            <CheckIcon size="text-6xl" />
          </div>

          <h1 className="text-5xl font-medium mt-24 mb-48 text-center">
            <Trans message="We've got your request" />
          </h1>

          <ul className="mb-60 list-disc list-inside px-20">
            <li className="pb-10">
              <Trans message="Our support team will review it and send you an email within 3 days!" />
            </li>
            <li className="pb-10">
              <Trans message="Don't submit another request until you hear from us." />
            </li>
            <li>
              <Trans
                message=" If this artist profile is already claimed, ask an admin on your team
            to invite you."
              />
            </li>
          </ul>

          <div className="text-center">
            <Button
              variant="raised"
              color="primary"
              elementType={Link}
              to="/"
              className="min-w-140"
              radius="rounded-full"
            >
              <Trans message="Got It" />
            </Button>
          </div>
        </div>
      </div>
    </BackstageLayout>
  );
}
