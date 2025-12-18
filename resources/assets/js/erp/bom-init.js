// ============================================================================
// BOM LIST - Filter & Delete Modal
// ============================================================================
// Toggle Filters
function toggleFilters() {
    const filtersCard = document.getElementById('filtersCard');
    filtersCard.style.display = filtersCard.style.display === 'none' ? 'block' : 'none';
}

// Confirm Delete
function confirmDelete(bomCode, productName) {
    document.getElementById('deleteBomCode').textContent = bomCode;
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteForm').action = `/inventory/bom/${bomCode}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// ============================================================================
// BOM FORM - Create & Update 
// ============================================================================
let itemIndex = 0;

// Helpers
function formatCurrency(amount) {
    return 'Rp ' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function initSelect2(context = document) {
    context.querySelectorAll('.select-2').forEach(el => {
        if (!$(el).hasClass("select2-hidden-accessible")) {
            $(el).select2({
                width: '100%'
            });
        }
    });
}

// Add / Remove BOM Item
function addBOMItem() {
    itemIndex++;
    
    // Clone template
    const template = document.getElementById('bomItemTemplate');
    const clone = template.content.cloneNode(true);
    
    // Replace placeholders
    const wrapper = document.createElement('div');
    wrapper.innerHTML = clone.querySelector('.bom-item').outerHTML.replace(/INDEX_PLACEHOLDER/g, itemIndex);

    const newItem = wrapper.firstElementChild;

    // Append to container
    document.getElementById('bomItemsContainer').appendChild(newItem);
    
    initSelect2(newItem);

    // Hide empty state
    document.getElementById('emptyState').style.display = 'none';
    
    // Update counter
    updateTotalItems();
}

function removeBOMItem(button) {
    Swal.fire({
        icon: 'warning',
        title: 'Remove Item',
        text: 'Are you sure you want to remove this item?',
        showCancelButton: true,
        confirmButtonColor: '#985ffd',
        cancelButtonColor: "#faf8fd",
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('.bom-item').remove();
            updateTotalItems();
            calculateTotalCost();
            
            // Show empty state if no items
            if (document.querySelectorAll('.bom-item').length === 0) {
                document.getElementById('emptyState').style.display = 'block';
            }

            Swal.fire({
                icon: 'success',
                title: 'Removed',
                text: 'Item has been removed.',
                timer: 1200,
                showConfirmButton: false
            });
        }
    });
}

// Update Material & Cost
function updateMaterialInfo(select) {
    const option = select.options[select.selectedIndex];
    const card = select.closest('.bom-item');
    
    if (option.value) {
        // Auto-fill UOM
        const uomSelect = card.querySelector('select[name*="[uom_id]"]');
        const uomValue = option.dataset.uom;
        if (uomSelect && uomValue) {
            $(uomSelect).val(uomValue).trigger('change');
        }
        
        // Auto-fill Item Type
        const typeSelect = card.querySelector('select[name*="[item_type]"]');
        const materialType = option.dataset.type;
        if (typeSelect && materialType) {
            $(typeSelect).val(materialType).trigger('change');
        }
        
        // Update unit cost display
        const cost = parseFloat(option.getAttribute('data-cost')) || 0;
        card.querySelector('.unit-cost').textContent = formatCurrency(cost);
        
        // Recalculate item cost
        const quantityInput = card.querySelector('.quantity-input');
        if (quantityInput.value) {
            calculateItemCost(quantityInput);
        }
    }
}

function calculateItemCost(input) {
    const card = input.closest('.bom-item');
    const materialSelect = card.querySelector('.material-select');
    const option = materialSelect.options[materialSelect.selectedIndex];
    
    if (option.value) {
        const unitCost = parseFloat(option.getAttribute('data-cost')) || 0;
        const quantity = parseFloat(input.value) || 0;
        const itemCost = unitCost * quantity;
        
        card.querySelector('.item-cost').textContent = formatCurrency(itemCost);
        
        // Recalculate total
        calculateTotalCost();
    }
}

function calculateTotalCost() {
    let total = 0;
    document.querySelectorAll('.bom-item').forEach(item => {
        const costText = item.querySelector('.item-cost').textContent;
        const cost = parseFloat(costText.replace(/[^0-9.-]+/g, '')) || 0;
        total += cost;
    });
    
    document.getElementById('totalCost').textContent = formatCurrency(total);
}

// UI Updates
function updateTotalItems() {
    const count = document.querySelectorAll('.bom-item').length;
    document.getElementById('totalItems').textContent = count;
    
    // Renumber items
    document.querySelectorAll('.bom-item').forEach((item, index) => {
        item.querySelector('.item-number').textContent = index + 1;
    });
}

// Validation 
function initFormValidation() {
    const bomForm = document.getElementById('bomForm');
    const required = ['product_id'];
    
    if (!bomForm) return;

    bomForm.addEventListener('submit', async function (e) {
        // Reset validation state on each submit
        let isValid = true;
        let lastInvalidField = '';
        
        const items = document.querySelectorAll('.bom-item');

        // Validate required fields
        required.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            
            if (!field) return; 
            
            let value = field.value.trim(); 

            if (!value) {
                isValid = false;
                field.classList.add('is-invalid');
                lastInvalidField = field.getAttribute('data-name') || fieldName.replace(/_/g, ' ');
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

        if (items.length === 0) {
            e.preventDefault();

            await Swal.fire({
                icon: 'warning',
                title: 'Validation',
                text: 'Please add at least one BOM item',
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

    const dateRanges = document.querySelectorAll('.daterange');
    if (dateRanges.length > 0) {
        dateRanges.forEach(element => {
            const dateFrom = element.dataset.dateFrom;
            const dateTo   = element.dataset.dateTo;

            const config = {
                mode: "range",
                dateFormat: "Y-m-d",
                disableMobile: true
            };

            if (dateFrom && dateTo) {
                config.defaultDate = [dateFrom, dateTo];
            }

            flatpickr(element, config);
        });
    }

    const singeDate = document.querySelectorAll('.single-date');
    if (singeDate.length > 0) {
        singeDate.forEach((element) => {
            const date = element.getAttribute('date') || null;

            flatpickr(element, {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",      
                disableMobile: true,      
                defaultDate: date
            });
        });
    }

    initSelect2();

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') || urlParams.has('product_type') || urlParams.has('status') || urlParams.has('sort')) {
        document.getElementById('filtersCard').style.display = 'block';
    }

    // --- FORM FEATURES (Create & Update) ---
    initFormValidation();

});

// ============================================================================
// EXPORT FUNCTIONS - Make available globally for inline HTML calls
// ============================================================================
window.toggleFilters = toggleFilters;
window.confirmDelete = confirmDelete;
window.addBOMItem = addBOMItem;
window.removeBOMItem = removeBOMItem;
window.updateMaterialInfo = updateMaterialInfo;
window.calculateItemCost = calculateItemCost;