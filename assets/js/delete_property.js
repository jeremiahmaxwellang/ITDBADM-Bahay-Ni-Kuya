function deleteProperty(id) {
    if (confirm("Are you sure you want to delete this property?")) {
        fetch('../assets/php/sql_delete_property.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(response => "Property successfully deleted")
        .then(data => {
            alert(data); // Optional feedback
            location.reload(); // Refresh page to reflect changes
        })
        .catch(error => console.error("Error:", error));
    }
}