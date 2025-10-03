<?php

namespace App\Broadcasting;

use App\Contracts\NameInterface;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SocketIoBroadcaster extends Broadcaster implements NameInterface
{
    protected $channel;

    public function __construct()
    {
        $this->channel = config('broadcasting.connections.socketio.channel');
    }

    public static function getName(): string
    {
        return 'socketio';
    }

    public function auth($request)
    {
        if (Str::startsWith($request->channel_name, ['private-', 'presence-']) &&
            !$request->user()) {
            throw new AccessDeniedHttpException();
        }

        $channelName = $this->normalizeChannelName($request->channel_name);

        return parent::verifyUserCanAccessChannel(
            $request,
            $channelName
        );
    }

    public function validAuthenticationResponse($request, $result)
    {
        if (Str::startsWith($request->channel_name, 'private')) {
            return response()->json([
                'channel_data' => [
                    'user_id' => $request->user()->id,
                    'user_info' => $result,
                ]
            ]);
        }

        return response()->json(true);
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $message = json_encode([
            'event' => $event,
            'data' => $payload,
            'socket' => null
        ]);

        foreach ($channels as $channel) {
            Redis::publish(
                $this->channel,
                json_encode(['channel' => $channel, 'message' => $message])
            );
        }
    }

    protected function normalizeChannelName($channel)
    {
        return preg_replace('/^(private|presence)-/', '', $channel);
    }
}
