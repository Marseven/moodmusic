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
  CreateRadioStationPayload,
  useCreateRadioStation,
} from './requests/use-create-radio-station';
import {
  UpdateRadioStationPayload,
  useUpdateRadioStation,
} from './requests/use-update-radio-station';

export interface RadioStation {
  id: number;
  name: string;
  image?: string;
  stream_url: string;
  frequency?: string;
  description?: string;
  genre?: string;
  is_active: boolean;
  sort_order: number;
  listeners_count: number;
  created_at?: string;
  updated_at?: string;
}

interface Props {
  station?: RadioStation;
}

export function CrupdateRadioStationDialog({station}: Props) {
  const {close, formId} = useDialogContext();
  const isUpdate = !!station;

  const form = useForm<UpdateRadioStationPayload>({
    defaultValues: {
      id: station?.id,
      name: station?.name || '',
      stream_url: station?.stream_url || '',
      image: station?.image || '',
      frequency: station?.frequency || '',
      description: station?.description || '',
      genre: station?.genre || '',
      is_active: station?.is_active ?? true,
      sort_order: station?.sort_order || 0,
    },
  });

  const createStation = useCreateRadioStation(form as any);
  const updateStation = useUpdateRadioStation(form);

  const onSubmit = (values: UpdateRadioStationPayload) => {
    if (isUpdate) {
      updateStation.mutate(values, {onSuccess: () => close()});
    } else {
      createStation.mutate(values as CreateRadioStationPayload, {
        onSuccess: () => close(),
      });
    }
  };

  const isLoading = createStation.isLoading || updateStation.isLoading;

  return (
    <Dialog size="lg">
      <DialogHeader>
        {isUpdate ? (
          <Trans
            message="Modifier la station \u00ab :name \u00bb"
            values={{name: station.name}}
          />
        ) : (
          <Trans message="Nouvelle station radio" />
        )}
      </DialogHeader>
      <DialogBody>
        <Form id={formId} form={form} onSubmit={onSubmit}>
          <FormTextField
            name="name"
            label={<Trans message="Nom" />}
            className="mb-24"
            required
            autoFocus
          />
          <FormTextField
            name="stream_url"
            label={<Trans message="URL du flux audio" />}
            description={
              <Trans message="URL du flux de streaming (ex: https://stream.example.com/live.mp3)" />
            }
            className="mb-24"
            required
          />
          <FormTextField
            name="image"
            label={<Trans message="URL de l'image" />}
            className="mb-24"
          />
          <div className="mb-24 flex gap-24">
            <FormTextField
              name="frequency"
              label={<Trans message="Fr\u00e9quence" />}
              description={<Trans message="Ex: 98.5 FM" />}
              className="flex-1"
            />
            <FormTextField
              name="genre"
              label={<Trans message="Genre" />}
              className="flex-1"
            />
          </div>
          <FormTextField
            name="description"
            label={<Trans message="Description" />}
            inputElementType="textarea"
            rows={3}
            className="mb-24"
          />
          <div className="mb-24 flex gap-24">
            <FormTextField
              name="sort_order"
              label={<Trans message="Ordre d'affichage" />}
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
            <Trans message="Cr\u00e9er" />
          )}
        </Button>
      </DialogFooter>
    </Dialog>
  );
}
