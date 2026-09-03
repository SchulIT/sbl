import axios from 'axios';
import TomSelect from "tom-select";
import { Modal } from 'bootstrap';

async function updatePreview($select, $preview, url, $modal, maximumNumberOfCopies) {
    let copies = getCopies($select);

    if(copies.length >= maximumNumberOfCopies) {
        let diff = copies.length - maximumNumberOfCopies;

        let modal = Modal.getOrCreateInstance($modal);
        modal.show();
    } else {
        let response = await axios.get(url + '?ids=' + copies.join(','));
        $preview.innerHTML = response.data;
    }
}

function getCopies($select) {
    return $select.value.split(',');
}

document.addEventListener('DOMContentLoaded', async () => {
    let $preview = document.getElementById('preview');
    let $select = document.querySelector($preview.getAttribute('data-select'));

    new TomSelect($select, {
        create: true
    });

    let url = $preview.getAttribute('data-url');
    let $modal = document.getElementById('modalError');
    let maximumNumberOfCopies = parseInt($preview.getAttribute('data-maximum'));

    $select.addEventListener('change', async () => {
        await updatePreview($select, $preview, url, $modal, maximumNumberOfCopies);
    });

    await updatePreview($select, $preview, url);

    let $user = document.querySelector('#bulk_checkout_request_borrower');

    if($user === null) {
        return;
    }

    $user.addEventListener('change',  () => {
        $select.tomselect.focus();
    });
});