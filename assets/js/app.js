import Choices from "choices.js";
import { Modal, Tooltip, Popover } from "bootstrap";
import "../styles/app.css";

document.addEventListener('DOMContentLoaded', function() {
    let initializeChoice = function(el) {
        let removeItemButton = false;

        if(el.getAttribute('multiple') !== null) {
            removeItemButton = true;
        }

        let config = {
            itemSelectText: '',
            shouldSort: false,
            shouldSortItems: false,
            removeItemButton: removeItemButton,
            placeholder: true
        };

        el.choices = new Choices(el, config);
    };

    document.querySelectorAll('select[data-choice=true]').forEach(function(el) {
        initializeChoice(el);
    });

    document.querySelectorAll('[title]').forEach(function(el) {
        new Tooltip(el, {
            placement: 'bottom'
        });
    });

    document.querySelectorAll('[data-trigger="submit"]').forEach(function (el) {
        let eventName = 'change';

        if(el.nodeName === 'BUTTON') {
            eventName = 'click';
        }

        el.addEventListener(eventName, function (event) {
            let confirmModalSelector = el.getAttribute('data-confirm');
            let form = this.closest('form');

            if(confirmModalSelector === null || confirmModalSelector === '') {
                form.submit();
                return;
            }

            let modalEl = document.querySelector(confirmModalSelector);
            let modal = new Modal(modalEl);
            modal.show();

            let confirmBtn = modalEl.querySelector('.confirm');
            confirmBtn.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopImmediatePropagation();

                form.submit();
            });
        });
    });
});
