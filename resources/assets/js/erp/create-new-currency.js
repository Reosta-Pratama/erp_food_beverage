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
    
});
