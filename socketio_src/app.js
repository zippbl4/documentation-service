const { createServer } = require('http');
const { Server } = require('socket.io');
const { createAdapter } = require('@socket.io/redis-adapter');
const Redis = require('ioredis');

// Создаем Redis клиенты
const pubClient = new Redis({
    host: 'redis',
    port: 6379
});
const subClient = pubClient.duplicate();

// Создаем HTTP сервер
const httpServer = createServer();

// Инициализируем Socket.IO сервер
const io = new Server(httpServer, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

// Подключаем Redis адаптер
io.adapter(createAdapter(pubClient, subClient));

// Обработка подключений
io.on('connection', (socket) => {
    console.log('Client connected');

    socket.on('disconnect', () => {
        console.log('Client disconnected');
    });
});

// Подписываемся на Redis канал
subClient.subscribe('socket.io', (err) => {
    if (err) throw err;
});

// Обработка сообщений из Redis
subClient.on('message', (channel, message) => {
    try {
        const data = JSON.parse(message);
        io.emit(data.channel, data.message);
    } catch (e) {
        console.error('Error processing message:', e);
    }
});

// Запуск сервера
const PORT = 6001;
httpServer.listen(PORT, () => {
    console.log(`Socket.IO server running on port ${PORT}`);
});