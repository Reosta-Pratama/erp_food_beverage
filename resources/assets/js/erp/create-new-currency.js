document.addEventListener('DOMContentLoaded', function () {

    // Auto uppercase currency code
    const currencyCode = document.getElementById('currency_code');
    currencyCode.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    // Base currency exchange rate logic
    const baseCurrencyCheckbox = document.getElementById('is_base_currency');
    const exchangeRateInput = document.getElementById('exchange_rate');

    function updateExchangeRateState() {
        if (baseCurrencyCheckbox.checked) {
            exchangeRateInput.value = '1.000000';
            exchangeRateInput.readOnly = true;
            exchangeRateInput.classList.add('bg-light');
        } else {
            exchangeRateInput.readOnly = false;
            exchangeRateInput.classList.remove('bg-light');
        }
    }

    // Run on load
    updateExchangeRateState();

    // Run on change
    baseCurrencyCheckbox.addEventListener('change', updateExchangeRateState);
    

    const btnSetBase = document.getElementById('btnSetBase');
    if (btnSetBase) {

        btnSetBase.addEventListener('click', function () {
            const form = document.getElementById('setBaseForm');
            const currencyCode = btnSetBase.dataset.code; 

            Swal.fire({
                icon: 'warning',
                title: 'Set as Base Currency?',
                text: `This will set ${currencyCode} as the system's base currency.`,
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Yes, set as base',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#985ffd',
                cancelButtonColor: '#faf8fd'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

});
