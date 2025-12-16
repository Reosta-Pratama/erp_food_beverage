// ============================================================================
// PRODUCT LIST - Checkbox Selection & Bulk Operations
// ============================================================================

function selectAll() {
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = true);
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = true;
    }
    updateSelectedCount();
}

function deselectAll() {
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = false);
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    updateSelectedCount();
}

function toggleAll(checkbox) {
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = checkbox.checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const checked = document.querySelectorAll('.product-checkbox:checked');
    const allCheckboxes = document.querySelectorAll('.product-checkbox');
    const selectAllBtn = document.getElementById('selectAll');
    const selectedCountEl = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');

    // Update selected count text
    if (selectedCountEl) {
        selectedCountEl.textContent = checked.length > 0 ? `${checked.length} selected` : '0 selected';
    }

    // Update select all checkbox state
    const allSelected = checked.length === allCheckboxes.length && allCheckboxes.length > 0;
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = allSelected;
    }

    // Toggle active class on select all button
    if (selectAllBtn) {
        selectAllBtn.classList.toggle('active', allSelected);
    }

    // Store selected IDs in hidden field
    const selectedIds = Array.from(checked).map(cb => cb.value);
    saveSelectedProducts(selectedIds);
}

function saveSelectedProducts(ids) {
    const hiddenField = document.getElementById('selectedProductIds');
    if (hiddenField) {
        hiddenField.value = JSON.stringify(ids);
    }
}

function showBulkPriceModal() {
    const checked = document.querySelectorAll('.product-checkbox:checked');

    if (checked.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation',
            text: 'Please select at least one product.',
            confirmButtonText: 'OK'
        });
        return;
    }

    const ids = Array.from(checked).map(cb => cb.value);
    const bulkProductIdsEl = document.getElementById('bulkProductIds');
    const bulkSelectedCountEl = document.getElementById('bulkSelectedCount');

    if (bulkProductIdsEl) {
        bulkProductIdsEl.value = JSON.stringify(ids);
    }
    
    if (bulkSelectedCountEl) {
        bulkSelectedCountEl.textContent = ids.length;
    }

    const modalEl = document.getElementById('bulkPriceModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function restoreSelection() {
    const hiddenField = document.getElementById('selectedProductIds');
    if (hiddenField && hiddenField.value) {
        try {
            const saved = JSON.parse(hiddenField.value);
            document.querySelectorAll('.product-checkbox').forEach(cb => {
                if (saved.includes(cb.value)) {
                    cb.checked = true;
                }
            });
        } catch (e) {
            console.error('Error parsing saved selection:', e);
        }
    }
}


// ============================================================================
// PRODUCT FORM - Create & Update (Pricing Calculator & Validation)
// ============================================================================

function calculatePricing() {
    const costInput = document.getElementById('standard_cost');
    const priceInput = document.getElementById('selling_price');
    
    if (!costInput || !priceInput) return;
    
    const cost = parseFloat(costInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    
    const marginDisplay = document.getElementById('marginDisplay');
    const markupDisplay = document.getElementById('markupDisplay');
    
    if (!marginDisplay || !markupDisplay) return;
    
    if (cost > 0 && price > 0) {
        // Margin = (Price - Cost) / Price * 100
        const margin = ((price - cost) / price * 100).toFixed(2);
        marginDisplay.textContent = margin + '%';
        
        // Markup = (Price - Cost) / Cost * 100
        const markup = ((price - cost) / cost * 100).toFixed(2);
        markupDisplay.textContent = markup + '%';
    } else {
        marginDisplay.textContent = '-';
        markupDisplay.textContent = '-';
    }
}

function initProductFormValidation() {
    const productForm = document.getElementById('productForm');
    
    if (!productForm) return;
    
    productForm.addEventListener('submit', async function (e) {
        const required = ['product_name', 'product_type', 'category_id', 'uom_id'];
        let isValid = true;
        let lastInvalidField = '';

        required.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            let value = field.value;

            if (fieldName === 'product_name') {
                value = value.trim();
                field.value = value; 
            }

            if (!isValid) return;

            if (!value) {
                isValid = false;
                field.classList.add('is-invalid');
                lastInvalidField = field.getAttribute('data-name') || fieldName.replace('_', ' ');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();

            await Swal.fire({
                icon: 'warning',
                title: 'Validation',
                text: `Please complete the required field: ${lastInvalidField}.`,
                confirmButtonText: 'OK'
            });

            return;
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span> Saving...
            `;
        }
    });
}

function initPricingCalculator() {
    const standardCost = document.getElementById('standard_cost');
    const sellingPrice = document.getElementById('selling_price');
    
    if (standardCost) {
        standardCost.addEventListener('input', calculatePricing);
    }

    if (sellingPrice) {
        sellingPrice.addEventListener('input', calculatePricing);
    }

    calculatePricing();
}


// ============================================================================
// DOM READY - Initialize All Features
// ============================================================================

document.addEventListener('DOMContentLoaded', function () {

    const singleSelect = document.querySelectorAll('.single-select');
    if (singleSelect.length > 0) {
        singleSelect.forEach((dropdown) => {
            new Choices(dropdown, {
                searchEnabled: true,
                allowHTML: true
            });
        });
    }

    // --- PRODUCT LIST FEATURES ---
    restoreSelection();

    // Event listeners for product checkboxes
    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    // Event listener for select all checkbox
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            toggleAll(this);
        });
    }

    updateSelectedCount();

    // --- PRODUCT FORM FEATURES (Create & Update) ---
    initPricingCalculator();
    initProductFormValidation();
    
});


// ============================================================================
// EXPORT FUNCTIONS - Make available globally for inline HTML calls
// ============================================================================

window.selectAll = selectAll;
window.deselectAll = deselectAll;
window.toggleAll = toggleAll;
window.showBulkPriceModal = showBulkPriceModal;