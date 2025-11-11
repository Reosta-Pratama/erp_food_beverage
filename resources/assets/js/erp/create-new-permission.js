document.addEventListener('DOMContentLoaded', function() {
    
    // Handle CRUD checkbox visual feedback
    document.querySelectorAll('.crud-checkbox').forEach(checkbox => {
        const card = checkbox.closest('.crud-checkbox-card');
        
        // Set initial state
        if (checkbox.checked) {
            card.classList.add('checked');
        }
        
        // Toggle on change
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                card.classList.add('checked');
            } else {
                card.classList.remove('checked');
            }
        });
    });
    
    // Auto-generate permission code from permission name
    document.getElementById('permission_name').addEventListener('input', function() {
        const moduleName = document.getElementById('module_name').value;
        const permissionName = this.value;
        
        const moduleCode = moduleName
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '_');
        
        const permissionCode = permissionName
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '_');
        
        if (moduleCode && permissionCode) {
            document.getElementById('permission_code').value = moduleCode + '.' + permissionCode;
        } else if (permissionCode) {
            document.getElementById('permission_code').value = permissionCode;
        }
    });
    
    // Update permission code when module changes
    document.getElementById('module_name').addEventListener('input', function() {
        const permissionName = document.getElementById('permission_name').value;
        if (permissionName) {
            document.getElementById('permission_name').dispatchEvent(new Event('input'));
        }
    });
    
    // Module dropdown selection
    document.querySelectorAll('.module-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const moduleName = this.dataset.module;
            document.getElementById('module_name').value = moduleName;
            
            // Trigger permission code update
            const permissionName = document.getElementById('permission_name').value;
            if (permissionName) {
                document.getElementById('permission_name').dispatchEvent(new Event('input'));
            }
        });
    });
    
    // Form validation
    document.getElementById('permissionForm').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('.crud-checkbox');
        const isChecked = Array.from(checkboxes).some(cb => cb.checked);
        
        if (!isChecked) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No CRUD Selected',
                text: 'Please select at least one CRUD permission (Create, Read, Update, or Delete).',
                confirmButtonText: 'OK'
            });
            return false;
        }
    });
});