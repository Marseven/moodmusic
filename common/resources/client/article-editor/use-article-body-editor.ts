import {useEditor} from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import {Underline} from '@tiptap/extension-underline';
import {Link as LinkExtension} from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import {useEffect} from 'react';
import {Superscript} from '@tiptap/extension-superscript';
import {Subscript} from '@tiptap/extension-subscript';
import {Color} from '@tiptap/extension-color';
import {TextStyle} from '@tiptap/extension-text-style';
import {TextAlign} from '@tiptap/extension-text-align';
import {CodeBlockLowlight} from '@tiptap/extension-code-block-lowlight';
import {BackgroundColor} from '@common/text-editor/extensions/background-color';
import {Indent} from '@common/text-editor/extensions/indent';
import {Embed} from '@common/text-editor/extensions/embed';
import {lowlight} from '../text-editor/lowlight';
import {InfoBlock} from '@common/text-editor/extensions/info-block';

export function useArticleBodyEditor(initialContent?: string) {
  const editor = useEditor({
    extensions: [
      StarterKit.configure({
        codeBlock: false,
      }),
      Underline,
      LinkExtension,
      Image,
      Superscript,
      Subscript,
      TextStyle,
      Color,
      BackgroundColor,
      Indent,
      CodeBlockLowlight.configure({
        lowlight,
      }),
      TextAlign.configure({
        types: ['heading', 'paragraph'],
      }),
      InfoBlock,
      Embed,
    ],
    onFocus: () => {},
    content: initialContent,
  });

  // destroy editor
  useEffect(() => {
    if (editor) {
      return () => editor.destroy();
    }
  }, [editor]);

  return editor;
}
