    </div> <!-- Close main-content -->
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Confirm before delete
        function confirmDelete(item, url) {
            if(confirm(`Are you sure you want to delete this ${item}?`)) {
                window.location.href = url;
            }
        }
        
        // Search functionality
        function searchTable(tableId) {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById(tableId);
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let show = false;
                
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            show = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = show ? '' : 'none';
            }
        }
        
        // Form validation
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const required = form.querySelectorAll('[required]');
            let valid = true;
            
            required.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            return valid;
        }
    </script>
</body>
</html>