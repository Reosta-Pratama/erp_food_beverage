document.addEventListener('DOMContentLoaded', function() {
    
    // Confirm before clearing
    document.querySelector('#clearModal form')?.addEventListener('submit', function(e) {
        const days = document.getElementById('days').value;
        if (days && !confirm(`FINAL CONFIRMATION: Delete all audit logs older than ${days} days?\n\nThis action is PERMANENT and CANNOT be undone!`)) {
            e.preventDefault();
        }
    });
    
});