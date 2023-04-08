import clsx from 'clsx';
import {usePlayerStore} from '@common/player/hooks/use-player-store';

export function BufferingIndicator() {
  const status = usePlayerStore(s => s.status);
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="48"
      height="48"
      fill="none"
      className={clsx(
        'absolute -top-3 -left-3 z-10 transition-opacity duration-300 pointer-events-none',
        status === 'buffering' ? 'animate-spin opacity-100' : 'opacity-0'
      )}
    >
      <g clipPath="url(#a)">
        <path
          stroke="url(#b)"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeMiterlimit="10"
          d="M45.72 31.644c-1.016 3.036-2.777 5.84-5.116 8.301-8.846 9.161-23.386 9.5-32.547.654-9.16-8.845-9.416-23.44-.654-32.546"
        />
      </g>
      <defs>
        <linearGradient
          id="b"
          x1="7.863"
          x2="45.527"
          y1="7.178"
          y2="31.53"
          gradientUnits="userSpaceOnUse"
        >
          <stop stopColor="currentColor" />
          <stop offset="1" stopColor="currentColor" stopOpacity="0" />
        </linearGradient>
        <clipPath>
          <path fill="currentColor" d="M0 0h48v48H0z" />
        </clipPath>
      </defs>
    </svg>
  );
}
