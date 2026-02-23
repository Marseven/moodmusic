import {FormChipField} from '@common/ui/forms/input-field/chip-field/form-chip-field';
import {Trans} from '@common/i18n/trans';
import React, {useState} from 'react';
import {useArtistPickerSuggestions} from '@app/web-player/artists/artist-picker/use-artist-picker-suggestions';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {Item} from '@common/ui/forms/listbox/item';

interface FormArtistPickerProps {
  name: string;
  className?: string;
  artistType?: string;
}
export function FormArtistPicker({name, className, artistType}: FormArtistPickerProps) {
  const {trans} = useTrans();
  const [inputValue, setInputValue] = useState('');
  const {data, isLoading} = useArtistPickerSuggestions({
    query: inputValue,
    artist_type: artistType,
  });

  return (
    <FormChipField
      className={className}
      name={name}
      label={<Trans message="Artistes" />}
      isAsync
      inputValue={inputValue}
      onInputValueChange={setInputValue}
      suggestions={data?.results}
      placeholder={trans(message('+Ajouter un artiste'))}
      isLoading={isLoading}
      allowCustomValue={false}
    >
      {data?.results.map(result => (
        <Item
          value={result}
          key={result.id}
          startIcon={
            result.image ? (
              <img
                className="rounded-full object-cover w-24 h-24"
                src={result.image}
                alt=""
              />
            ) : undefined
          }
        >
          <span className="flex items-center gap-6">
            {result.name}
            {(result as any).artist_type && (
              <span className="text-[10px] text-muted bg-chip rounded px-4 py-1 uppercase">
                {(result as any).artist_type}
              </span>
            )}
          </span>
        </Item>
      ))}
    </FormChipField>
  );
}
