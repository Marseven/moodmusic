import {UseFormReturn} from 'react-hook-form';
import {Form} from '@common/ui/forms/form';
import {FormNormalizedModelField} from '@common/ui/forms/normalized-model-field';
import {TRACK_MODEL} from '@app/web-player/tracks/track';
import {Editor, EditorContent, useEditor} from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import {Underline} from '@tiptap/extension-underline';
import {TextStyle} from '@tiptap/extension-text-style';
import {Color} from '@tiptap/extension-color';
import {BackgroundColor} from '@common/text-editor/extensions/background-color';
import {Indent} from '@common/text-editor/extensions/indent';
import {Trans} from '@common/i18n/trans';
import React from 'react';
import {FileUploadProvider} from '@common/uploads/uploader/file-upload-provider';
import {FontStyleButtons} from '@common/text-editor/menubar/font-style-buttons';
import {Divider} from '@common/text-editor/menubar/divider';
import {AlignButtons} from '@common/text-editor/menubar/align-buttons';
import {IndentButtons} from '@common/text-editor/menubar/indent-buttons';
import {ListButtons} from '@common/text-editor/menubar/list-buttons';
import {ColorButtons} from '@common/text-editor/menubar/color-buttons';
import {ClearFormatButton} from '@common/text-editor/menubar/clear-format-button';
import {UpdateLyricPayload} from '@app/admin/lyrics-datatable-page/requests/use-update-lyric';

interface Props {
  onSubmit: (values: UpdateLyricPayload) => void;
  formId: string;
  form: UseFormReturn<UpdateLyricPayload>;
}
export function CrupdateLyricForm({form, onSubmit, formId}: Props) {
  const editor = useEditor({
    extensions: [
      StarterKit.configure({
        codeBlock: false,
      }),
      Underline,
      TextStyle,
      Color,
      BackgroundColor,
      Indent,
    ],
    onFocus: () => {},
    content: form.getValues('text'),
  });

  return (
    <Form
      id={formId}
      form={form}
      onSubmit={values => {
        onSubmit({
          ...values,
          text: editor?.getHTML() || '',
        });
      }}
    >
      <FormNormalizedModelField
        className="mb-24"
        label={<Trans message="Track" />}
        name="track_id"
        modelType={TRACK_MODEL}
        queryParams={{
          with: 'artists,album',
        }}
      />
      {editor && (
        <div className="border rounded">
          <FileUploadProvider>
            <EditorMenu editor={editor} />
            <div className="p-14">
              <EditorContent editor={editor} />
            </div>
          </FileUploadProvider>
        </div>
      )}
    </Form>
  );
}

interface EditorMenuProps {
  editor: Editor;
}
function EditorMenu({editor}: EditorMenuProps) {
  return (
    <div className="flex items-center px-4 h-42 text-muted border-b overflow-hidden">
      <FontStyleButtons editor={editor} size="sm" />
      <Divider />
      <AlignButtons editor={editor} size="sm" />
      <IndentButtons editor={editor} size="sm" />
      <Divider />
      <ListButtons editor={editor} size="sm" />
      <Divider />
      <ColorButtons editor={editor} size="sm" />
      <Divider />
      <ClearFormatButton editor={editor} size="sm" />
    </div>
  );
}
