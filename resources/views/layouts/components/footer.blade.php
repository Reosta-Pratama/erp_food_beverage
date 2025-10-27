<footer
    id="footer"
    class="app-footer">
    <!-- Footer Content -->
    <div class="footer-container container-fluid px-4">
        <div class="footer-content">
            <!-- Copyright -->
            <span class="copyright">
                Copyright &copy; 
                <span class="year"  document.getElementById('currentYear').textContent = new Date().getFullYear();></span>
                <a href="javascript:void(0)" class="app-name">{{ config('app.name') }}</a>.
                All rights reserved.
            </span>
        </div>
    </div>

    <script type="text/javascript">
        document.querySelector('.year').textContent = new Date().getFullYear();
    </script>
</footer>