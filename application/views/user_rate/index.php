<div>
    <table >
        <tr>
            <td>
                <form action={submitUrl} method="get">
                    比赛id：
                    <input type='input' name='contestId' size='10' >
                    <input type='submit' value='比赛评分'>
                </form>
            </td>
            <td>
                <form action={submitUrl} method="get">
                    评委id：
                    <input type='input' name='raterId' size='10' >
                    <input type='submit' value='评委评分'>
                </form>
            </td>
            <td>
                <form action={submitUrl} method="get">
                    团队id：
                    <input type='input' name='teamId' size='10' >
                    <input type='submit' value='团队得分'>
                </form>
            <td>
            <td>
                <form action={submitUrl} method="get">
                    比赛id：
                    <input type='input' name='contestId' size='10' >
                    <input type="hidden" name="act" value="stat" />
                    <input type='submit' value='结果统计'>
                </form>
            <td>
        <tr>
    </table>
</div>


<div>
    <h3>结果集：{resultTitle} {resultLength}</h3>
    {tableContent}
</div>