<div class="error_report">{error_tip}</div>
<form action={submitUrl} method="post">
    <p>用户名: <input type="text" name="name" /></p>
    <p>密 码: <input type="password" name="password" /></p>
    <input type="hidden" name="act" value="signin" />
    <input type="submit" value="登录" />
</form>