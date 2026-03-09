/**
 * Flight search form redirect handler
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get all flight search forms
    const searchForms = document.querySelectorAll('form[action="/api/search"]');
    
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                const spinner = submitButton.querySelector('.spinner-border');
                const btnText = submitButton.querySelector('span:not(.spinner-border)');
                
                if (spinner) {
                    spinner.classList.remove('d-none');
                }
                if (btnText) {
                    btnText.textContent = 'Searching...';
                }
                
                submitButton.disabled = true;
            }
            
            // Submit the form
            const formData = new FormData(this);
            
            fetch('/api/search', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                redirect: 'follow'
            }).then(response => {
                // Redirect to flight-results page regardless of the API response
                window.location.href = '/flights/results';
            }).catch(error => {
                console.error('Error:', error);
                // Still redirect to results page even on error
                window.location.href = '/flights/results';
            });
        });
    });
});
