<?php

declare(strict_types=1);

namespace Roave\Signature;

use Roave\Signature\Encoder\EncoderInterface;

use function assert;
use function is_string;
use function preg_match;
use function preg_replace;

final class FileContentChecker implements CheckerInterface
{
    private EncoderInterface $encoder;

    /**
     * {@inheritDoc}
     */
    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function check(string $phpCode): bool
    {
        if (! preg_match('{Roave/Signature:\s+([a-zA-Z0-9\/=]+)}', $phpCode, $matches)) {
            return false;
        }

        return $this->encoder->verify($this->stripCodeSignature($phpCode), $matches[1]);
    }

    private function stripCodeSignature(string $phpCode): string
    {
        $replaced = preg_replace('{[\/\*\s]+Roave/Signature:\s+([a-zA-Z0-9\/\*\/ =]+)}', '', $phpCode);

        assert(is_string($replaced));

        return $replaced;
    }
}
