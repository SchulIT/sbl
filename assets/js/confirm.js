import { Modal } from "bootstrap";

for(let $form of document.querySelectorAll('[data-confirm=true]')) {
    let isConfirmed = false;

    $form.addEventListener('submit', function(event) {
        if(isConfirmed) {
            return; // prevent loops
        }

        let $modal = document.querySelector($form.getAttribute('data-confirm-modal'));

        if($modal === null) {
            console.error('$modal not found');
            return;
        }

        let $confirm = $modal.querySelector('[data-confirm-button]');

        if($confirm === null) {
            return;
        }

        $confirm.addEventListener('click', function() {
            isConfirmed = true;
            $form.submit();
        });

        event.preventDefault();

        let modal = new Modal($modal);
        modal.show();
    });
}