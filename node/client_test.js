var WebSocketClient = require('websocket').client;
var client = new WebSocketClient();

// Вешаем на него обработчик события подключения к серверу
client.on('connect', handler);

// Подключаемся к нужному ресурсу
client.connect('ws://localhost:9090/');

var args = process.argv.slice(2);

var message = {
    "body": args[0],
    "recipients": args.slice(1)
};

console.log(message);

function handler(connection) {
    connection.on('message', function (message) {
        // делаем что-нибудь с пришедшим сообщением
        console.log(message.utf8Data);
    });
    connection.send(JSON.stringify(message));
}
