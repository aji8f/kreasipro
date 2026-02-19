    </main>
    </div> <!-- End .admin-wrapper -->

    <!-- Scripts -->
    <script src="../lib/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
