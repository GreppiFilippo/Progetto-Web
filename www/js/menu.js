let timer = null;

/**
 * Debounce form submission to avoid excessive requests.
 * @param {HTMLFormElement} form - The form to be submitted.
 */
function debouncedSubmit(form) {
    clearTimeout(timer);
    timer = setTimeout(() => {
        form.submit();
    }, 400);
}