import {FieldValues, SubmitHandler, UseFormReturn} from 'react-hook-form';
import clsx from 'clsx';
import React, {ReactNode} from 'react';
import {useStickySentinel} from '../utils/hooks/sticky-sentinel';
import {Form} from '../ui/forms/form';
import {Button} from '../ui/buttons/button';
import {Trans} from '../i18n/trans';

interface Props<T extends FieldValues> {
  onSubmit: SubmitHandler<T>;
  form: UseFormReturn<T>;
  title: ReactNode;
  isLoading: boolean;
  children: ReactNode;
  actions?: ReactNode;
  backButton?: ReactNode;
  disableSaveWhenNotDirty?: boolean;
  wrapInContainer?: boolean;
}
export function CrupdateResourceLayout<T extends FieldValues>({
  onSubmit,
  form,
  title,
  children,
  actions,
  backButton,
  isLoading = false,
  disableSaveWhenNotDirty = false,
  wrapInContainer = true,
}: Props<T>) {
  const {isSticky, sentinelRef} = useStickySentinel();
  const isDirty = !disableSaveWhenNotDirty
    ? true
    : Object.keys(form.formState.dirtyFields).length;

  return (
    <Form
      onSubmit={onSubmit}
      onBeforeSubmit={() => form.clearErrors()}
      form={form}
    >
      <div ref={sentinelRef} />
      <div
        className={clsx(
          'sticky top-0 my-12 md:my-24 z-10 transition-shadow',
          isSticky && 'bg-paper shadow'
        )}
      >
        <div
          className={clsx(
            'flex items-center md:items-start gap-24 py-14',
            wrapInContainer && 'container mx-auto px-24'
          )}
        >
          {backButton}
          <h1 className="text-xl md:text-3xl whitespace-nowrap overflow-hidden overflow-ellipsis md:mr-64">
            {title}
          </h1>
          <div className="mr-auto"></div>
          {actions}
          <Button
            variant="flat"
            color="primary"
            type="submit"
            disabled={isLoading || !isDirty}
          >
            <Trans message="Save" />
          </Button>
        </div>
      </div>
      <div
        className={
          wrapInContainer ? 'container mx-auto px-24 pb-24' : undefined
        }
      >
        <div className="rounded">{children}</div>
      </div>
    </Form>
  );
}
