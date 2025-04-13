<script>
function updateTime() {
    const date = new Date();
    const optionsDate = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    const optionsTime = {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    };
    const dateString = date.toLocaleDateString('en-US', optionsDate);
    const timeString = date.toLocaleTimeString('en-US', optionsTime);
    const dateTimeElement = document.getElementById('date-time');
    if (dateTimeElement) {
        dateTimeElement.textContent = `${dateString} | ${timeString}`;
    }
}
updateTime();
setInterval(updateTime, 1000);
</script>

