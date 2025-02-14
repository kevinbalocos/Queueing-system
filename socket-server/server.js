const express = require("express");
const http = require("http");
const socketIo = require("socket.io");
const cors = require("cors");

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

app.use(cors());
app.use(express.json());

io.on("connection", (socket) => {
    console.log("A user connected");

    socket.on("disconnect", () => {
        console.log("A user disconnected");
    });
});

// Receive queue item from CodeIgniter
app.post("/newQueueItem", (req, res) => {
    const data = req.body;
    console.log("Received from CodeIgniter:", data);
    io.emit("updateQueue", data); // Broadcast to all clients
    res.sendStatus(200);
});

server.listen(3000, () => {
    console.log("Socket.io server running on port 3000");
});
