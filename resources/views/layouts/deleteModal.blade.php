<div id="Modal_delete" class="modal fade" role="dialog">
  	<div class="modal-dialog">	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"> Delete Conformation</h4>
	      </div>
	      	<form id="form_id" method="post" action="" class="form-horizontal">
		      	<div class="modal-body">
		      		{{ csrf_field() }}
		      		<h4> Are you sure to delete this record ?</h4>
		        	<input type="hidden" name="_method" value="delete">     	 
		      	</div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
			        <button type="submit" class="btn btn-primary">Yes</button>
			    </div>
	      	</form>
	    </div>
  	</div>
</div>
<script>

// header details and form method and hidden input field value send
function initModal(action)
{
	$('#form_id').attr('action','/'+action);
}	

</script>