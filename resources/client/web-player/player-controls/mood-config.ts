import {Zap, Cloud, Heart, Smile, Sparkles, Church} from 'lucide-react';
import type {LucideIcon} from 'lucide-react';

export interface MoodInfo {
  icon: LucideIcon;
  label: string;
  cls: string;
}

export const MOOD_CONFIG: Record<string, MoodInfo> = {
  energetic:   {icon: Zap,      label: 'Énergique',    cls: 'player-mood-energetic'},
  chill:       {icon: Cloud,    label: 'Détendu',      cls: 'player-mood-chill'},
  romantic:    {icon: Heart,    label: 'Romantique',   cls: 'player-mood-romantic'},
  happy:       {icon: Smile,    label: 'Joyeux',       cls: 'player-mood-happy'},
  focused:     {icon: Sparkles, label: 'Concentré',    cls: 'player-mood-focused'},
  melancholic: {icon: Cloud,    label: 'Mélancolique', cls: 'player-mood-melancholic'},
  spiritual:   {icon: Church,   label: 'Spirituel',    cls: 'player-mood-spiritual'},
};
