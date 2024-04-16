<?php // ** Lampkin 2024 ** // ?>
<?php // ** FOOTER public 10 - Bootstrap 5.3  ** // ?>
			</div>
		</section>
		<?php include_once "includes/modal.php"; ?>

		<footer>

			<div class="footer-admin">
				<strong>&copy; <?php echo date('Y') ?> CommissionerCollege.com :: <?php echo $app_full_name ;?> </strong>
			| All rights reserved.
			| <a href="admin" target="_blank"> Admin </a>
			<?php // echo $app_full_name; ?>

				<br />
				<?php //include "godaddy_seal.php"; ?>
			</div>
		</footer>

		<?php //! Bootstrap  -- Jan 2024  ?>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

		<?php //! SaveScroll  ?>
			<script type="text/javascript" src="js/save-scroll.js"></script>

		<?php //! CUSTOM JavaScript  ?>
			<script type="text/javascript" src="js/custom.js"></script>
			<script type="text/javascript" src="js/ajax.js"></script>

		<?php
		/* close connection */
			mysqli_close($con);
		?>
	</body>
</html>
