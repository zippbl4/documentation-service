<?php

namespace App\AsyncTask\Channels;

class FileBuffer
{
    private string $channel;
    private int $bufferSize;

    public function open(string $channel, int $buffer = 1): void
    {
        $this->channel = storage_path($channel);
        $this->bufferSize = $buffer;

        if (!file_exists($this->channel)) {
            file_put_contents($this->channel, '');
        }
    }

    public function close(): void
    {
        if (file_exists($this->channel)) {
            unlink($this->channel);
        }
    }

    public function write(string $data): void
    {
        $this->buffer('a+', function ($fp) use ($data): void {
            fwrite($fp, $data . PHP_EOL);
            fflush($fp);
        });
    }

    public function read(): ?string
    {
        return $this->buffer('c+', function ($fp): ?string {
            $lines = file($this->channel, FILE_IGNORE_NEW_LINES);
            $data = null;
            if (!empty($lines)) {
                $data = array_shift($lines);
                ftruncate($fp, 0);
                rewind($fp);
                fwrite($fp, implode(PHP_EOL, $lines) . PHP_EOL);
                fflush($fp);
            }
            return $data;
        });
    }

    public function isFull(): bool
    {
        return count(file($this->channel)) >= $this->bufferSize;
    }

    public function isEmpty(): bool
    {
        return count(file($this->channel)) === 0;
    }

    private function buffer(string $mode, callable $fn): mixed
    {
        $fp = fopen($this->channel, $mode);
        if (! flock($fp, LOCK_EX)) {
            throw new \Exception("Unable to lock the file!");
        }

        try {
            return $fn($fp);
        } finally {
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }
}
