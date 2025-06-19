document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.buy-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const gameId = form.dataset.gameid || form.querySelector('[name="game_id"]').value;
            const formData = new FormData();
            formData.append('game_id', gameId);

            // Automatycznie wykryj ścieżkę do buy_game.php
            let url = 'ajax/buy_game.php';
            if (window.location.pathname.includes('/admin/')) {
                url = '../ajax/buy_game.php';
            }

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.json())
            .then(data => {
                showToast(data.message);
                if (data.success) {
                    setTimeout(() => window.location.reload(), 1500);
                }
            })
            .catch(() => {
                showToast('Wystąpił błąd przy zakupie.');
            });
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.wishlist-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const gameId = form.dataset.gameid || form.querySelector('[name="game_id"]').value;
            const formData = new FormData();
            formData.append('game_id', gameId);

            // Automatycznie wykryj ścieżkę do wishlist_add.php
            let url = 'wishlist_add.php';
            if (window.location.pathname.includes('/admin/')) {
                url = '../wishlist_add.php';
            }

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(resp => resp.json())
            .then(data => {
                showToast(data.message);
                if (data.success) {
                    setTimeout(() => window.location.reload(), 1500);
                }
            })
            .catch(() => {
                showToast('Wystąpił błąd przy dodawaniu do listy życzeń.');
            });
        });
    });
});



document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(reviewForm);
            fetch('ajax/add_review.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    window.location.reload();
                }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Obsługa AJAX do kupowania gier (już masz)
    // Możesz dodać inne interakcje, np. podświetlenie aktywnego linku w menu
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        if (link.href === window.location.href) {
            link.style.textDecoration = "underline";
        }
    });
});

function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.style.display = 'block';
    toast.style.opacity = '1';

    setTimeout(() => {
        toast.style.transition = 'opacity 0.4s';
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.style.display = 'none';
            toast.style.transition = '';
        }, 400);
    }, 3500); // 3,5 sekundy widoczne
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.play-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const title = btn.getAttribute('data-title');
            showToast('Uruchamianie ' + title);
        });
    });
});

// Dodaj na końcu pliku main.js
