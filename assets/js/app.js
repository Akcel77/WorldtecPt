import '../css/app.scss';

import {Dropdown} from "bootstrap";


document.addEventListener('DOMContentLoaded', () => {
    new App();

    // // Déplacer cette partie à l'intérieur de DOMContentLoaded
    // document.getElementById('changeColorButton').addEventListener('click', function() {
    //     console.log('test')
    //
    //     let rootStyle = document.documentElement.style;
    //     let currentColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
    //
    //     // Change la couleur entre rouge et la couleur originale
    //     if (currentColor === '#ffffff') {
    //         rootStyle.setProperty('--primary', '#000000');
    //     } else {
    //         rootStyle.setProperty('--primary', '#ffffff');
    //     }
    // });
});

class App{
    constructor() {
        this.enableDropdowns();
        this.handleCommentForm();
    }

    enableDropdowns = () => {
        const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        dropdownElementList.map(function (dropdownToggleEl) {
            return new Dropdown(dropdownToggleEl)
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

        })

    }


}
