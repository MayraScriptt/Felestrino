document.querySelectorAll('img[loading="lazy"]').forEach((image) => {
    image.decoding = 'async'
})
