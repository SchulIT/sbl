import axios from 'axios';

function updatePreview() {
    let $input = document.getElementById('book_cover_file');
    const [ file ] = $input.files;

    if(file) {
        document.getElementById('image_preview').src = URL.createObjectURL(file);
    }
}

document.getElementById('book_cover_file').onchange = function(event) {
    updatePreview();
}

document.getElementById('isbn-input').addEventListener('keydown', async function(event) {
    if(event.key === 'Enter') {
        event.preventDefault();
        document.getElementById('isbn-import-btn').click();
    }
});

document.getElementById('isbn-import-btn').addEventListener('click', async function(event) {
    event.preventDefault();
    let $button = event.target;
    let $icon = $button.querySelector('.fas');

    let originalClassname = $icon.className;
    $icon.className = 'fa-solid fa-spinner fa-spin';
    $button.setAttribute('disabled', 'disabled');

    try {
        let $isbn = document.getElementById('isbn-input');
        let isbn = $isbn.value;

        let response = await axios.get(
            $button.getAttribute('data-url').replace('isbn', isbn)
        );

        document.getElementById('book_title').value = response.data.name;
        document.getElementById('book_subtitle').value = response.data.nameZusatz;
        document.getElementById('book_publisher').value = response.data.publisher;
        document.getElementById('book_isbn').value = response.data.isbn;

        let imageData = await fetch('data:image/png;base64,' + response.data.image);
        let imageBlob = await imageData.blob();

        let file = new File([imageBlob], "cover.png", { type: 'image/png', lastModified: (new Date()).getTime() });
        let container = new DataTransfer();
        container.items.add(file);
        document.getElementById('book_cover_file').files = container.files;
        updatePreview();
    } catch(error) {
        console.error(error);
    } finally {
        $icon.className = originalClassname;
        $button.removeAttribute('disabled');
    }
});