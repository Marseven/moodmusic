import {useForm} from 'react-hook-form';
import {useState} from 'react';
import useClipboard from 'react-use-clipboard';
import {Dialog} from '../../../../ui/overlays/dialog/dialog';
import {DialogHeader} from '../../../../ui/overlays/dialog/dialog-header';
import {DialogBody} from '../../../../ui/overlays/dialog/dialog-body';
import {Form} from '../../../../ui/forms/form';
import {
  FormTextField,
  TextField,
} from '../../../../ui/forms/input-field/text-field/text-field';
import {useDialogContext} from '../../../../ui/overlays/dialog/dialog-context';
import {DialogFooter} from '../../../../ui/overlays/dialog/dialog-footer';
import {Button} from '../../../../ui/buttons/button';
import {
  CreateAccessTokenPayload,
  useCreateAccessToken,
} from './create-new-token';
import {ErrorIcon} from '../../../../icons/material/Error';
import {Trans} from '../../../../i18n/trans';
import {queryClient} from '@common/http/query-client';

export function CreateNewTokenDialog() {
  const form = useForm<CreateAccessTokenPayload>();
  const {formId, close} = useDialogContext();
  const createToken = useCreateAccessToken(form);

  const [plainTextToken, setPlainTextToken] = useState<string>();

  const formNode = (
    <Form
      form={form}
      id={formId}
      onSubmit={values => {
        createToken.mutate(values, {
          onSuccess: response => {
            setPlainTextToken(response.plainTextToken);
            queryClient.invalidateQueries(['users']);
          },
        });
      }}
    >
      <FormTextField
        name="tokenName"
        label={<Trans message="Nom du jeton" />}
        required
        autoFocus
      />
    </Form>
  );

  return (
    <Dialog>
      <DialogHeader>
        <Trans message="Créer un nouveau jeton" />
      </DialogHeader>
      <DialogBody>
        {plainTextToken ? (
          <PlainTextPreview plainTextToken={plainTextToken} />
        ) : (
          formNode
        )}
      </DialogBody>
      <DialogFooter>
        <Button variant="text" onClick={close}>
          <Trans message="Terminé" />
        </Button>
        {!plainTextToken && (
          <Button
            variant="flat"
            color="primary"
            type="submit"
            form={formId}
            disabled={createToken.isLoading}
          >
            <Trans message="Créer" />
          </Button>
        )}
      </DialogFooter>
    </Dialog>
  );
}

interface PlainTextPreviewProps {
  plainTextToken: string;
}
function PlainTextPreview({plainTextToken}: PlainTextPreviewProps) {
  const [isCopied, copyToClipboard] = useClipboard(plainTextToken || '', {
    successDuration: 1000,
  });

  return (
    <>
      <TextField
        readOnly
        value={plainTextToken}
        autoFocus
        onClick={e => {
          e.currentTarget.focus();
          e.currentTarget.select();
        }}
        endAppend={
          <Button variant="flat" color="primary" onClick={copyToClipboard}>
            {isCopied ? <Trans message="Copié!" /> : <Trans message="Copier" />}
          </Button>
        }
      />
      <div className="flex items-center gap-10 mt-14 text-sm">
        <ErrorIcon size="sm" className="text-danger" />
        <Trans message="Assurez-vous de stocker le jeton dans un endroit sûr. Après la fermeture de cette boîte de dialogue, le jeton ne sera plus visible." />
      </div>
    </>
  );
}
