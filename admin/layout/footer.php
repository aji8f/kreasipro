    </main>
    </div> <!-- End .admin-wrapper -->

    <!-- Scripts: jQuery first, then Bootstrap Bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="../lib/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle Logic
        $(document).ready(function() {
            $('#sidebarToggle, #sidebarOverlay').on('click', function() {
                $('#sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('show');
            });
        });
    </script>
</body>
</html>
