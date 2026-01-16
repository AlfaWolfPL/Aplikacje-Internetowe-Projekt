document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('input[name="q"]');
    if (!input) return;

    input.addEventListener('input', function(e) {
        const val = this.value;
        if (val.length < 2) return;

        if (e.inputType === 'deleteContentBackward') return;

        fetch('index.php?action=autocomplete&q=' + encodeURIComponent(val))
            .then(r => r.json())
            .then(data => {
                if (data.full_title && data.suggestion_part) {
                    const originalVal = val;
                    const suggestion = data.suggestion_part;

                    if (data.full_title.toLowerCase().startsWith(originalVal.toLowerCase())) {
                        input.value = originalVal + suggestion;
                        input.setSelectionRange(originalVal.length, input.value.length);
                    }
                }
            })
            .catch(err => console.error(err));
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && this.selectionStart !== this.selectionEnd) {
            e.preventDefault();
            this.setSelectionRange(this.value.length, this.value.length);
        }
    });
});
