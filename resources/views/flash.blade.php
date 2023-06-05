@if ($success = Session::get('success') || $danger = Session::get('danger') || $info = Session::get('info') || $notice = Session::get('notice'))
	@if ($success = Session::get('success'))
		@php Session::forget('success') @endphp
		<script>$(document).ready(function(){showsuccess('<?= addslashes($success) ?>')});</script>
		@endif
	@if ($danger = Session::get('danger'))
		@php Session::forget('danger') @endphp
		<script>$(document).ready(function(){showerror('<?= addslashes($danger) ?>')});</script>
	@endif
	@if ($info = Session::get('info'))
		@php Session::forget('info') @endphp
		<script>$(document).ready(function(){showerror('<?= addslashes($info) ?>')});</script>
	@endif
	@if ($notice = Session::get('notice'))
		@php Session::forget('notice') @endphp
		<script>$(document).ready(function(){showerror('<?= addslashes($notice) ?>')});</script>
	@endif
@endif
