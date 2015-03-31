<h2>{title}</h2>

<div class="error_report"><?php echo validation_errors(); ?></div>

<form action={submitUrl} method="post">
    <p>用 户 名: <input type="text" name="username" value={username} /></p>
    <p>密  码: <input type="password" name="password" /></p>
    <p>重新输入密码: <input type="password" name="password2" /></p>
    <input type="hidden" name="id" value={id} />
    <input type="submit" value="重置" />
</form>
