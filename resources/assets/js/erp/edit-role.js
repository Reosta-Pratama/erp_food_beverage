document.addEventListener('DOMContentLoaded', function() {
    
    // Update permission count
    function updatePermissionCount() {
        const count = document.querySelectorAll('input[name="permissions[]"]:checked').length;
        document.getElementById('permissionCount').textContent = count;
    }
    
    // Check module checkboxes based on initial state
    document.querySelectorAll('.select-all-module').forEach(checkbox => {
        const module = checkbox.dataset.module;
        const moduleCheckboxes = document.querySelectorAll(`input[data-module="${module}"].module-permission`);
        const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(moduleCheckboxes).some(cb => cb.checked);
        
        checkbox.checked = allChecked;
        checkbox.indeterminate = someChecked && !allChecked;
    });
    
    // Select/Deselect module permissions
    document.querySelectorAll('.select-all-module').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckboxes = document.querySelectorAll(`input[data-module="${module}"].module-permission`);
            
            moduleCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            
            this.indeterminate = false;
            updatePermissionCount();
        });
    });
    
    // Update module checkbox when individual permission changes
    document.querySelectorAll('.module-permission').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckboxes = document.querySelectorAll(`input[data-module="${module}"].module-permission`);
            const moduleSelectAll = document.querySelector(`input[data-module="${module}"].select-all-module`);
            
            const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(moduleCheckboxes).some(cb => cb.checked);
            
            if (moduleSelectAll) {
                moduleSelectAll.checked = allChecked;
                moduleSelectAll.indeterminate = someChecked && !allChecked;
            }
            
            updatePermissionCount();
        });
    });
    
    // Form validation
    document.getElementById('roleForm').addEventListener('submit', function(e) {
        const checkedCount = document.querySelectorAll('input[name="permissions[]"]:checked').length;
        
        if (checkedCount === 0) {
            e.preventDefault();
            alert('Please select at least one permission for this role.');
            return false;
        }
    });
});