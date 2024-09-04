const cardsWrapper = document.querySelector('.cards-wrapper');
const arrows = document.querySelectorAll('.arrow');

let currentIndex = 0;
const cards = Array.from(cardsWrapper.children);
const cardCount = cards.length;
const cardWidth = cards[0].offsetWidth + parseInt(window.getComputedStyle(cards[0]).marginRight);
const totalWidth = cardCount * cardWidth;

// Function to update card position
function updateCards() {
    cardsWrapper.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
}

// Function to handle page navigation
function goToPage(direction) {
    if (direction === 'next') {
        currentIndex = (currentIndex + 1) % cardCount;

        // Handle resetting position after reaching the end
        if (currentIndex === 0) {
            cardsWrapper.style.transition = 'none';
            cardsWrapper.style.transform = `translateX(0px)`;
            setTimeout(() => {
                cardsWrapper.style.transition = 'transform 0.3s ease';
            }, 0);
        }
    } else if (direction === 'prev') {
        if (currentIndex === 0) {
            cardsWrapper.style.transition = 'none';
            currentIndex = cardCount - 1;
            cardsWrapper.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
            setTimeout(() => {
                cardsWrapper.style.transition = 'transform 0.3s ease';
            }, 0);
        } else {
            currentIndex = (currentIndex - 1 + cardCount) % cardCount;
        }
    }

    updateCards();
}

// Function to handle button clicks
function handleButtonClick(event) {
    if (event.target.tagName === 'BUTTON') {
        const phoneNumber = event.target.nextElementSibling;
        phoneNumber.style.display = phoneNumber.style.display === 'block' ? 'none' : 'block';
    }
}

// Initialize slider and event listeners
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.container').addEventListener('click', (event) => {
        handleButtonClick(event);
    });

    arrows.forEach(arrow => {
        arrow.addEventListener('click', () => {
            if (arrow.classList.contains('left')) {
                goToPage('prev');
            } else if (arrow.classList.contains('right')) {
                goToPage('next');
            }
        });
    });

    // Initial card position
    updateCards();
});
