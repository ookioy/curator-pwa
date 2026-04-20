<footer>
    <p><small>&copy; <?= date('Y') ?> Інформаційна система куратора</small></p>
</footer>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch(console.error);
  });
}
</script>

</body>
</html>