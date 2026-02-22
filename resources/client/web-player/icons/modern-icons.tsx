/**
 * Modern outline-style icons using Lucide React
 * Matches the Flutter mobile app's Lucide icon set for visual consistency
 * Compatible with SvgIcon system (supports size prop, IconButton, etc.)
 */
import {createLucideIcon} from '@common/icons/create-lucide-icon';
import {createSvgIcon} from '@common/icons/create-svg-icon';
import {
  Music,
  Mic,
  Disc3,
  ListMusic,
  Clock,
  Plus,
  Search,
  ChevronRight,
  ChevronLeft,
  ChevronDown,
  Share2,
  Ellipsis,
  Download,
  Repeat,
  Heart,
  ChevronUp,
  EllipsisVertical,
  Tag,
  Sparkles,
  TrendingUp,
  User,
  Bookmark,
  Star,
  Image,
  Pencil,
  Check,
  AudioLines,
  Smartphone,
  Maximize,
  Pause,
  ArrowUpDown,
  ListPlus,
  List,
  LayoutGrid,
  X,
} from 'lucide-react';

// ── Music / Audio ──

export const ModernMusicNoteIcon = createLucideIcon(Music, 'ModernMusicNoteIcon');
export const ModernMicrophoneIcon = createLucideIcon(Mic, 'ModernMicrophoneIcon');
export const ModernDiscIcon = createLucideIcon(Disc3, 'ModernDiscIcon');

// ── Navigation / UI ──

export const ModernQueueListIcon = createLucideIcon(ListMusic, 'ModernQueueListIcon');
export const ModernClockIcon = createLucideIcon(Clock, 'ModernClockIcon');
export const ModernPlusIcon = createLucideIcon(Plus, 'ModernPlusIcon');
export const ModernSearchIcon = createLucideIcon(Search, 'ModernSearchIcon');
export const ModernChevronRightIcon = createLucideIcon(ChevronRight, 'ModernChevronRightIcon');
export const ModernChevronLeftIcon = createLucideIcon(ChevronLeft, 'ModernChevronLeftIcon');
export const ModernChevronDownIcon = createLucideIcon(ChevronDown, 'ModernChevronDownIcon');

// ── Actions ──

export const ModernShareIcon = createLucideIcon(Share2, 'ModernShareIcon');
export const ModernEllipsisIcon = createLucideIcon(Ellipsis, 'ModernEllipsisIcon');
export const ModernDownloadIcon = createLucideIcon(Download, 'ModernDownloadIcon');
export const ModernRepeatIcon = createLucideIcon(Repeat, 'ModernRepeatIcon');

// ── Hearts (Like) ──

export const ModernHeartOutlineIcon = createLucideIcon(Heart, 'ModernHeartOutlineIcon');

// Filled heart — kept as SVG since Lucide is stroke-only
export const ModernHeartFilledIcon = createSvgIcon(
  <path fill="currentColor" d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001z" />,
  'ModernHeartFilled', '0 0 24 24'
);

// ── Player ──

export const ModernExpandIcon = createLucideIcon(ChevronUp, 'ModernExpandIcon');
export const ModernCollapseIcon = createLucideIcon(ChevronDown, 'ModernCollapseIcon');
export const ModernEllipsisVerticalIcon = createLucideIcon(EllipsisVertical, 'ModernEllipsisVerticalIcon');

// ── Genre / Category ──

export const ModernTagIcon = createLucideIcon(Tag, 'ModernTagIcon');
export const ModernSparklesIcon = createLucideIcon(Sparkles, 'ModernSparklesIcon');
export const ModernTrendingUpIcon = createLucideIcon(TrendingUp, 'ModernTrendingUpIcon');

// ── User / Profile ──

export const ModernUserIcon = createLucideIcon(User, 'ModernUserIcon');
export const ModernBookmarkIcon = createLucideIcon(Bookmark, 'ModernBookmarkIcon');
export const ModernStarIcon = createLucideIcon(Star, 'ModernStarIcon');

// ── Image / Edit ──

export const ModernImageIcon = createLucideIcon(Image, 'ModernImageIcon');
export const ModernEditIcon = createLucideIcon(Pencil, 'ModernEditIcon');
export const ModernCheckIcon = createLucideIcon(Check, 'ModernCheckIcon');

// ── Original Content / App ──

export const ModernAudioLinesIcon = createLucideIcon(AudioLines, 'ModernAudioLinesIcon');
export const ModernSmartphoneIcon = createLucideIcon(Smartphone, 'ModernSmartphoneIcon');
export const ModernMaximizeIcon = createLucideIcon(Maximize, 'ModernMaximizeIcon');
export const ModernPauseIcon = createLucideIcon(Pause, 'ModernPauseIcon');
export const ModernArrowUpDownIcon = createLucideIcon(ArrowUpDown, 'ModernArrowUpDownIcon');
export const ModernListPlusIcon = createLucideIcon(ListPlus, 'ModernListPlusIcon');
export const ModernListIcon = createLucideIcon(List, 'ModernListIcon');
export const ModernLayoutGridIcon = createLucideIcon(LayoutGrid, 'ModernLayoutGridIcon');
export const ModernXIcon = createLucideIcon(X, 'ModernXIcon');
