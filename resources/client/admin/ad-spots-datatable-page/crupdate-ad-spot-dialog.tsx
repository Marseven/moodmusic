import React, {useState} from 'react';
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
import {FormSelect, Option} from '@common/ui/forms/select/select';
import {FormFileEntryField} from '@common/ui/forms/input-field/file-entry-field';
import {FormImageSelector} from '@common/ui/images/image-selector';
import {FileUploadProvider} from '@common/uploads/uploader/file-upload-provider';
import {
  CreateAdSpotPayload,
  useCreateAdSpot,
} from './requests/use-create-ad-spot';
import {
  UpdateAdSpotPayload,
  useUpdateAdSpot,
} from './requests/use-update-ad-spot';

export interface AdSpot {
  id: number;
  name: string;
  type: 'audio' | 'banner';
  audio_url?: string;
  image_url?: string;
  click_url?: string;
  duration?: number;
  active: boolean;
  priority: number;
  impressions: number;
  clicks: number;
  start_date?: string;
  end_date?: string;
  created_at?: string;
  updated_at?: string;
}

type InputMode = 'upload' | 'url';

interface Props {
  adSpot?: AdSpot;
}

function isExternalUrl(value?: string): boolean {
  if (!value) return false;
  return value.startsWith('http://') || value.startsWith('https://');
}

