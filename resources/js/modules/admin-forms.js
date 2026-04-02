document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm('Deseja confirmar esta ação?')) {
            event.preventDefault()
        }
    })
})
