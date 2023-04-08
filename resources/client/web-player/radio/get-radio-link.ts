import {slugifyString} from '@common/utils/string/slugify-string';
import {getBootstrapData} from '@common/core/bootstrap-data/use-backend-bootstrap-data';
import {RadioSeed} from '@app/web-player/radio/requests/use-radio-recommendations';

export function getRadioLink(
  seed: RadioSeed,
  {absolute}: {absolute?: boolean} = {}
): string {
  let link = `/radio/${seed.model_type}/${seed.id}/${slugifyString(seed.name)}`;
  if (absolute) {
    link = `${getBootstrapData().settings.base_url}${link}`;
  }
  return link;
}
