<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest;

use PhpSchool\Terminal\Terminal;

class MockTerminal implements Terminal
{
    /**
     * @inheritDoc
     */
    public function getWidth(): int
    {
        return 100;
    }

    /**
     * @inheritDoc
     */
    public function getHeight(): int
    {
        return 30;
    }

    /**
     * @inheritDoc
     */
    public function getColourSupport(): int
    {
        return 8;
    }

    /**
     * @inheritDoc
     */
    public function disableEchoBack(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function enableEchoBack(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function isEchoBack(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function disableCanonicalMode(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function enableCanonicalMode(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function isCanonicalMode(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isInteractive(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function restoreOriginalConfiguration(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function supportsColour(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function clearLine(): void
    {
        //noop
    }

    /**
     * @inheritDoc
     */
    public function clearDown(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function clean(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function enableCursor(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function disableCursor(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function moveCursorToTop(): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function moveCursorToRow(int $rowNumber): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function moveCursorToColumn(int $columnNumber): void
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function read(int $bytes): string
    {
        // noop
    }

    /**
     * @inheritDoc
     */
    public function write(string $buffer): void
    {
        // noop
    }
}
