<?php

namespace App\AsyncTask\Channels;

class PipeBuffer
{
    private string $pipePath;
    private int $bufferSize;

    private mixed $writer = null;
    private mixed $reader = null;

    public function open(string $channel, int $buffer = 1): void
    {
        $this->pipePath = storage_path($channel);
        $this->bufferSize = $buffer;

        // Создаём именованный канал, если он ещё не существует
        if (file_exists($this->pipePath)) {
            return;
        }

        if (! posix_mkfifo($this->pipePath, 0666)) {
            throw new \Exception("Unable to create named pipe at {$this->pipePath}");
        }
    }

    public function close(): void
    {
        $this->closeWriter();
        $this->closeReader();
        $this->closePipe();
    }

    private function closePipe(): void
    {
        if (file_exists($this->pipePath)) {
            unlink($this->pipePath);
        }
    }

    public function write(string $data): void
    {
        $fp = $this->openWriter();
        fwrite($fp, $data . PHP_EOL);
    }

    private function openWriter(): mixed
    {
        if ($this->writer === null) {
            $this->writer = fopen($this->pipePath, 'w');
        }

        if ($this->writer === false) {
            throw new \Exception("Unable to open pipe for writing");
        }

        return $this->writer;
    }

    private function closeWriter(): void
    {
        if ($this->writer === null) {
            return;
        }

        fclose($this->writer);
    }

    private function openReader(): mixed
    {
        if ($this->reader === null) {
            $this->reader = fopen($this->pipePath, 'r');
        }

        if ($this->reader === false) {
            throw new \Exception("Unable to open pipe for reading");
        }

        return $this->reader;
    }

    private function closeReader(): void
    {
        if ($this->reader === null) {
            return;
        }

        fclose($this->reader);
    }

    public function read(): ?string
    {
        $fp = $this->openReader();
        $data = fgets($fp);
        return $data !== false ? trim($data) : null;
    }

    public function isFull($path): bool
    {
        $fp = fopen($path, 'r');

        if (stream_set_blocking($fp, 0)) {
            throw new \Exception("Couldn't lock file for reading.\n");
        }

        if (! flock($fp, LOCK_SH)) {
            throw new \Exception("Couldn't lock file for reading.\n");
        }

        try {
            return fgets($fp) === false;
        } finally {
            stream_set_blocking($fp, 1);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public function isEmpty(): bool
    {
        return filesize($this->pipePath) === 0;
    }
}
