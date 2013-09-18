 <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
 <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
 <script>
	var btnSubmit = window.parent.document.getElementById("submit");
	var btnNext = window.parent.document.getElementById("modalBtnNext");
	var btnBack = window.parent.document.getElementById("modalBtnBack");
	var btnCancel = window.parent.document.getElementById("modalBtnCancel");
	var btnClose = window.parent.document.getElementById("btnClose");

	$(btnSubmit).hide();
	$(btnCancel).hide();
	$(btnClose).show();


	$("#btnFechar").on ('click', function (event) {
		$("#modalBtnBack").prop('style','')
		$("#modalBtnNext").prop('style','');
		$("#btnFechar").prop('sytle','display:none');
	});
 </script>