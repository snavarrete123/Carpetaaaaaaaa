class Game {
    constructor() {
        this.canvas = document.getElementById('gameCanvas');
        this.ctx = this.canvas.getContext('2d');
        this.canvas.width = 400;
        this.canvas.height = 600;
        
        this.player = {
            x: this.canvas.width / 2,
            y: this.canvas.height - 60,
            width: 30,
            height: 50,
            speed: 5,
            color: '#ffffff'
        };
        
        this.obstacles = [];
        this.score = 0;
        this.gameOver = false;
        this.obstacleSpeed = 5;
        this.spawnInterval = 1000;
        this.lastSpawn = 0;
        
        this.keys = {};
        this.setupEventListeners();
        this.gameLoop();
    }
    
    setupEventListeners() {
        window.addEventListener('keydown', (e) => this.keys[e.key] = true);
        window.addEventListener('keyup', (e) => this.keys[e.key] = false);
        document.getElementById('restartButton').addEventListener('click', () => this.restart());
    }
    
    spawnObstacle() {
        const now = Date.now();
        if (now - this.lastSpawn > this.spawnInterval) {
            const size = 40;
            const obstacle = {
                x: Math.random() * (this.canvas.width - size),
                y: -size,
                size: size,
                speed: this.obstacleSpeed
            };
            this.obstacles.push(obstacle);
            this.lastSpawn = now;
        }
    }
    
    update() {
        if (this.gameOver) return;
        
        if (this.keys['ArrowLeft'] && this.player.x > 0) {
            this.player.x -= this.player.speed;
        }
        if (this.keys['ArrowRight'] && this.player.x < this.canvas.width - this.player.width) {
            this.player.x += this.player.speed;
        }
        
        this.spawnObstacle();
        for (let i = this.obstacles.length - 1; i >= 0; i--) {
            const obstacle = this.obstacles[i];
            obstacle.y += obstacle.speed;
            
            if (obstacle.y > this.canvas.height) {
                this.obstacles.splice(i, 1);
                this.score++;
                document.getElementById('scoreValue').textContent = this.score;
                
                if (this.score % 3 === 0) {
                    this.obstacleSpeed += 0.3;
                    this.spawnInterval = Math.max(250, this.spawnInterval - 30);
                }
            }
            
            if (this.checkCollision(this.player, obstacle)) {
                this.endGame();
            }
        }
    }
    
    checkCollision(player, obstacle) {
        const playerCenter = {
            x: player.x + player.width / 2,
            y: player.y + player.height / 2
        };
        
        const obstacleCenter = {
            x: obstacle.x + obstacle.size / 2,
            y: obstacle.y + obstacle.size / 2
        };
        
        const distance = Math.sqrt(
            Math.pow(playerCenter.x - obstacleCenter.x, 2) +
            Math.pow(playerCenter.y - obstacleCenter.y, 2)
        );
        
        return distance < (player.width / 2 + obstacle.size / 2);
    }
    
    drawTriangle(x, y, size) {
        this.ctx.fillStyle = '#ff0000';
        this.ctx.beginPath();
        this.ctx.moveTo(x + size/2, y + size);
        this.ctx.lineTo(x, y);
        this.ctx.lineTo(x + size, y);
        this.ctx.closePath();
        this.ctx.fill();
        
        this.ctx.strokeStyle = '#ffffff';
        this.ctx.lineWidth = 2;
        this.ctx.stroke();
    }
    
    drawStickFigure(x, y, width, height) {
        this.ctx.strokeStyle = this.player.color;
        this.ctx.lineWidth = 2;
        
        this.ctx.beginPath();
        this.ctx.arc(x + width/2, y + 10, 8, 0, Math.PI * 2);
        this.ctx.stroke();
        
        this.ctx.beginPath();
        this.ctx.moveTo(x + width/2, y + 18);
        this.ctx.lineTo(x + width/2, y + 35);
        this.ctx.stroke();
        
        this.ctx.beginPath();
        this.ctx.moveTo(x + width/2 - 10, y + 25);
        this.ctx.lineTo(x + width/2 + 10, y + 25);
        this.ctx.stroke();
        
        this.ctx.beginPath();
        this.ctx.moveTo(x + width/2, y + 35);
        this.ctx.lineTo(x + width/2 - 8, y + 45);
        this.ctx.moveTo(x + width/2, y + 35);
        this.ctx.lineTo(x + width/2 + 8, y + 45);
        this.ctx.stroke();
    }
    
    draw() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        this.drawStickFigure(this.player.x, this.player.y, this.player.width, this.player.height);
        
        this.obstacles.forEach(obstacle => {
            this.drawTriangle(obstacle.x, obstacle.y, obstacle.size);
        });
    }
    
    endGame() {
        this.gameOver = true;
        document.getElementById('gameOver').classList.remove('hidden');
        document.getElementById('finalScore').textContent = this.score;
    }
    
    restart() {
        this.player.x = this.canvas.width / 2;
        this.obstacles = [];
        this.score = 0;
        this.gameOver = false;
        this.obstacleSpeed = 5;
        this.spawnInterval = 1000;
        document.getElementById('scoreValue').textContent = '0';
        document.getElementById('gameOver').classList.add('hidden');
    }
    
    gameLoop() {
        this.update();
        this.draw();
        requestAnimationFrame(() => this.gameLoop());
    }
}

window.addEventListener('load', () => {
    new Game();
}); 