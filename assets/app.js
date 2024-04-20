import './bootstrap.js';
import './styles/app.css';

document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();
    let selectedRole = document.querySelector('select[name="roles"]').value;
    let formData = new FormData(event.target);
    formData.set('roles', JSON.stringify([selectedRole]));
    fetch(event.target.action, {
        method: 'POST',
        body: formData
    }).then(function(response) {
        return response.json();
    });
});