export function CrupdateAdSpotDialog({adSpot}: Props) {
  const {close, formId} = useDialogContext();
  const isUpdate = !!adSpot;

  const [audioMode, setAudioMode] = useState<InputMode>(
    isUpdate && isExternalUrl(adSpot?.audio_url) ? 'url' : 'upload',
  );
  const [imageMode, setImageMode] = useState<InputMode>(
    isUpdate && isExternalUrl(adSpot?.image_url) ? 'url' : 'upload',
  );

  const form = useForm<UpdateAdSpotPayload>({
    defaultValues: {
      id: adSpot?.id,
      name: adSpot?.name || '',
      type: adSpot?.type || 'audio',
      audio_url: adSpot?.audio_url || '',
      image_url: adSpot?.image_url || '',
      click_url: adSpot?.click_url || '',
      duration: adSpot?.duration || 15,
      active: adSpot?.active ?? true,
      priority: adSpot?.priority || 0,
      start_date: adSpot?.start_date || '',
      end_date: adSpot?.end_date || '',
    },
  });

  const createAdSpot = useCreateAdSpot(form as any);
  const updateAdSpot = useUpdateAdSpot(form);

  const onSubmit = (values: UpdateAdSpotPayload) => {
    if (isUpdate) {
      updateAdSpot.mutate(values, {onSuccess: () => close()});
    } else {
      createAdSpot.mutate(values as CreateAdSpotPayload, {
        onSuccess: () => close(),
      });
    }
  };

  const isLoading = createAdSpot.isLoading || updateAdSpot.isLoading;
  const selectedType = form.watch('type');

  const handleAudioModeChange = (mode: InputMode) => {
    form.setValue('audio_url', '');
    setAudioMode(mode);
  };

  const handleImageModeChange = (mode: InputMode) => {
    form.setValue('image_url', '');
    setImageMode(mode);
  };

  return (
    <Dialog size="lg">
      <DialogHeader>
        {isUpdate ? (
          <Trans
            message="Update \u201c:name\u201d ad spot"
            values={{name: adSpot.name}}
          />
        ) : (
          <Trans message="Create new ad spot" />
        )}
      </DialogHeader>
      <DialogBody>
        <Form id={formId} form={form} onSubmit={onSubmit}>
          <FormTextField
            name="name"
            label={<Trans message="Name" />}
            className="mb-24"
            required
            autoFocus
          />

          <FormSelect
            name="type"
            label={<Trans message="Type" />}
            className="mb-24"
            selectionMode="single"
          >
            <Option value="audio">
              <Trans message="Audio" />
            </Option>
            <Option value="banner">
              <Trans message="Bannière" />
            </Option>
          </FormSelect>

          {/* Audio: Upload or URL (only for audio type) */}
          {selectedType === 'audio' && (
          <div className="mb-24">
            <div className="mb-8 flex items-center justify-between">
              <label className="block text-sm font-medium">
                <Trans message="Audio" />
              </label>
              <ModeToggle
                mode={audioMode}
                onChange={handleAudioModeChange}
              />
            </div>
            {audioMode === 'upload' ? (
              <FileUploadProvider>
                <FormFileEntryField
                  name="audio_url"
                  diskPrefix="ad_audio"
                  allowedFileTypes={['audio/mpeg', 'audio/mp3', 'audio/*']}
                  maxFileSize={10485760}
                  showRemoveButton
                />
              </FileUploadProvider>
            ) : (
              <FormTextField
                name="audio_url"
                placeholder="https://example.com/ad-audio.mp3"
                required
              />
            )}
          </div>
          )}

          {/* Image: Upload or URL */}
          <div className="mb-24">
            <div className="mb-8 flex items-center justify-between">
              <label className="block text-sm font-medium">
                <Trans message="Banner image" />
              </label>
              <ModeToggle
                mode={imageMode}
                onChange={handleImageModeChange}
              />
            </div>
            {imageMode === 'upload' ? (
              <FileUploadProvider>
                <FormImageSelector
                  name="image_url"
                  diskPrefix="ad_images"
                  variant="input"
                  showRemoveButton
                />
              </FileUploadProvider>
            ) : (
              <FormTextField
                name="image_url"
                placeholder="https://example.com/ad-banner.jpg"
              />
            )}
          </div>

            <FormTextField
              name="click_url"
              label={<Trans message="Click URL" />}
              description={
                <Trans message="URL to open when user clicks the ad banner." />
              }
              className="mb-24"
            />
            <div className="mb-24 flex gap-24">
              {selectedType === 'audio' && (
              <FormTextField
                name="duration"
                label={<Trans message="Duration (seconds)" />}
                type="number"
                min={1}
                className="flex-1"
                required
              />
              )}
              <FormTextField
                name="priority"
                label={<Trans message="Priority" />}
                description={<Trans message="Higher = more frequent" />}
                type="number"
                min={0}
                className="flex-1"
              />
            </div>
            <div className="mb-24 flex gap-24">
              <FormTextField
                name="start_date"
                label={<Trans message="Start date" />}
                type="date"
                className="flex-1"
              />
              <FormTextField
                name="end_date"
                label={<Trans message="End date" />}
                type="date"
                className="flex-1"
              />
            </div>
            <FormSwitch name="active">
              <Trans message="Active" />
            </FormSwitch>
          </Form>
      </DialogBody>
      <DialogFooter>
        <Button onClick={() => close()}>
          <Trans message="Cancel" />
        </Button>
        <Button
          form={formId}
          disabled={isLoading}
          variant="flat"
          color="primary"
          type="submit"
        >
          {isUpdate ? (
            <Trans message="Save" />
          ) : (
            <Trans message="Create" />
          )}
        </Button>
      </DialogFooter>
    </Dialog>
  );
}

interface ModeToggleProps {
  mode: InputMode;
  onChange: (mode: InputMode) => void;
}
function ModeToggle({mode, onChange}: ModeToggleProps) {
  return (
    <div className="flex overflow-hidden rounded border text-xs">
      <button
        type="button"
        className={`px-10 py-4 transition-colors ${
          mode === 'upload'
            ? 'bg-primary text-on-primary'
            : 'bg-paper text-muted hover:bg-hover'
        }`}
        onClick={() => onChange('upload')}
      >
        <Trans message="Upload" />
      </button>
      <button
        type="button"
        className={`px-10 py-4 transition-colors ${
          mode === 'url'
            ? 'bg-primary text-on-primary'
            : 'bg-paper text-muted hover:bg-hover'
        }`}
        onClick={() => onChange('url')}
      >
        <Trans message="URL" />
      </button>
    </div>
  );
}
