const counter = document.getElementById('counter');
const decreaseBtn = document.getElementById('decrease');
const resetBtn = document.getElementById('reset');
const increaseBtn = document.getElementById('increase');

let count = 0;

function updateCounter() {
    counter.textContent = count;
    
    counter.classList.remove('positive', 'negative', 'animate');
    
    if (count > 0) {
        counter.classList.add('positive');
    } else if (count < 0) {
        counter.classList.add('negative');
    }
    
    counter.classList.add('animate');
    
    setTimeout(() => {
        counter.classList.remove('animate');
    }, 300);
}

decreaseBtn.addEventListener('click', () => {
    count--;
    updateCounter();
});

resetBtn.addEventListener('click', () => {
    count = 0;
    updateCounter();
});

increaseBtn.addEventListener('click', () => {
    count++;
    updateCounter();
});

updateCounter(); 