function copyToClipboard() {
    const input = document.getElementById("server-address-input");
    navigator.clipboard
        .writeText(input.value)
        .then(_ => alert("Address copied to the clipboard!"))
}