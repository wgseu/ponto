const Server = require('./server')

const server = new Server()

server.start(() => {
  console.log('Websocket running.');
})

process.on('SIGTERM', () => {
  console.log('Shutting down');
  server.close(() => {
    console.log('Server closed');
    process.exit(128 + 15);
  });
});
