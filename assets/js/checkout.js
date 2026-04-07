import axios from 'axios';

async function updatePreview($select, $preview, url) {
    // Selected options:
    let copies = [ ];
    for(let option of $select.selectedOptions) {
        copies.push(option.value);
    }

    let response = await axios.get(url + '?ids=' + copies.join(','));
    $preview.innerHTML = response.data;
}

document.addEventListener('DOMContentLoaded', async () => {
    let $preview = document.getElementById('checkout_preview');
    let $select = document.querySelector($preview.getAttribute('data-select'));
    let url = $preview.getAttribute('data-url');

    $select.addEventListener('change', async event => {
        await updatePreview($select, $preview, url);
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