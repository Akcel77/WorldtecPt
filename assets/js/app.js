import '../css/app.scss';

import { Dropdown } from "bootstrap";

document.addEventListener('DOMContentLoaded', () => {
    new App();

    // Initialiser le bouton de basculement de thème et le thème actuel
    const themeToggle = document.getElementById('theme-toggle');
    initializeTheme();

    // Gestion de l'événement de clic sur le bouton de basculement de thème
    themeToggle.addEventListener('click', function() {
        toggleTheme();
        updateButtonTheme();
    });
});

function initializeTheme() {
    // Charger le thème sauvegardé ou utiliser le thème clair par défaut
    const savedTheme = localStorage.getItem('theme') || 'light-mode';
    document.body.classList.add(savedTheme);
    updateButtonTheme();
}

function toggleTheme() {
    // Basculer entre le mode clair et sombre
    const isDarkMode = document.body.classList.contains('dark-mode');
    const newTheme = isDarkMode ? 'light-mode' : 'dark-mode';
    document.body.classList.replace(isDarkMode ? 'dark-mode' : 'light-mode', newTheme);
    localStorage.setItem('theme', newTheme);
}

function updateButtonTheme() {
    // Mettre à jour le texte du bouton de basculement de thème
    const themeToggle = document.getElementById('theme-toggle');
    if (document.body.classList.contains('dark-mode')) {
        themeToggle.textContent = 'Light Mode';
    } else {
        themeToggle.textContent = 'Dark Mode';
    }
}

class App {
    constructor() {
        this.enableDropdowns();
        this.handleCommentForm();
    }

    enableDropdowns = () => {
        const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.map(function (dropdownToggleEl) {
            return new Dropdown(dropdownToggleEl);
        });
    }

    handleCommentForm() {
        const commentForm = document.querySelector('form.comment-form');

        if (null === commentForm){
            return;
        }

        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const response = await fetch('/ajax/comments', {
                method: 'POST',
                body: new FormData(e.target)
            });

            if(!response.ok){
                console.error(await response.text());
                return;
            }

            const json = await response.json();

            if (json.code === 'COMMENT_ADDED_SUCCESSFULLY'){
                const commentList = document.querySelector('.comment-list');
                const commentCount = document.querySelector('.comment-count');
                const commentContent = document.querySelector('#comment_content');
                commentList.insertAdjacentHTML('afterbegin', json.message);
                commentCount.innerText = json.numberOfComments;
                commentContent.value = '';
            }
        });
    }
}
