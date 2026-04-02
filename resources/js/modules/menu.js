const button = document.querySelector('[data-menu-toggle]')
const nav = document.querySelector('[data-main-nav]')

if (button && nav) {
    button.addEventListener('click', () => {
        nav.classList.toggle('is-open')
    })
}
