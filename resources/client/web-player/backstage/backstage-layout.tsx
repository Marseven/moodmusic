import {Navbar} from '@common/ui/navigation/navbar/navbar';
import {Footer} from '@common/ui/footer/footer';
import lightBgImage from './images/light-bg.svg';
import darkBgImage from './images/dark-bg.svg';
import {useIsDarkMode} from '@common/ui/themes/use-is-dark-mode';
import {ComponentPropsWithoutRef, ReactNode} from 'react';

interface Props extends ComponentPropsWithoutRef<'div'> {
  children: ReactNode;
}
export function BackstageLayout({children, ...domProps}: Props) {
  const isDarkMode = useIsDarkMode();
  return (
    <div className="h-full flex flex-col" {...domProps}>
      <Navbar className="flex-shrink-0" color="bg" darkModeColor="bg" />
      <div
        className="overflow-y-auto flex-auto bg-cover relative"
        style={{
          backgroundImage: `url(${isDarkMode ? darkBgImage : lightBgImage})`,
        }}
      >
        <div className="container mx-auto p-14 md:p-24 min-h-full flex flex-col">
          <div className="flex-auto">{children}</div>
          <Footer />
        </div>
      </div>
    </div>
  );
}
