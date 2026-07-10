import axios from 'axios';
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

function updateCounter($select, $counter) {
    let copies = getCopies($select);

    let $template = $counter.querySelector('template');
    let templateText = $template.innerHTML;

    let text = templateText.replace('%count%', copies.length);
    let $p = $counter.querySelector('p');

    $p.innerHTML = text;

    if(copies.length > 0) {
        $counter.classList.remove('d-none');
    } else {
        $counter.classList.add('d-none');
    }
}

function getCopies($select) {
    // Selected options:
    let copies = [ ];
    for(let option of $select.selectedOptions) {
        copies.push(option.value);
    }

    return copies;
}

document.addEventListener('DOMContentLoaded', async () => {
    let $preview = document.getElementById('checkout_preview');
    let $select = document.querySelector($preview.getAttribute('data-select'));
    let url = $preview.getAttribute('data-url');
    let $modal = document.getElementById('modalError');
    let maximumNumberOfCopies = parseInt($preview.getAttribute('data-maximum'));

    $select.addEventListener('change', async event => {
        updateCounter($select, document.getElementById('counter'));
        await updatePreview($select, $preview, url, $modal, maximumNumberOfCopies);
    });

    await updatePreview($select, $preview, url);

    let $user = document.querySelector('#bulk_checkout_request_borrower');

    if($user === null) {
        return;
    }

    $user.addEventListener('keyup', async event => {
        if(event.key === 'Enter') {
            $select.focus();
        }
    });
});