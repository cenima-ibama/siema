<!-- form load fail -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

<script type="text/javascript">
	(function() {
		$(document).ready(function() {
			var submitBtn = window.top.document.getElementById("submit");
			$(submitBtn).hide();
		});
	}).call(this);
</script>

<div class="alert alert-error fade in">
    <strong>Formulário não existente no banco!</strong>
</div>