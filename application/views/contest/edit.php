<h2>{title}</h2>

<div class="error_report"><?php echo validation_errors(); ?></div>

<form action={submitUrl} method="post">
{formContent}

</form>
