import {SlugEditor, SlugEditorProps} from '@common/ui/slug-editor';
import {useController, useFormContext} from 'react-hook-form';
import React, {Fragment, useEffect, useRef} from 'react';
import clsx from 'clsx';
import {useStickySentinel} from '@common/utils/hooks/sticky-sentinel';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {Button} from '@common/ui/buttons/button';
import {Link} from 'react-router-dom';
import {ArrowBackIcon} from '@common/icons/material/ArrowBack';
import {Trans} from '@common/i18n/trans';
import {HistoryButtons} from '@common/text-editor/menubar/history-buttons';
import {ModeButton} from '@common/text-editor/menubar/mode-button';
import {ArticleBodyEditorMenubar} from './article-body-editor-menubar';
import {Editor} from '@tiptap/react';
import {CreateCustomPagePayload} from '@common/admin/custom-pages/requests/use-create-custom-page';

interface StickyHeaderProps {
  editor: Editor;
  allowSlugEditing?: boolean;
  onSave: (editorContent: string) => void;
  backLink: string;
  isLoading: boolean;
}
export function ArticleEditorStickyHeader({
  editor,
  allowSlugEditing = true,
  onSave,
  isLoading,
  backLink,
}: StickyHeaderProps) {
  const {isSticky, sentinelRef} = useStickySentinel();
  const isMobile = useIsMobileMediaQuery();

  return (
    <Fragment>
      <div ref={sentinelRef} />
      <div
        className={clsx(
          'sticky top-0 z-10 mb-20 bg-paper',
          isSticky && 'shadow'
        )}
      >
        <div className="px-20 py-10 flex items-center justify-between sm:justify-start gap-20 border-b text-muted">
          {!isMobile && (
            <Fragment>
              <Button
                variant="text"
                size="sm"
                elementType={Link}
                to={backLink}
                relative="path"
                startIcon={<ArrowBackIcon />}
              >
                <Trans message="Back" />
              </Button>
              <div className="mr-auto">
                {allowSlugEditing && (
                  <FormSlugEditor
                    name="slug"
                    showLinkIcon={false}
                    prefix="pages"
                  />
                )}
              </div>
            </Fragment>
          )}
          {editor && <HistoryButtons editor={editor} />}
          {!isMobile && <ModeButton editor={editor} />}
          <SaveButton
            onSave={() => {
              onSave(editor.getHTML());
            }}
            isLoading={isLoading}
          />
        </div>
        <ArticleBodyEditorMenubar editor={editor} size="sm" />
      </div>
    </Fragment>
  );
}

interface SaveButtonProps {
  onSave: () => void;
  isLoading: boolean;
}
function SaveButton({onSave, isLoading}: SaveButtonProps) {
  const form = useFormContext();
  const title = form.watch('title');

  return (
    <Button
      variant="flat"
      size="sm"
      color="primary"
      className="min-w-90"
      disabled={isLoading || !title}
      onClick={() => onSave()}
    >
      <Trans message="Save" />
    </Button>
  );
}

interface FormSlugEditorProps extends SlugEditorProps {
  name: string;
}
function FormSlugEditor({name, ...other}: FormSlugEditorProps) {
  const {
    field: {onChange, onBlur, value = '', ref},
  } = useController({
    name,
  });
  const manuallyChanged = useRef(false);

  const {watch, setValue} = useFormContext<CreateCustomPagePayload>();

  useEffect(() => {
    const subscription = watch((formVal, {name: fieldName}) => {
      // if user has not changed slug manually, set it based on page title field changes
      if (fieldName === 'title' && !manuallyChanged.current) {
        setValue('slug', formVal.title);
      }
    });
    return () => subscription.unsubscribe();
  }, [watch, setValue]);

  return (
    <SlugEditor
      className={clsx(!value && 'invisible')}
      onChange={e => {
        manuallyChanged.current = true;
        onChange(e);
      }}
      onInputBlur={onBlur}
      value={value}
      inputRef={ref}
      {...other}
    />
  );
}
