import {
  Flame, PartyPopper, Zap, Music, Sparkles,
  TreePine, Sword, Heart, CloudRain, HandHeart,
  UtensilsCrossed, Coffee,
  Smile, Sun, Moon, Flower2, Wind,
  CloudDrizzle, Cloud, Trophy, AlertTriangle,
  Skull, Angry, HandFist, Bolt,
} from 'lucide-react';
import type {LucideIcon} from 'lucide-react';

export interface MoodInfo {
  icon: LucideIcon;
  label: string;
  cls: string;
}

export const MOOD_CONFIG: Record<string, MoodInfo> = {
  // === Gabonese cultural moods ===
  boma:         {icon: Flame,            label: 'Boma',           cls: 'player-mood-boma'},
  bangando:     {icon: PartyPopper,      label: 'Bangando',       cls: 'player-mood-bangando'},
  niamatos:     {icon: Zap,              label: 'Niamatos',       cls: 'player-mood-niamatos'},
  reglage:      {icon: Music,            label: 'Réglage',        cls: 'player-mood-reglage'},
  bwiti:        {icon: Sparkles,         label: 'Bwiti',          cls: 'player-mood-bwiti'},
  baka:         {icon: TreePine,         label: 'Baka',           cls: 'player-mood-baka'},
  mvett:        {icon: Sword,            label: 'Mvett',          cls: 'player-mood-mvett'},
  nia_ku_rondi: {icon: Heart,            label: 'Nia ku rondi',   cls: 'player-mood-nia-ku-rondi'},
  fatigue:      {icon: CloudRain,        label: 'Fatigué',        cls: 'player-mood-fatigue'},
  mbolo:        {icon: HandHeart,        label: 'Mbolo',          cls: 'player-mood-mbolo'},
  coupe_coupe:  {icon: UtensilsCrossed,  label: 'Coupé-Coupé',   cls: 'player-mood-coupe-coupe'},
  olo:          {icon: Coffee,           label: 'Olo',            cls: 'player-mood-olo'},
  // === Universal moods ===
  amusement:    {icon: Smile,            label: 'Amusement',      cls: 'player-mood-amusement'},
  joy:          {icon: Sun,              label: 'Joie',           cls: 'player-mood-joy'},
  eroticism:    {icon: Moon,             label: 'Érotisme',       cls: 'player-mood-eroticism'},
  beauty:       {icon: Flower2,          label: 'Beauté',         cls: 'player-mood-beauty'},
  relaxation:   {icon: Wind,             label: 'Relaxation',     cls: 'player-mood-relaxation'},
  sadness:      {icon: CloudDrizzle,     label: 'Tristesse',      cls: 'player-mood-sadness'},
  dreaminess:   {icon: Cloud,            label: 'Rêverie',        cls: 'player-mood-dreaminess'},
  triumph:      {icon: Trophy,           label: 'Triomphe',       cls: 'player-mood-triumph'},
  anxiety:      {icon: AlertTriangle,    label: 'Anxiété',        cls: 'player-mood-anxiety'},
  scariness:    {icon: Skull,            label: 'Peur',           cls: 'player-mood-scariness'},
  annoyance:    {icon: Angry,            label: 'Agacement',      cls: 'player-mood-annoyance'},
  defiance:     {icon: HandFist,          label: 'Défi',           cls: 'player-mood-defiance'},
  pumped_up:    {icon: Bolt,             label: 'Énergie',        cls: 'player-mood-pumped-up'},
};
