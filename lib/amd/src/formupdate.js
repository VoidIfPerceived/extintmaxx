
document.addEventListener('change', function(e) {
    if (e.target.name === 'profile') {
        window.console.log('Profile changed to: ' + e.target.value);

        const url = new URL(window.location.href);
        url.searchParams.set('profile', e.target.value);
        window.location.href = url.toString();
    }
});