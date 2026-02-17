import React from 'react';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {DialogBody} from '@common/ui/overlays/dialog/dialog-body';
import {DialogFooter} from '@common/ui/overlays/dialog/dialog-footer';
import {Dialog} from '@common/ui/overlays/dialog/dialog';
import {DialogHeader} from '@common/ui/overlays/dialog/dialog-header';
import {useForm} from 'react-hook-form';
import {Trans} from '@common/i18n/trans';
import {Button} from '@common/ui/buttons/button';
import {Form} from '@common/ui/forms/form';
import {FormTextField} from '@common/ui/forms/input-field/text-field/text-field';
import {FormSwitch} from '@common/ui/forms/toggle/switch';
import {
  CreateOriginalContentCategoryPayload,
  useCreateOriginalContentCategory,
} from './requests/use-create-original-content-category';
import {
  UpdateOriginalContentCategoryPayload,
  useUpdateOriginalContentCategory,
} from './requests/use-update-original-content-category';

export interface OriginalContentCategory {
  id: number;
  name: string;
  display_name: string;
  description?: string;
  icon?: string;
  position: number;
  is_active: boolean;
  created_at?: string;
  updated_at?: string;
}

interface Props {
  category?: OriginalContentCategory;
}

export function CrupdateOriginalContentCategoryDialog({category}: Props) {
  const {close, formId} = useDialogContext();
  const isUpdate = !!category;

  const form = useForm<UpdateOriginalContentCategoryPayload>({
    defaultValues: {
      id: category?.id,
      name: category?.name || '',
      display_name: category?.display_name || '',
      description: category?.description || '',
      icon: category?.icon || '',
      position: category?.position || 0,
      is_active: category?.is_active ?? true,
    },
  });

  const createCategory = useCreateOriginalContentCategory(form as any);
  const updateCategory = useUpdateOriginalContentCategory(form);

  const onSubmit = (values: UpdateOriginalContentCategoryPayload) => {
    if (isUpdate) {
      updateCategory.mutate(values, {onSuccess: () => close()});
    } else {
      createCategory.mutate(
        values as CreateOriginalContentCategoryPayload,
        {onSuccess: () => close()},
      );
    }
  };

  const isLoading = createCategory.isLoading || updateCategory.isLoading;

  return (
    <Dialog size="lg">
      <DialogHeader>
        {isUpdate ? (
          <Trans
            message="Modifier « :name »"
            values={{name: category.display_name}}
          />
        ) : (
          <Trans message="Nouvelle catégorie" />
        )}
      </DialogHeader>
      <DialogBody>
        <Form id={formId} form={form} onSubmit={onSubmit}>
          <FormTextField
            name="name"
            label={<Trans message="Identifiant (slug)" />}
            description={
              <Trans message="Identifiant technique unique, ex: mix, beat" />
            }
            className="mb-24"
            required
            autoFocus
          />
          <FormTextField
            name="display_name"
            label={<Trans message="Nom affiché" />}
            className="mb-24"
            required
          />
          <FormTextField
            name="description"
            label={<Trans message="Description" />}
            inputElementType="textarea"
            rows={3}
            className="mb-24"
          />
          <div className="mb-24 flex gap-24">
            <FormTextField
              name="icon"
              label={<Trans message="Icône" />}
              description={
                <Trans message="Nom Lucide, ex: disc-3, audio-lines" />
              }
              className="flex-1"
            />
            <FormTextField
              name="position"
              label={<Trans message="Position" />}
              type="number"
              min={0}
              className="flex-1"
            />
          </div>
          <FormSwitch name="is_active">
            <Trans message="Active" />
          </FormSwitch>
        </Form>
      </DialogBody>
      <DialogFooter>
        <Button onClick={() => close()}>
          <Trans message="Annuler" />
        </Button>
        <Button
          form={formId}
          disabled={isLoading}
          variant="flat"
          color="primary"
          type="submit"
        >
          {isUpdate ? (
            <Trans message="Enregistrer" />
          ) : (
            <Trans message="Créer" />
          )}
        </Button>
      </DialogFooter>
    </Dialog>
  );
}
