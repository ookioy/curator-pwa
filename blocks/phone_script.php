<script>
(function () {
    document.querySelectorAll('.phone-field-wrap').forEach(function (wrap) {
        var prefix   = wrap.querySelector('.phone-prefix-select');
        var number   = wrap.querySelector('.phone-number-input');
        var combined = wrap.parentElement.querySelector('input[type="hidden"]');
        if (!prefix || !number || !combined) return;
        function update() {
            combined.value = number.value.trim() ? prefix.value + number.value.trim() : '';
        }
        prefix.addEventListener('change', update);
        number.addEventListener('input',  update);
        update();
    });
}());
</script>