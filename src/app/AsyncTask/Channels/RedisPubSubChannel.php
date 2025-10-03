<?php

namespace App\AsyncTask\Channels;

use App\AsyncTask\Exceptions\ReceiverTimeoutException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class RedisPubSubChannel implements ChannelContract
{
    protected string $channel;
    protected string $subscribersKey;
    protected string $messagesKey;
    protected string $messageId = '0';

    protected int $timeout = 10;

    public function open(string $channel): void
    {
        $this->channel = $channel;
        $this->subscribersKey = "$channel:subscribers";
        $this->messagesKey = "$channel:messages";

        $this->incrementSubscribers();
    }

    public function close(): void
    {
        if ($this->decrementSubscribers() <= 0) {
            Redis::del($this->channel);
            Redis::del($this->subscribersKey);
            Redis::del($this->messagesKey);
        }
    }

    /**
     * Проверяет, можно ли закрыть канал (если сообщений нет).
     */
    public function readyToClose(): void
    {
        $info = Redis::xInfo('STREAM', $this->channel);
        $info['length'] === 0;
    }

    /**
     * Отправляет сообщение в канал.
     */
    public function send(string $data): void
    {
        $messageId = Redis::xAdd($this->channel, '*', ['message' => $data]);
        $this->registerMessage($messageId);
    }

    /**
     * Получает одно сообщение.
     */
    public function receive(): string
    {
        //Redis::xRead
        //
        //Аргументы:
        //$streams (array) – Список потоков и их ID последнего прочитанного сообщения.
        //Формат: ['stream_name' => 'last_id'].
        //last_id:
        //'0' – прочитать все сообщения с начала.
        //'>' – ждать новые сообщения.
        //'message_id' – читать с указанного ID.
        //$count (int, необязательный) – Максимальное количество сообщений (по умолчанию 0 – без ограничения).
        //$block (int, необязательный) – Таймаут в миллисекундах (по умолчанию null, что означает немедленный возврат).
        //Если 0 – метод сразу возвращает данные (без ожидания).
        //Если > 0 – метод ждёт указанное время (в мс) новых сообщений.
        //
        //Возвращает:
        //array – если есть сообщения (['stream_name' => ['message_id' => ['key' => 'value']]]).
        //false – если произошла ошибка.
        //null – если сообщений нет.
        $messages = Redis::xRead(
            [$this->channel => $this->messageId],
            1,
            $this->timeout * 1000
        );

        if (! $messages || ! isset($messages[$this->channel])) {
            return '';
        }

        $message = $messages[$this->channel];
        $messageId = key($message);
        $value = current($message);

        $this->messageId = $messageId;
        $this->acknowledgeMessage($messageId);

        return $value['message'] ?? '';
    }

    public function receiveAll(): \Generator
    {
        $time = Carbon::now()->addSeconds($this->timeout);
        while (true) {
            switch (true) {
                case $data = $this->receive():
                    yield $data;
                    $time = Carbon::now()->addSeconds($this->timeout);
                    break;
                case Carbon::now()->greaterThan($time):
                    $this->close();
                    return;
            }
        }
    }


    /**
     * Регистрирует, сколько подписчиков должно обработать сообщение.
     */
    protected function registerMessage(string $messageId): void
    {
        $subscribersCount = Redis::hget($this->subscribersKey, 'count') ?? 0;
        Redis::hset($this->messagesKey, $messageId, $subscribersCount);
    }

    /**
     * Помечает сообщение как обработанное подписчиком.
     * Удаляет сообщение, если все подписчики обработали его.
     */
    protected function acknowledgeMessage(string $messageId): void
    {
        if (Redis::hincrby($this->messagesKey, $messageId, -1) <= 0) {
            Redis::xDel($this->channel, [$messageId]);
            Redis::hdel($this->messagesKey, $messageId);
        }
    }

    /**
     * Увеличивает количество активных подписчиков.
     */
    protected function incrementSubscribers(): void
    {
        Redis::hincrby($this->subscribersKey, 'count', 1);
    }

    /**
     * Уменьшает количество активных подписчиков.
     * @return int Оставшееся количество подписчиков.
     */
    protected function decrementSubscribers(): int
    {
        return Redis::hincrby($this->subscribersKey, 'count', -1);
    }
}
