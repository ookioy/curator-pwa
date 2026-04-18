<script>
(function () {
    var prefix   = document.getElementById('phone_prefix');
    var number   = document.getElementById('phone_number');
    var combined = document.getElementById('phone_combined');
    function update() {
        combined.value = number.value.trim() ? prefix.value + number.value.trim() : '';
    }
    prefix.addEventListener('change', update);
    number.addEventListener('input',  update);
    update();
}());
</script>
