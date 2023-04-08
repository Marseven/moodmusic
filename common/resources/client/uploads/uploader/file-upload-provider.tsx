import createContext from 'zustand/context';
import {StoreApi} from 'zustand';
import {ReactNode} from 'react';
import {createFileUploadStore, FileUploadState} from './file-upload-store';
import {useSettings} from '../../core/settings/use-settings';

const {Provider, useStore} = createContext<StoreApi<FileUploadState>>();
export const useFileUploadStore = useStore;

interface FileUploadProviderProps {
  children: ReactNode;
}
export function FileUploadProvider({children}: FileUploadProviderProps) {
  const settings = useSettings();
  return (
    <Provider createStore={() => createFileUploadStore({settings})}>
      {children}
    </Provider>
  );
}
